<?php

namespace App\Botman;

use App\Models\Channel;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Messages\Incoming\Answer;

class P2PBuyConversation extends Conversation
{
    protected $name;

    protected $email;

    protected $query;

    protected function askOrderID()
    {
        $this->ask('What is your Order Id?', function (Answer $answer) {

            $value = $answer->getText();
            $this->name = $value;
            $channel = Channel::where('channel_name', $this->name)->first();

            $status = [0 => 'Pending', 1 => 'Accepted', 2 => 'Rejected'];

            if ($channel) {
                $this->say('Your order is ' . $status[$channel->dispute_status]);
            } else {
                return $this->repeat('Please enter a valid Order ID');
            }
        });
    }

    public function run()
    {
        $this->askOrderID();
    }
}
