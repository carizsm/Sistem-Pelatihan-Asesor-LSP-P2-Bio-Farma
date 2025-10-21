<?php

namespace App\Models;

use App\Enums\RegistrationStatus;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $table = 'registrations';

    protected $casts = [
        'regist_date' => 'date',
        'status' => RegistrationStatus::class
    ];

    protected $fillable = [
        'regist_date'
    ];

    public function tna(): BelongsTo
    {
        return $this->belongsTo(Tna::class, 'tna_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function presence(): HasOne
    {
        return $this->hasOne(Presence::class);
    }

    public function feedbackResult(): HasOne
    {
        return $this->hasOne(FeedbackResult::class);
    }
}