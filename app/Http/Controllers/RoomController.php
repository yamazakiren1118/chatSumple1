<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Room;
use App\Message;

class RoomController extends Controller
{
    //
    public function index()
    {
        $room = Room::all();
        return view('main', ['room'=>$room]);
    }

    public function show($id)
    {
        $room = Room::find($id);
        $url = env('SOCKET_URL', 'http://localhost:3000');
  
        if(!$room){
            abort(404);
        }
        $message = $room->messages;
        return view('show', ['id'=>$id, 'message'=>$message, 'url'=>$url]);
    }

    public function create(Request $request)
    {
        $room = new Room();
        $room->fill($request->all());
        $room->save();
        $room = $room->toArray();
        $room = $room + ['url' => action('RoomController@show',$room['id'])];
        return response()->json($room);
        // return redirect()->action('RoomController@show', ['id'=>$room->id]);
    }
}

