<?php

namespace App\Commands;

use App\Commands\Command;
use App\Models\CallMe;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\Facades\Mail;

class SendCallMeNumber extends Command implements SelfHandling
{

    /**
     * @var CallMe
     */
    protected $callMe;

    /**
     * Create a new job instance.
     *
     * @param CallMe $callMe
     */
    public function __construct(CallMe $callMe)
    {
        $this->callMe = $callMe;
    }

    /**
     *
     */
    public function handle()
    {
        $callMe = $this->callMe;

        Mail::send('emails/sendCallMeNumber',
            array('callMe' => $callMe),
            function ($message) {
                //$message->to(config('mail.admin_question'))->subject('Заказ звонка на сайте '. config('app.url'));
                $message->to(config('mail.admin_question'))->subject('Заказ звонка на сайте allfor2.com@Zayavki zvonkov');
            }
        );
    }
}
