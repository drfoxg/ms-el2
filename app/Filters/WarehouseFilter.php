<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use App\Http\Requests\Api\WarehouseRequest;

class WarehouseFilter
{
    protected Builder $query;
    protected WarehouseRequest $request;

    public function __construct(WarehouseRequest $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $query): Builder
    {
        $this->query = $query;

        $this->filterByName()
             ->filterByPrice()
             ->filterByCategory()
             ->filterByStock()
             ->filterByRating()
             ->sort();

        return $this->query;
    }

    protected function filterByName(): self
    {
        if ($this->request->filled('q')) {

            $this->query->where(function ($q) {
                $q->whereFullText(['name', 'comment'], $this->request->q)
                  ->orWhere('name', 'like', '%' . $this->request->q . '%');
            });

        }
        return $this;
    }

    protected function filterByPrice(): self
    {
        if ($this->request->filled('price_from')) {
            $this->query->where('price', '>=', $this->request->price_from);
        }
        if ($this->request->filled('price_to')) {
            $this->query->where('price', '<=', $this->request->price_to);
        }
        return $this;
    }

    protected function filterByCategory(): self
    {
        if ($this->request->filled('category_id')) {
            $this->query->where('category_id', $this->request->category_id);
        }
        return $this;
    }

    protected function filterByStock(): self
    {
        if ($this->request->filled('in_stock')) {
            $this->query->where('in_stock', filter_var($this->request->in_stock, FILTER_VALIDATE_BOOLEAN));
        }
        return $this;
    }

    protected function filterByRating(): self
    {
        if ($this->request->filled('rating_from')) {
            $this->query->where('rating', '>=', $this->request->rating_from);
        }
        return $this;
    }

    protected function sort(): self
    {
        match ($this->request->get('sort')) {
            'price_asc'   => $this->query->orderBy('price'),
            'price_desc'  => $this->query->orderByDesc('price'),
            'rating_desc' => $this->query->orderByDesc('rating'),
            'newest'      => $this->query->orderByDesc('created_at'),
            default       => $this->query->orderBy('id'),
        };

        return $this;
    }
}
