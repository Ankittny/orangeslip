<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OfferLetterGenerated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data;

    public function __construct($data)
    {
        $this->data =$data;
        
       // $this->candidate_code =  $candidate_code;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Offer Letter Generated Successfully')->view('emails.offer_letter_generated',['data'=> $this->data]);
    }
}
