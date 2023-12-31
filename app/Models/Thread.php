<?php namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletes;

class Thread extends Eloquent
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'threads';

    /**
     * The attributes that can be set with Mass Assignment.
     *
     * @var array
     */
    protected $fillable = ['subject'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];


    /**
     * Messages relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany('App\Models\Message');
    }

    /**
     * Returns the latest message from a thread
     *
     * @return \App\Models\Message
     */
    public function getLatestMessageAttribute()
    {
        return $this->messages()->latest()->first();
    }

    /**
     * Participants relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function participants()
    {
        return $this->hasMany('App\Models\Participant');
    }

    /**
     * Returns the user object that created the thread
     *
     * @return mixed
     */
    public function creator()
    {
        return $this->messages()->oldest()->first()->user;
    }

    /**
     * Returns all of the latest threads by updated_at date
     *
     * @return mixed
     */
    public static function getAllLatest()
    {
        return self::latest('updated_at');
    }

    /**
     * Returns an array of user ids that are associated with the thread
     *
     * @param null $userId
     * @return array
     */
    public function participantsUserIds($userId = null)
    {
        $users = $this->participants()->withTrashed()->lists('user_id');

        if ($userId) {
            $users[] = $userId;
        }

        return $users;
    }

    /**
     * Returns an array of user ids that are associated with the thread
     *
     * @param null $userId
     * @return array
     */
    public function participantsUserIdsWithoutUser($userId)
    {
        $users = $this->participants()->withTrashed()
            ->where('participants.user_id', '<>', $userId)
            ->lists('user_id');


        return $users;
    }

    /**
     * Returns threads that the user is associated with
     *
     * @param $query
     * @param $userId
     * @return mixed
     */
    public function scopeForUser($query, $userId)
    {
        return $query->join('participants', 'threads.id', '=', 'participants.thread_id')
            ->where('participants.user_id', $userId)
            ->where('participants.deleted_at', null)
            ->select('threads.*');
    }

    /**
     * Returns threads with new messages that the user is associated with
     *
     * @param $query
     * @param $userId
     * @return mixed
     */
    public function scopeForUserWithNewMessages($query, $userId)
    {
        return $query->join('participants', 'threads.id', '=', 'participants.thread_id')
            ->where('participants.user_id', $userId)
            ->whereNull('participants.deleted_at')
            ->where(function ($query) {
                $query->where('threads.updated_at', '>', $this->getConnection()->raw($this->getConnection()->getTablePrefix() . 'participants.last_read'))
                    ->orWhereNull('participants.last_read');
            })
            ->select('threads.*');
    }

    /**
     * Returns threads between given user ids
     *
     * @param $query
     * @param $participants
     * @return mixed
     */
    public function scopeBetween($query, array $participants)
    {
        $query->whereHas('participants', function ($query) use ($participants) {
            $query->whereIn('user_id', $participants)
                ->groupBy('thread_id')
                ->havingRaw('COUNT(thread_id)='.count($participants));
        });
    }

    /**
     * Adds users to this thread
     *
     * @param array $participants list of all participants
     * @return void
     */
    public function addParticipants(array $participants)
    {
        if (count($participants)) {
            foreach ($participants as $user_id) {
                Participant::firstOrCreate([
                    'user_id' => $user_id,
                    'thread_id' => $this->id,
                ]);
            }
        }
    }

    /**
     * Mark a thread as read for a user
     *
     * @param integer $userId
     */
    public function markAsRead($userId)
    {
        try {
            $participant = $this->getParticipantFromUser($userId);
            $participant->last_read = new Carbon;
            $participant->save();
        } catch (ModelNotFoundException $e) {
            // do nothing
        }
    }

    /**
     * See if the current thread is unread by the user
     *
     * @param integer $userId
     * @return bool
     */
    public function isUnread($userId)
    {
        try {
            $participant = $this->getParticipantFromUser($userId);
            if ($this->updated_at > $participant->last_read) {
                return true;
            }
        } catch (ModelNotFoundException $e) {
            // do nothing
        }

        return false;
    }

    /**
     * Finds the participant record from a user id
     *
     * @param $userId
     * @return mixed
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getParticipantFromUser($userId)
    {
        return $this->participants()->where('user_id', $userId)->firstOrFail();
    }

    /**
     * Restores all participants within a thread that has a new message
     */
    public function activateAllParticipants()
    {
        $participants = $this->participants()->withTrashed()->get();
        foreach ($participants as $participant) {
            $participant->restore();
        }
    }

    /**
     * Generates a string of participant information
     *
     * @param null $userId
     * @param array $columns
     * @return string
     */
    public function participantsString($userId=null, $columns=['name', 'last_name'])
    {
        $participantNames = User::join('participants', 'users.id', '=', 'participants.user_id')
            ->where('participants.thread_id', $this->id)
            ->select('users.name', 'users.last_name');

        if ($userId !== null) {
            $participantNames->where('users.id', '!=', $userId);
        }

        $participantNames = $participantNames->get();

        $userNames = [];

        foreach ($participantNames as $participantName) {
            $userNames[] = $participantName->name . ' ' . $participantName->last_name;
        }

        return implode(', ', $userNames);
    }

    /**
     * Checks to see if a user is a current participant of the thread
     *
     * @param $userId
     * @return bool
     */
    public function hasParticipant($userId)
    {
        $participants = $this->participants()->where('user_id', '=', $userId);
        if ($participants->count() > 0) {
            return true;
        }

        return false;
    }

    /**
     * Generates a select string used in participantsString()
     *
     * @param $columns
     * @return string
     */
    protected function createSelectString($columns)
    {
        $dbDriver = $this->getConnection()->getDriverName();

        switch ($dbDriver) {
            case 'pgsql':
            case 'sqlite':
                $columnString = implode(" || ' ' || " . $this->getConnection()->getTablePrefix() . "users.", $columns);
                $selectString = "(" . $this->getConnection()->getTablePrefix() .  "users." . $columnString . ") as name";
                break;
            case 'sqlsrv':
                $columnString = implode(" + ' ' + " . $this->getConnection()->getTablePrefix() .  "users." , $columns);
                $selectString = "(" . $this->getConnection()->getTablePrefix() .  "users." . $columnString . ") as name";
                break;
            default:
                $columnString = implode(", ' ', " . $this->getConnection()->getTablePrefix() .  "users." , $columns);
                $selectString = "concat(" . $this->getConnection()->getTablePrefix() .  "users." . $columnString . ") as name";
        }

        return $selectString;
    }

    /**
     * Returns array of unread messages in thread for given user.
     *
     * @param $userId
     *
     * @return \Illuminate\Support\Collection
     */
    public function userUnreadMessages($userId)
    {
        $messages = $this->messages()
            ->with('user')
            ->get();
        try {
            $participant = $this->getParticipantFromUser($userId);
        }catch(ModelNotFoundException $e)
        {
            return collect();
        }

        if (!$participant->last_read) {
            return collect($messages);
        }
        $unread = [];
        $i = count($messages) - 1;
        while ($i >= 0) {
                if ($messages[$i]->updated_at->gt($participant->last_read)) {
                    array_push($unread, $messages[$i]);
                } else {
                    break;
                }
            --$i;
        }
        return collect($unread);
    }
    /**
     * Returns count of unread messages in thread for given user.
     *
     * @param $userId
     *
     * @return int
     */
    public function userUnreadMessagesCount($userId)
    {
        $messages = $this->messages()->get();
        try {
            $participant = $this->getParticipantFromUser($userId);
        }catch(ModelNotFoundException $e)
        {
            return 0;
        }

        if (!$participant->last_read) {
            return count($messages);
        }
        $count = 0;
        $i = count($messages) - 1;
        while ($i >= 0) {
            if($participant->last_read) {
                if ($messages[$i]->updated_at->gt($participant->last_read)) {
                    ++$count;
                } else {
                    break;
                }
            }else{
                ++$count;
            }
            --$i;
        }
        return $count;
    }
}
