<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\QuizAttemptType;
use Illuminate\Database\Eloquent\Model;

class QuizAttempt extends Model
{
    use HasFactory;

    protected $table = 'quiz_attempts';

    protected $casts = [
        'score' => 'float',
        'type' => QuizAttemptType::class
    ];

    protected $fillable = [
        'registration_id',
        'type',
        'score'
    ];

    public function traineeAnswers(): HasMany
    {
        return $this->hasMany(TraineeAnswer::class);
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }
}
