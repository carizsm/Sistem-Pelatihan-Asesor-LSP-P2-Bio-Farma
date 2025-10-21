<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'units';

    protected $fillable = ['unit_name'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
