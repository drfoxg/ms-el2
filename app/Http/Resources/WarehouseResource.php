<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WarehouseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'part_number' => $this->part_number,
            'stock_quantity' => $this->stock_quantity,
            'manufacturer_name' => $this->manufacturer->name,
            'comment' => $this->comment,
        ];

        //return parent::toArray($request);
    }
}
