<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainee_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('quiz_attempt_id');
            $table->unsignedBigInteger('quiz_question_id');
            $table->unsignedBigInteger('quiz_answer_id');
            $table->timestamps();

            $table->foreign('quiz_attempt_id')->references('id')->on('quiz_attempts');
            $table->foreign('quiz_question_id')->references('id')->on('quiz_questions');
            $table->foreign('quiz_answer_id')->references('id')->on('quiz_answers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainee_answers');
    }
};
