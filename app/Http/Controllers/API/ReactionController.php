<?php

namespace App\Http\Controllers;

use App\Models\reaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReactionController extends Controller
{
     public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'react' => 'required',
            'artikel_id' => 'required',
            'user_id' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $reactions = reaction::create($input);

        return response()->json([
            "success" => true,
            "message" => "Comment created successfully.",
            "data" => $reactions
        ]);

    }
}
