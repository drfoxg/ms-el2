<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Manufacturer extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function manufacturers(): HasMany
    {
        return $this->hasMany(Warehouse::class);
    }
}
