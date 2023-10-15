<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function index()
    {
        $artikels = Artikel::all();

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
            'title' => 'required',
            'content' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        // Mengambil ID pengguna yang sedang login
        $user_id = Auth::id();

        // Menambahkan user_id ke input sebelum menyimpan artikel
        $input['user_id'] = $user_id;

        $artikels = Artikel::create($input);

        return response()->json([
            "success" => true,
            "message" => "Article created successfully.",
            "data" => $artikels
        ]);

    }

    public function show($id)
    {
        $artikels = Artikel::find($id);

        if (is_null($artikels)) {
            return $this->sendError('Artikel not found.');
        }

        return response()->json([
            "success" => true,
            "message" => "Article retrieved successfully.",
            "data" => $artikels
        ]);

    }

    public function update(Request $request, Artikel $artikel)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required',
            'content' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $artikel->title = $input['title'];
        $artikel->content = $input['content'];
        $artikel->user_id = $input['user_id'];
        $artikel->save();

        return response()->json([
            "success" => true,
            "message" => "Article updated successfully.",
            "data" => $artikel
        ]);
    }

    public function destroy(Artikel $artikel)
    {
        $artikel->delete();

        return response()->json([
            "success" => true,
            "message" => "Article deleted successfully.",
            "data" => $artikel
        ]);
    }

}
