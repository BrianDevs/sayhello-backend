<?php

namespace App\Http\Controllers;

use App\Events\SendMessage;
use App\Models\Chat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
        
        $data = array(
            'message'=> $request->input('message'),
            'receiver_id'=> $request->input('receiver_id'),
            'sender_id'=> $request->input('sender_id'),

        );
        $message = $request->input('message');
        Chat::create($data);

        // Log::info("message");
        // Log::info($request->all());

        $sendMessage = event(new SendMessage($message));
        
        // Log::info("---------------Priting message evend----------------");
        // Log::info($sendMessage);
        return response()->json(['status'=>true , 'data'=>$message]);
    }
}
