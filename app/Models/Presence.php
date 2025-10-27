<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    use HasFactory;

    protected $table = 'presences';

    protected $casts = [
        'clock_in' => 'datetime',
        'clock_out' => 'datetime'
    ];

    protected $fillable = [
        'clock_in',
        'clock_out'
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(Registration::class, 'registration_id');
    }
}
