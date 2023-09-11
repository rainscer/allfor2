<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatParticipant extends Model {

    protected $fillable = [
        'user_session_id',
        'chat_id',
        'support',
        'is_typing'
    ];


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'last_read'];

}
