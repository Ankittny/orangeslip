<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ChatBot;
use App\Models\Country;
use BotMan\BotMan\BotMan;
use Laracasts\Flash\Flash;
use Facade\FlareClient\View;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Botman\OnboardingConversation;
use App\Botman\P2PConversation;
use App\Botman\ButtonConversation;
use App\Botman\P2PBuyConversation;
use Illuminate\Support\Facades\DB;
use App\Botman\P2PSellConversation;
use App\Botman\SupportConversation;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Messages\Incoming\Answer;
use BotMan\BotMan\Messages\Attachments\Image;
use BotMan\BotMan\Messages\Outgoing\Question;
use BotMan\BotMan\Messages\Outgoing\Actions\Button;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\BotMan\Messages\Conversations\Conversation;
use BotMan\BotMan\Cache\LaravelCache;


class BotManController extends Controller
{

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
  
        $botman->hears('{message}', function($botman, $message) {
  
            if ($message == 'hi') {
                $this->askName($botman);
            }else{
                $botman->reply("write 'hi' for testing...");
            }
  
        });
  
        $botman->listen();
    }
  
     
    public function askName($botman)
    {
        $botman->ask('How are you?', function (Answer $response) {
            $this->say('Cool - you said ' . $response->getText());
        });

        // $botman->ask('Hello! What is your Name?', function() {
  
        //     // $name = $answer->getText();
  
        //     $this->say('Nice to meet you ');
        // });
    }
    */
/*
    public function index($id = null)
    {
        $categories = ChatBot::where('sub_category', 0)->get();

        $current_category = ChatBot::where('id', $id)->first();
        if ($current_category) {
            $sub_categories = ChatBot::where('sub_category', $current_category->id)->get();

            if ($sub_categories->count() == 0) {
                return view('admin.chat_bot.category', compact('current_category'));
            } else {
                return view('admin.chat_bot.category_list', compact('sub_categories', 'current_category'));
            }
        }

        return view('admin.chat_bot.index', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $rules = [];
        if ($request->sub_category) {
            $rules += [
                'sub_category' => "required",
                'sub_category_id' => "required|exists:chat_bots,id",
                'desc' => 'nullable'
            ];
        } else {
            $rules += [
                "category" => "required",
                'desc' => 'nullable'
            ];
        }
        $this->validate($request, $rules);

        if ($request->sub_category) {
            ChatBot::create([
                'category' => $request->sub_category,
                'sub_category' => $request->sub_category_id,
                'description' => $request->desc
            ]);
        } else {
            ChatBot::create([
                'category' => $request->category,
                'sub_category' => 0,
                'description' => $request->desc
            ]);
        }


        Flash::success(__("Category added successfully."));
        return redirect()->back();
    }

    public function editCategory($id)
    {
        $category = ChatBot::where('id', $id)->first();
        if (!$category) {
            flash()->error('Unable to find category');
            return redirect()->back();
        }
        return view('admin.chat_bot.edit-category', compact('category'));
    }
    public function updateCategory(Request $request, $id)
    {
        $this->validate($request, [
            'category' => 'required'
        ]);
        $category = ChatBot::where('id', $id)->first();
        if (!$category) {
            flash()->error('Unable to find category');
            return redirect()->back();
        }
        $category->category = $request->category;
        $category->description = $request->desc;

        $category->save();
        flash()->success('Category update successful');
        return redirect()->back();
    }

    public function removeCategory($id)
    {
        $category = ChatBot::where('id', $id)->first();
        $sub_categories = ChatBot::where('sub_category', $id)->get();

        if ($category == null) {
            return json_encode([
                'status' => false,
                'message' => 'There is an error deleting category'
            ]);
        }

        $category->delete();

        foreach ($sub_categories as $sub) {
            $sub->delete();
            foreach (ChatBot::where('sub_category', $sub->id)->get() as $sub_of_sub) {
                $sub_of_sub->delete();
            }
        }
        return json_encode([
            'status' => true,
            'message' => 'Category has been deleted successfully'
        ]);
    }
*/

    public function handle()
    {
        $botman = app('botman');
        // $botman = BotManFactory::create([
        //     'config' => [
        //         'user_cache_time' => 300,
        //         'conversation_cache_time' => 400,
        //     ],
        // ], new LaravelCache());
        // $botman = BotManFactory::create($config, new LaravelCache());
        // dd($botman);
        $botman->fallback(function ($botman) {
            $botman->reply('Hey!');
            $botman->typesAndWaits(1);
            $botman->reply('I see those words of yours, but I have no idea what they mean.');
            $botman->typesAndWaits(1);

            $question =  Question::create('Start Conversation')->addButtons([
                Button::create('Start Coversation')->value('^[a-zA-Z0-9_.-]*$'),
            ]);
            $botman->reply($question);
        });

        //Hi message
        $botman->hears('^[a-zA-Z0-9_.-]*$', function ($botman) {
            $botman->startConversation(new OnboardingConversation());
        });

        //Stop out
        $botman->hears('stop', function ($botman) {
            $botman->reply('Thank you for using Caesium ChatBot.');
        })->stopsConversation();

        $botman->listen();
    }

    /*
    public function askName($botman)
    {
        $botman->ask('Hello ! What is your Name ?', function (Answer $answer) {
            $name = $answer->getText();

            $this->say('Nice to meet you !! ' . $name);
            
            // $this->bot->startConversation(new ButtonConversation());
        });
    }
    */

}
