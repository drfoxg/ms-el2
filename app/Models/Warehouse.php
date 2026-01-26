<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Warehouse extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $with = ['vendor', 'manufacturer'];

    protected $casts = [
        'price'     => 'decimal:2',
        'in_stock'  => 'boolean',
        'rating'    => 'float',
    ];

    // поля, которые не передаются в json
    protected $hidden = ['created_at', 'updated_at'];

    public function getPriceForDisplayAttribute(): string
    {
        return number_format($this->price, 2, ',', '');
    }

    public function getRatingForDisplayAttribute(): string
    {
        return number_format($this->rating, 2, ',', '');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function manufacturer(): BelongsTo
    {
        return $this->belongsTo(Manufacturer::class);
    }
}
