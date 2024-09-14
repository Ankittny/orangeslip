<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Support extends Mailable
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
        return $this->subject('Support Form Details')->view('emails.Support',['sendData'=>  $this->sendData]);
    }
}
