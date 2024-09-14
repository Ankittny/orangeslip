<?php
namespace App\Botman;

use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Cache\LaravelCache;

use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
 

class SupportConversation extends Conversation
{
    protected $name;

    protected $email;

    protected $query;


    protected function askName()
    {
        $this->ask('Hi! What is your name?',function (Answer $answer) {            
            
            // Save result
            $value = $answer->getValue();
            if (!preg_match("/^[a-zA-Z-' ]*$/", $value)) {
                return $this->repeat('Please enter a valid name');
            }
            $this->name = $value;
            $this->say('Nice to meet you ' . $this->name);
            $this->askEmail();
        });
    }

    public function askEmail()
{
    // $abc=new Answer;
     
	$this->ask('what is your email?', function(Answer $answer) {

		// Save result
		$this->email = $answer->getText();

		$this->say('Great - that is all we need, '.$this->email);

		//$this->bot->startConversation(new FavouriteLunchConversation());
	});
}
/*
    protected function askEmail()
    {
        $this->ask('One more thing - what is your email address?', function (Answer $answer) {
            // Save result
            $value = $answer->getText();
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                return $this->repeat('Please enter a valid email');
            }
            $this->email = $value;
            $this->say('Great - that is all we need, ' . $this->name);
            $this->askHelp();
        });
    }
*/
    protected function AskImage()
    {
        $this->askForImages('Please send me a pic', function ($images) {
            $this->say('Thank you' . count($images) . 'image');
        });
    }

    protected function askHelp()
    {
        $this->ask('How can I help you?', function (Answer $answer) {
            // Save result
            $this->query = $answer->getText();

            $this->say('Your query has been forwarded, we will contact you soon.');
        });
    }

    public function run()
    {
        // This will be called immediately
        $this->askHelp();
    }
}
