<?php

namespace App\Http\Controllers;

use App\JWTToken;
use App\Message;
use App\Room;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    private $JWT;

    public function __construct()
    {
        $this->JWT = new JWTToken();
    }

    public function index(Request $req)
    {
        return view('skill/chat');
    }

    public function getRoom(Request $req, $userId)
    {
        $user = auth()->user();
        if($user->checkExpert()){
            $room = Room::where([
                ['user_id', '=', $userId],
                ['expert_id', '=', $user->id]
            ])->first();
            if(!$room){
                $room = Room::create(['user_id' => $userId, 'expert_id' => $user->id]);
                $room->messages = [];
            }else{
                $room->messages = $room->messages()->orderBy('id')->get();
            }
        }else{
            $room = Room::where([
                ['user_id', '=', $user->id],
                ['expert_id', '=', $userId]
            ])->first();
            if(!$room){
                $room = Room::create(['user_id' => $user->id, 'expert_id' => $userId]);
                $room->messages = [];
            }else {
                $room->messages = $room->messages()->orderBy('id')->get();
            }
        }

        return response()->json($room);
    }

    public function getUnread(Request $req){
        $user = auth()->user();

        //내가 참여한 방의 안읽은 메시지를 가져오려면
        $roomList = Room::where('user_id', '=', $user->id)->orWhere('expert_id', '=', $user->id)->get();

        foreach ($roomList as $room){
            $room->unread = $room->messages()->where([['read', '=', 0], ['user_id', '!=', $user->id]])->count();
        }

        return response()->json($roomList);
    }

    public function putRead(Request $req){
        $user = auth()->user();
        $roomId = $req->input('id');

        \DB::table('messages')->where([
            ['room_id', '=', $roomId],
            ['user_id', '!=', $user->id]
        ])->update(['read'=> 1]);

        return response()->json('read complete');
    }

    public function getToken(){
        $user = auth()->user();
        $isExpert = $user->checkExpert();

        //그외에 필요한 정보가 있다면 여기서 넣어주면 된다.
        $token = $this->JWT->hashing(['id'=>$user->id, 'name'=>$user->name, 'isExpert'=>$isExpert]);

        return response()->json($token);
    }
}
