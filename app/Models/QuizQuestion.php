<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $table = 'quiz_questions';

    protected $casts = [
        'question_number' => 'integer'
    ];

    protected $fillable = [
        'question',
        'question_number'
    ];

    public function tna(): BelongsTo
    {
        return $this->belongsTo(Tna::class, 'tna_id');
    }

    public function quizAnswers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class);
    }

    public function traineeAnswers(): HasMany
    {
        return $this->hasMany(TraineeAnswer::class);
    }
}
