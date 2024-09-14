<?php

namespace App\Botman;

use App\Models\Channel;
use App\Models\User;
use App\Models\ChatBot;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Conversations\Conversation;

class ButtonConversation extends Conversation
{

    public function run()
    {

        $this->bot->typesAndWaits(1);
        $this->main();
    }


    public function main()
    {
        $services = ChatBot::where('sub_category', 0)->pluck('id', 'category');


        $buttonServices = [];

        foreach ($services as $id => $value) {
            $button = Button::create($id)->value($value);
            $buttonServices[] = $button;
        }

        $question = Question::create('To generate a support description, please select one of the following options that best matches your query:')
            ->callbackId('select_service')
            ->addButtons($buttonServices);

        $this->ask($question, function (Answer $answer) {

            if ($answer->isInteractiveMessageReply()) {
                $id = $answer->getValue();

                $sub_categories = ChatBot::where('sub_category', $id)->pluck('id', 'category');

                $current_category = ChatBot::where('id', $id)->first();


                if ($sub_categories->count() == 0) {
                    $this->say($current_category->category);

                    $this->basicQuestion($current_category);
                } else {
                    $this->sub_category($sub_categories, $id);
                }
            } else {
                $this->repeat("Sorry, I didn't get that. Please select a valid option");
                return $this->main();
            }
        });
    }


    public function sub_category($sub_categories, $desc)
    {

        $this->bot->typesAndWaits(1);

        $sub_category_button = [];
        $desc = ChatBot::where('id', $desc)->first();
        foreach ($sub_categories as $id => $value) {
            $button = Button::create($id)->value($value);
            $sub_category_button[] = $button;
        }


        $question = Question::create($desc->description)
            ->callbackId('select_service')
            ->addButtons($sub_category_button);

        $this->ask($question, function (Answer $answer) {

            $id = $answer->getValue();
            $sub_categories = ChatBot::where('sub_category', $id)->pluck('id', 'category');


            if ($answer->isInteractiveMessageReply()) {


                $current_category = ChatBot::where('id', $id)->first();


                if ($sub_categories->count() == 0) {

                    $this->say($current_category->category);

                    $this->basicQuestion($current_category);
                } else {
                    $this->sub_category($sub_categories, $id);
                }
            } else {
                $this->repeat("Sorry, I didn't get that. Please select a valid option");
                return $this->main();
            }
        });
    }




    public function basicQuestion($params = null)
    {
        $this->bot->typesAndWaits(1);

        $basic_questions = [
            "home" => "Home",
            "back" => "Back",
        ];

        $basic_button = [];

        foreach ($basic_questions as $id => $value) {
            $button = Button::create($value)->value($id);
            $basic_button[] = $button;
        }
        $question = Question::create('General')
            ->callbackId('select_service')
            ->addButtons($basic_button);


        $this->ask($question, function (Answer $answer) use ($params) {
            if ($answer->isInteractiveMessageReply()) {

                if ($answer->getValue() == 'back') {

                    if ($params) {
                        $sub_categories = ChatBot::where('id', $params->sub_category)->pluck('id', 'category');

                        if ($sub_categories) {
                            $id = ChatBot::where('id', $params->sub_category)->first();
                            $this->sub_category($sub_categories, $id->id);
                        }
                    }
                } elseif ($answer->getValue() == 'home') {

                    $this->main();
                }
            }
        });
    }
}
