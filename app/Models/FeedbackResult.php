<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedbackResult extends Model
{
    use HasFactory;

    protected $table = 'feedback_results';

    protected $casts = [
        'feedback_date' => 'date',
        'score_01' => 'integer',
        'score_02' => 'integer',
        'score_03' => 'integer',
        'score_04' => 'integer',
        'score_05' => 'integer',
        'score_06' => 'integer',
        'score_07' => 'integer',
        'score_08' => 'integer',
        'score_09' => 'integer',
        'score_10' => 'integer',
        'score_11' => 'integer',
        'score_12' => 'integer',
        'score_13' => 'integer',
        'score_14' => 'integer',
        'score_15' => 'integer',
    ];

    protected $fillable = [
        'feedback_date', 'score_01', 'score_02', 'score_03', 
        'score_04', 'score_05', 'score_06', 'score_07',
        'score_08', 'score_09', 'score_10', 'score_11',
        'score_12', 'score_13', 'score_14', 'score_15',
        'registration_id',
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }
}
