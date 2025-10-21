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

            $table->foreignId('quiz_attempt_id')
                ->constrained('quiz_attempts')
                ->onDelete('cascade');
            $table->foreignId('quiz_question_id')
                ->constrained('quiz_questions')
                ->onDelete('cascade');
            $table->foreignId('quiz_answer_id')
                ->constrained('quiz_answers')
                ->onDelete('cascade');

            $table->timestamps();
            $table->unique(['quiz_attempt_id', 'quiz_question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainee_answers');
    }
};