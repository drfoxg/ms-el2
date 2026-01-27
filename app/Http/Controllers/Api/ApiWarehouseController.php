<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiWarehouseResource;
use App\Models\Warehouse;
use App\Filters\WarehouseFilter;
use App\Http\Requests\Api\WarehouseRequest;
use App\Http\Resources\ApiResponseResource;
use App\Traits\ApiResponseTrait;

class ApiWarehouseController extends Controller
{
    use ApiResponseTrait;

    private const PAGINATE = 15;

    public function index(WarehouseRequest $request): ApiResponseResource
    {
        $query = Warehouse::with(['category', 'manufacturer', 'vendor']);

        // Применяем фильтр
        $query = (new WarehouseFilter($request))->apply($query);

        $products = $query->paginate($request->integer('per_page', self::PAGINATE))
                          ->withQueryString();

        return $this->ok(
            ApiWarehouseResource::collection($products),
            'Товары успешно получены'
        );
    }
}
