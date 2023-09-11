<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Activity;

class Mailing extends Model {

    use SoftDeletes;
    /**
     * @var array
     */
    protected $fillable = [
        'subject',
        'body',
        'participants',
        'hit',
        'scheduled'
    ];

    /**
     * @param $participants
     * @param $subject
     * @param $body
     */
    public static function mailSend($participants, $subject, $body)
    {
        if(count($participants)) {
            $emails_validated = [];
            foreach ($participants as $email) {
                $v = Validator::make(
                    ['email' => $email],
                    ['email' => [
                        'required',
                        'email'
                    ]
                    ]);

                if (!$v->fails()) {
                    $emails_validated[] = $email;
                }
            }
            $emails_validated = array_unique($emails_validated);

            if(count($emails_validated)) {
                //Send mail to customer if email isset
                Mail::send('emails.sendMessage', ['message_text' => $body],
                    function ($message) use ($subject, $emails_validated) {

                        foreach ($emails_validated as $email) {
                            $message->to($email)->subject($subject);
                        }
                    });
            }
        }
    }

    /**
     * @param $participant_id
     */
    public static function sendMailToUserNewMessage($participant_id)
    {
        $activities = Activity::users()->lists('user_id');
        if(!in_array($participant_id, $activities)){

            $user = User::find($participant_id);
            $v = Validator::make(
                ['email' => $user->email],
                ['email' => [
                    'required',
                    'email'
                ]
                ]);

            if (!$v->fails()) {
                Mail::send('emails.newMessageInSupport', ['user' => $user],
                    function ($message) use ($user) {
                        $message->to($user->email)->subject(trans('user.newMessageOnKorovo'));
                    });
            }
        }
    }

}
