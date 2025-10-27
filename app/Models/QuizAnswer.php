<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class QuizAnswer extends Model
{
    use HasFactory;

    protected $table = 'quiz_answers';

    protected $casts = [
        'answer_order' => 'integer',
        'is_correct' => 'boolean'
    ];

    protected $fillable = [
        'answer',
        'answer_order',
        'is_correct',
        'quiz_question_id',
    ];

    public function quizQuestion(): BelongsTo
    {
        return $this->belongsTo(QuizQuestion::class, 'quiz_question_id');
    }

    public function traineeAnswers(): HasMany
    {
        return $this->hasMany(TraineeAnswer::class);
    }
}
