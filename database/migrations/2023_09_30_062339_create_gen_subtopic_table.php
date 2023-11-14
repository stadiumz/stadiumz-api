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
        Schema::create('gen_subtopic', function (Blueprint $table) {
            $table->id();
            // relation to gen_topic
            $table->foreignId('gen_topic_id')->constrained('gen_topic');
            $table->string('subtopic');
            $table->string('youtube_link');
            $table->text('youtube_transcript');
            $table->text('youtube_transcript_summary');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gen_subtopic');
    }
};
