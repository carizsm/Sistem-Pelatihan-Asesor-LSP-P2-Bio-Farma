<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\RealizationStatus;

class Tna extends Model
{
    use HasFactory;

    protected $table = 'tnas';

    protected $casts = [
        'passing_score' => 'integer',
        'period'     => 'integer',
        'batch'     => 'integer',
        'start_date' => 'datetime',
        'end_date'   => 'datetime',
        'realization_status' => RealizationStatus::class
    ];

    protected $fillable = [
        'tna_code',
        'name',
        'method',
        'passing_score',
        'period',
        'batch',
        'start_date',
        'end_date',
        'speaker',
        'spt_file_path',
        'realization_status',
        'reason',
        'goal',
        'before_status',
        'after_status'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function quizQuestions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class);
    }

    /**
     * Mendapatkan status TNA yang sudah di-logic-kan (real-time).
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->realization_status === RealizationStatus::TIDAK_TEREALISASI) {
                    return 'Dibatalkan';
                }

                if (now()->isAfter($this->end_date)) {
                    return 'Selesai';
                }

                if (now()->isBefore($this->start_date)) {
                    return 'Dijadwalkan';
                }

                return 'Sedang Berlangsung';
            }
        );
    }
}