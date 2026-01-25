<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $with = ['vendor', 'manufacturer'];

    // поля, которые не передаются в json
    protected $hidden = ['created_at', 'updated_at'];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function manufacturer()
    {
        return $this->belongsTo(Manufacturer::class);
    }
}
