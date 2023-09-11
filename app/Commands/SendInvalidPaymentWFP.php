<?php

namespace App\Commands;

use App\Commands\Command;
use App\Models\Order;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\Facades\Mail;

class SendInvalidPaymentWFP extends Command implements SelfHandling
{

    /**
     * @var Order
     */
    protected $order;

    /**
     * Create a new job instance.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     *
     */
    public function handle()
    {
        $order = $this->order;

        Mail::send('emails/sendInvalidPaymentWFP',
            array('order' => $order),
            function ($message) {
                $message->to(config('mail.admin_order'))->subject('Заказ на сайте allfor2.com@Zakazy');
            }
        );
    }
}
