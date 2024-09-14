<?php
  
namespace App\Http\Controllers;
  
use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use App\Botman\OnboardingConversation;
use BotMan\BotMan\Messages\Conversations;
use BotMan\BotMan\Messages\Conversations\Conversation;

use Laracasts\Flash\Flash;
use Facade\FlareClient\View;

use Illuminate\Support\Facades\DB;

use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Cache\LaravelCache;

  
class BotManController extends Controller
{
    public function test_botman()
    {
        return view('chat_bot.test_botman');
    }
    public function view()
    {
        return view('chat_bot.view');
    }
    public function chat()
    {
        return view('chat_bot.chat');
    }
 /*
    public function handle()
    {
        $botman = app('botman');

        // $botman->fallback(function ($botman) {
        //     $botman->reply('Hey!');
        //     $botman->typesAndWaits(1);
        //     $botman->reply('I see those words of yours, but I have no idea what they mean.');
        //     $botman->typesAndWaits(1);

        //     $question =  Question::create('Start Conversation')->addButtons([
        //         Button::create('Start Coversation')->value('^[a-zA-Z0-9_.-]*$'),
        //     ]);
        //     $botman->reply($question);
        // });

        //Hi message
        $botman->hears('^[a-zA-Z0-9_.-]*$', function ($botman) {
            $botman->startConversation(new OnboardingConversation);
        });

        //Stop out
        $botman->hears('stop', function ($botman) {
            $botman->reply('Thank you for using Caesium ChatBot.');
        })->stopsConversation();

        $botman->listen();
    }
*/
    /**
     * Place your BotMan logic here.
     */
   
    public function handle()
    {
        
        // $botman = app('botman');

        $botman = BotManFactory::create([
            'config' => [
                'user_cache_time' => 300,
                'conversation_cache_time' => 400,
            ],
        ], new LaravelCache());

 



        $botman->hears('Hello', function($botman) {
            $botman->startConversation(new OnboardingConversation);
        });

        $botman->hears('stop', function ($botman) {
            $botman->reply('Thank you for using Caesium ChatBot.');
        })->stopsConversation();
   
      
 
        $botman->listen();
    }
  
    /**
     * Place your BotMan logic here.
     */
    
    public function askName($botman)
    {
        $botman->ask('controller! What is your Name?', function(Answer $answer) {
  
            $name = $answer->getText();
  
            $this->say('Nice to meet you '.$name);
        });
    }
    
    
}