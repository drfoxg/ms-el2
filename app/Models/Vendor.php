<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    //protected $with = ['vendor', 'vendor.parent'];

    /**
     * @return HasMany<\App\Models\Warehouse>
     */
    public function vendors(): HasMany
    {
        return $this->hasMany(Warehouse::class);
    }
}
