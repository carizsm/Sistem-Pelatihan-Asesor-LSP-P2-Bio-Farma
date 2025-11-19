<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Enums\RegistrationStatus;

class Registration extends Model
{
    use HasFactory;

    protected $table = 'registrations';

    protected $fillable = [
        'user_id',
        'tna_id',
        'regist_date',
        'status',
    ];

    protected $casts = [
        'regist_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tna(): BelongsTo
    {
        return $this->belongsTo(Tna::class);
    }

    public function presence(): HasOne
    {
        return $this->hasOne(Presence::class);
    }

    public function feedbackResult(): HasOne
    {
        return $this->hasOne(FeedbackResult::class);
    }

    public function quizAttempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }
}