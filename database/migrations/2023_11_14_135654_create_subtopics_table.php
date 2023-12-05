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
        Schema::create('subtopics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('topic_id')->constrained('topics');
            $table->string('subtopic');
            $table->string('description')->nullable();
            $table->string('youtube_link')->nullable();
            $table->text('youtube_transcript')->nullable();
            $table->string('link_reference')->nullable();
            $table->integer('is_done')->default(0);
            $table->integer('is_locked')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subtopics');
    }
};
