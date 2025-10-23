<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class TraineeAnswer extends Model
{
    protected $table = 'trainee_answers';

    protected $fillable = [
        'quiz_question_id',
        'quiz_answer_id',
        'quiz_attempt_id',
    ];

    public function quizQuestion(): BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class, 'quiz_question_id');
    }

    public function quizAnswer(): BelongsTo
    {
        return $this->belongsTo(QuizAnswer::class, 'quiz_answer_id');
    }

    public function quizAttempt(): BelongsTo
    {
        return $this->belongsTo(QuizAttempt::class, 'quiz_attempt_id');
    }
}
