<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Http\Resources\WarehouseCollection;
use App\Http\Resources\WarehouseResource;

class WarehouseController extends Controller
{
    public function index()
    {
        return WarehouseResource::collection(Warehouse::all());
        //return new WarehouseCollection(Warehouse::all());
    }

    public function test(Warehouse $warehouse)
    {
        return WarehouseResource::collection(Warehouse::all());
    }
}
