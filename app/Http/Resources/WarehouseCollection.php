<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WarehouseCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
        //dd($this->data->id);
        /*
        $collection = $this->collection;

        dd($collection);

        return [
            'id' => $this->id,
            'part_number' => $this->part_number,
            'stock_quantity' => $this->stock_quantity,
            'vendor_name' => $this->vendor->name,
            'comment' => $this->comment,
        ];
        */
    }

    /**
     * Customize the outgoing response for the resource.
     *
     * @param  \Illuminate\Http\Request
     * @param  \Illuminate\Http\Response
     * @return void
     */
    public function withResponse($request, $response)
    {
        $response->header('Charset', 'utf-8');
        $response->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}
