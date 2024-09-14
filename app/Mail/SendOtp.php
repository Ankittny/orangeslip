<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendOtp extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $new_otp;

    public function __construct($new_otp)
    {
        $this->new_otp =  $new_otp;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('OTP For Candidate Login')->view('emails.send_otp',['new_otp'=>  $this->new_otp]);
    }
}
