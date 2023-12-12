<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Subtopic;
use App\Models\Topic;
use Illuminate\Http\Request;

class SubTopicController extends Controller
{
    function index(Topic $topic)
    {
        $subTopic = Subtopic::query()
            ->with('topic')
            ->where('topic_id', $topic->id)->get();

        return response()->json([
            "success" => true,
            "message" => "Sub Topic List",
            "data" => $subTopic
        ]);
    }
}
