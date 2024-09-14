<?php

namespace App\Botman;

use BotMan\BotMan\BotMan;
// use BotMan\BotMan\BotManFactory;
// use BotMan\BotMan\Drivers\DriverManager;
use Illuminate\Http\Request;

use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

class OnboardingConversation extends Conversation
{
    protected $firstname;

    protected $email;

    public function run()
    {
        // This will be called immediately
        $this->askFirstname();
    }

    public function askFirstname()
    {
        
        $this->ask('onboarding! What is your firstname?', function(Answer $answer) {
            // Save result
            // dd($answer);
            $this->firstname = $answer->getText();

            $this->say('Nice to meet you '.$this->firstname);
            $this->askEmail();
        });
    }

    public function askEmail()
    {
        $this->ask('One more thing - what is your email?', function(Answer $answer) {
            // Save result
            $this->email = $answer->getText();

            $this->say('Great - that is all we need, '.$this->firstname);
        });
    }

    
}