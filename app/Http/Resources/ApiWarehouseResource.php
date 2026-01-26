<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApiWarehouseResource extends JsonResource
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

            'category' => [
                'id' => $this->category_id,
                'name' => optional($this->category)->name,
            ],

            'manufacturer' => $this->manufacturer
                ? [
                    'id' => $this->manufacturer_id,
                    'name' => $this->manufacturer->name,
                ]
                : null,

            'vendor' => $this->vendor
                ? [
                    'id' => $this->vendor_id,
                    'name' => $this->vendor->name,
                ]
                : null,

            'part_number' => $this->part_number,
            'name' => $this->name,

            'price' => (float) $this->price,
            'rating' => $this->rating,
            'in_stock' => $this->in_stock,
            'stock_quantity' => $this->stock_quantity,

            'comment' => $this->comment,

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}
