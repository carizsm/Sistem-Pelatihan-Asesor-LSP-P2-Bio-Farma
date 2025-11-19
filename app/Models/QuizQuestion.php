<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $table = 'quiz_questions';

    protected $casts = [
        'question_number' => 'integer'
    ];

    protected $fillable = [
        'question',
        'question_number',
        'tna_id',
    ];

    public function tna(): BelongsTo
    {
        return $this->belongsTo(Tna::class, 'tna_id');
    }

    public function quizAnswers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class);
    }

    // Alias untuk backward compatibility
    public function answers(): HasMany
    {
        return $this->quizAnswers();
    }
}
