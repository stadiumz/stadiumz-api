<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\Chats;

class ChatController extends Controller
{
    public function createChat(Request $request)
    {   
        $input = $request->all();

        $validator = Validator::make($input, [
            'from_id' => 'required|exists:users,id',
            'to_id' => 'required|exists:users,id',
            'message' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $chat = Chats::create($input);

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully.',
            'data' => $chat
        ], 201);
    }

    public function getChatForUser(Request $request, $userId)
    {
        $chats = Chats::where('to_id', $userId)->orWhere('from_id', $userId)->get();

        return response()->json(['messages' => $chats], 200);
    }
}