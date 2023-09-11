<?php

namespace App\Commands;

use App\Commands\Command;
use App\Models\Review;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\Facades\Mail;

class QuestionAboutProduct extends Command implements SelfHandling
{

    /**
     * @var Review
     */
    protected $review;

    /**
     * Create a new job instance.
     *
     * @param Review $catalogProduct
     */
    public function __construct(Review $review)
    {
        $this->review = $review;
    }

    /**
     *
     */
    public function handle()
    {
        $review = $this->review;

        Mail::send('emails/questionAboutProduct',
            array('review' => $review),
            function ($message) {
                $message->to(config('mail.admin_question'))->subject('Вопрос на сайте allfor2.com@Voprosy o tovare');
            }
        );
    }
}
