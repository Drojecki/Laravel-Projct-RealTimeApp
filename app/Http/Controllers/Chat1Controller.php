<?php

namespace App\Http\Controllers;

use App\Events\GreetingSent;
use Illuminate\Http\Request;
use App\Models\User;
use App\Events\MessageSent;


class Chat1Controller extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function showChat()
    {
        return view('chat.show');
    }
    public function messageReceived(Request $request)
    {   
        $rules = [
            'message' => 'required',
        ];

        $request->validate($rules);

        broadcast(new MessageSent($request->user(),$request->message));

        return response()->json('Message broadcast');
    }
    public function greetReceived(Request $request, User $user) {
        broadcast(new GreetingSent($user, "{$request->user()->name} POKED you"));
        broadcast(new GreetingSent($request->user(), "You POKED {$user->name}"));
    
        return "{$user->name} from {$request->user()->name}";
    }
    
}
