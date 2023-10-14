<?php

namespace App\Http\Controllers;

use App\Models\comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $comments = comment::create($input);

        return response()->json([
            "success" => true,
            "message" => "Comment created successfully.",
            "data" => $comments
        ]);

    }
}
