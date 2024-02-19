<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    //protected $with = ['vendor', 'vendor.parent'];

    public function vendors() {
        return $this->hasMany(Warehouse::class);
    }
}
