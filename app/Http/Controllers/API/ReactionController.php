<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReactionController extends Controller
{
    public function store(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'article_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validation Error.",
                "errors" => $validator->errors()
            ], 400);
        }

        $article = Article::find($input['article_id']);

        if (!$article) {
            return response()->json([
                "success" => false,
                "message" => "Article not found."
            ], 404);
        }

        // check if user already reacted
        $reaction = Reaction::where('user_id', auth()->user()->id)
            ->where('article_id', $article->id)
            ->first();

        if ($reaction) {
            // unreact
            if ($reaction->react == 1) {
                $article->update([
                    'count_reaction' => $article->count_reaction - 1,
                ]);

                $reaction->delete();

                return response()->json([
                    "success" => true,
                    "message" => "Reaction deleted successfully.",
                    "data" => $article->reactions
                ]);
            }
        }

        $article->update([
            'count_reaction' => $article->count_reaction + 1,
        ]);

        $article->reactions()->create([
            'user_id' => auth()->user()->id,
            'react' => 1
        ]);

        return response()->json([
            "success" => true,
            "message" => "Reaction created successfully.",
            "data" => $article->reactions
        ]);
    }
}
