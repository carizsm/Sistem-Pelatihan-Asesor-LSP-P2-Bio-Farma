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
        'type'
    ];

    public function trainee_answers(): HasMany
    {
        return $this->hasMany(TraineeAnswer::class);
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }
}
