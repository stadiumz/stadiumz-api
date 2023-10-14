<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\artikel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function index()
    {
        $artikels = artikel::all();

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
            'user_id' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $artikels = artikel::create($input);

        return response()->json([
            "success" => true,
            "message" => "Article created successfully.",
            "data" => $artikels
        ]);

    }

    public function show($id)
    {
        $artikels = artikel::find($id);

        if (is_null($artikels)) {
            return $this->sendError('Artikel not found.');
        }

        return response()->json([
            "success" => true,
            "message" => "Article retrieved successfully.",
            "data" => $artikels
        ]);

    }

    public function update(Request $request, artikel $artikel)
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

    public function destroy(artikel $artikel)
    {
        $artikel->delete();

        return response()->json([
            "success" => true,
            "message" => "Article deleted successfully.",
            "data" => $artikel
        ]);
    }

}
