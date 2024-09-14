<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EnrollmentStatus extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $sendData;

    public function __construct($sendData)
    {
        $this->sendData =  $sendData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Employer Enrollment Status')->view('emails.EnrollmentStatus',['sendData'=>  $this->sendData]);
    }
}
