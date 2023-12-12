<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function subtopic()
    {
        return $this->belongsTo(Subtopic::class);
    }

    public function answer()
    {
        return $this->hasOne(QuizAnswer::class);
    }
}
