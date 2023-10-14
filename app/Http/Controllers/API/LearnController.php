<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use OpenAI\Laravel\Facades\OpenAI;
use YoutubeDl\Options;
use YoutubeDl\YoutubeDl;

class LearnController extends Controller
{
    function generateTopic(Request $request)
    {
        $topic = $request->topic;
        $prompt = "
        You are a teacher who is teaching a class about $topic. You are trying to explain the concept of $topic to your students. Give a learning path about $topic to your students. give many as you can of subtopics about $topic. just answer with json with format array of string. eg ['subtopic1', 'subtopic2', 'subtopic3'].
        ";
        $ask = OpenAI::chat()->create([
            'model' => 'gpt-3.5-turbo-16k',
            'messages' => [
                // admin message
                ['role' => 'system', 'content' => $prompt],
                ['role' => 'user', 'content' => "I want to learn about $topic"],
            ],
            'temperature' => 0,
        ]);

        return response()->json([
            'message' => 'success',
            'response' => json_decode($ask['choices'][0]['message']['content'], true)
        ]);
    }
}
