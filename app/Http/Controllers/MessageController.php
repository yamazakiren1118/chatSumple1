<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Message;

class MessageController extends Controller
{
    //
    public function create(Request $request)
    {
        $message = new Message();
        $message->fill($request->all());
        $message->user_id = Auth::id();
        // dd($message);
        $message->save();
        return redirect()->action('RoomController@show',['id'=>$message->room_id]);
    }
}
