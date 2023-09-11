<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model {

    protected $fillable = [
        'participant_id',
        'chat_id',
        'body',
        'read'
    ];

    /**
     * Thread relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function chat()
    {
        return $this->belongsTo('App\Models\Chat');
    }

    /**
     * Participants relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function participant()
    {
        return $this->belongsTo('App\Models\ChatParticipant', 'participant_id');
    }

}
