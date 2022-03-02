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

        return response()->json(['id' => $message->id]);
        // return redirect()->action('RoomController@show',['id'=>$message->room_id]);
    }

    public function delete(Request $request)
    {
        $message = Message::find($request->id);
        $message->delete();
        return response()->json(['id' => $request->id]);
    }
}
