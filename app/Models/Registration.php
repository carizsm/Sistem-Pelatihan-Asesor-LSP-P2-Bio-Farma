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

    protected $casts = [
        'regist_date' => 'date',
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