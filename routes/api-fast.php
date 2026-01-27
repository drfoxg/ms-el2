<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiWarehouseController;

Route::get('/products', [ApiWarehouseController::class, 'index']);
