<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReactionController extends Controller
{
     public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'react' => 'required',
            'article_id' => 'required',
            'user_id' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $reactions = Reaction::create($input);

        return response()->json([
            "success" => true,
            "message" => "Reaction created successfully.",
            "data" => $reactions
        ]);

    }
}
