<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $table = 'positions';

    protected $fillable = ['position_name'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
