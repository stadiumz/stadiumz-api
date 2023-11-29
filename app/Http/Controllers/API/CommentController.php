<?php

namespace App\Http\Controllers\API;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'comment' => 'required',
            'artikel_id' => 'required',
            'user_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validation Error.",
                "data" => $validator->errors()
            ]);
        }

        $comments = Comment::create($input);

        return response()->json([
            "success" => true,
            "message" => "Comment created successfully.",
            "data" => $comments
        ]);
    }
}
