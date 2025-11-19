<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TraineeAnswer extends Model
{
    use HasFactory;

    protected $table = 'trainee_answers';

    protected $fillable = [
        'quiz_question_id',
        'quiz_answer_id',
        'quiz_attempt_id',
    ];

    public function quizAttempt(): BelongsTo
    {
        return $this->belongsTo(QuizAttempt::class);
    }

    public function quizQuestion(): BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class);
    }

    public function quizAnswer(): BelongsTo
    {
        return $this->belongsTo(QuizAnswer::class);
    }
}
