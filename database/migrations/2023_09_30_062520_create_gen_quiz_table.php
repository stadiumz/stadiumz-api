<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('gen_quiz', function (Blueprint $table) {
            $table->id();
            // relation to gen_subtopic
            $table->foreignId('gen_subtopic_id')->constrained('gen_subtopic');
            $table->string('question');
            $table->string('answer');
            $table->string('wrong_answer_1');
            $table->string('wrong_answer_2');
            $table->string('wrong_answer_3');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gen_quiz');
    }
};
