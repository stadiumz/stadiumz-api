<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function sendError($message, $errors = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if (!empty($errors)) {
            $response['data'] = $errors;
        }

        return response()->json($response, $code);
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $article = Article::find($input['article_id']);
        if(!$article) {
            return response()->json([
                "success" => false,
                "message" => "Article not found."
            ], 404);
        }

        $validator = Validator::make($input, [
            'comment' => 'required',
            'article_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validation Error.",
                "data" => $validator->errors()
            ], 400);
        }

        $comments = Comment::create([
            'comment' => $input['comment'],
            'article_id' => $input['article_id'],
            'user_id' => auth()->user()->id,
        ]);

        $article->update([
            'count_comment' => $article->count_comment + 1,
        ]);

        return response()->json([
            "success" => true,
            "message" => "Comment created successfully.",
            "data" => $comments
        ]);
    }
}
