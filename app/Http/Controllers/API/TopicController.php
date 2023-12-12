<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    function index() {
        $topic = Topic::withCount('subTopics')->where('user_id', auth()->user()->id)->get();
        return response()->json([
            "success" => true,
            "message" => "Topic List",
            "data" => $topic
        ]);
    }
}
