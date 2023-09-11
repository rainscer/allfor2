<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model {

	protected $fillable = [
        'user_session_id'
    ];

    /**
     * Messages relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany('App\Models\ChatMessage');
    }

    /**
     * Participants relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function participants()
    {
        return $this->hasMany('App\Models\ChatParticipant');
    }

    /**
     * Returns the latest message from a thread
     *
     * @return \App\Models\Message
     */
    public function getLatestMessageBodyAttribute()
    {
        $message = $this->messages->last();

        if($message){
            return $message->body;
        }

       return null;
    }


    /**
     * Returns the latest message from a thread
     *
     * @return \App\Models\Message
     */
    public function getSupportUnreadAttribute()
    {
        $message = $this->messages->last();

        if($message){

            if(!is_null($message->participant->user_session_id) && !$message->read){

                return true;
            }

            return false;
        }

        return false;
    }
}
