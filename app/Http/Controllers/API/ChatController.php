<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use Pusher\Pusher;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function createChat(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
           // 'from_id' => 'required',
            'to_id' => 'required',
            'message' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $user_id = Auth::id();

        $input['from_id'] = $user_id;

        $chat = Chat::create($input);

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            [
                'cluster' => env('PUSHER_APP_CLUSTER'),
                'useTLS' => true,
            ]
        );
        
        $pusher->trigger('chat-channel', 'new-chat', $chat);

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully.',
            'data' => $chat
        ], 201);
    }

    public function getChatForUser(Request $request, $userId)
    {
        $chats = Chat::where('to_id', $userId)->where('from_id', auth()->user()->id)->get();

        return response()->json(['messages' => $chats], 200);
    }
}
