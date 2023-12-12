<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\Subtopic;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index(Subtopic $subtopic)
    {
        $subtopic = Subtopic::find($subtopic->id);
        if(!$subtopic) {
            return response()->json([
                "success" => false,
                "message" => "Subtopic not found."
            ], 404);
        }
        
        $quizzes = Quiz::query()
            ->where('subtopic_id', $subtopic->id)
            ->with(['answer' => function($query) {
                $query->where('user_id', auth()->user()->id);
            }])
            ->get();

        return response()->json([
            "success" => true,
            "message" => "Quiz List",
            "data" => $quizzes
        ]);
    }

    public function answerQuiz($id, Request $request){
        $answer = $request->answer;

        // check if user already answered
        $quiz_answer = QuizAnswer::where('user_id', auth()->user()->id)
            ->where('quiz_id', $id)
            ->first();
        if($quiz_answer) {
            return response()->json([
                "success" => false,
                "message" => "You already answered this quiz."
            ], 400);
        }
        
        $quiz = Quiz::query()
            ->where('id', $id)
            ->where('option', $answer)
            ->first();

        
        if(!$quiz) {
            $quiz_answer = QuizAnswer::create([
                'user_id' => auth()->user()->id,
                'quiz_id' => $id,
                'answer' => $answer,
                'is_true' => false,
            ]);
        }else{
            $quiz_answer = QuizAnswer::create([
                'user_id' => auth()->user()->id,
                'quiz_id' => $id,
                'answer' => $answer,
                'is_true' => true,
            ]);
        }

        return response()->json([
            "success" => true,
            "message" => "Quiz List",
            "data" => $quiz_answer
        ]);
    }
}
