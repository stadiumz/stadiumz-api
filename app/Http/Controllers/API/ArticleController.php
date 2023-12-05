<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function index()
    {
        $artikels = Article::all();

        return response()->json([
            "success" => true,
            "message" => "Article List",
            "data" => $artikels
        ]);
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'thumbnail' => 'required',
            'title' => 'required',
            'content' => 'required',
            // 'user_id' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        // Mengambil ID pengguna yang sedang login
        $user_id = Auth::id();

        // Menambahkan user_id ke input sebelum menyimpan artikel
        // $input['user_id'] = $user_id;

        $artikels = Article::create($input);

        return response()->json([
            "success" => true,
            "message" => "Article created successfully.",
            "data" => $artikels
        ]);
    }

    public function show($id)
    {
        $artikels = Article::find($id);

        if (is_null($artikels)) {
            return $this->sendError('Article not found.');
        }

        return response()->json([
            "success" => true,
            "message" => "Article retrieved successfully.",
            "data" => $artikels
        ]);
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $artikel = Article::find($id);
        return $artikel;

        $validator = Validator::make($input, [
            'title' => 'string',
            'content' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validation Error.",
                "data" => $validator->errors()
            ]);
        }

        $artikel->title = $input['title'] ?? $artikel->title;
        $artikel->content = $input['content'] ?? $artikel->content;
        $artikel->user_id = auth()->user()->id;
        $artikel->thumbnail = $input['thumbnail'] ?? $artikel->thumbnail;
        $artikel->save();

        return response()->json([
            "success" => true,
            "message" => "Article updated successfully.",
            "data" => $artikel
        ]);
    }

    public function destroy(Article $artikel)
    {
        $artikel->delete();

        return response()->json([
            "success" => true,
            "message" => "Article deleted successfully.",
            "data" => $artikel
        ]);
    }
}
