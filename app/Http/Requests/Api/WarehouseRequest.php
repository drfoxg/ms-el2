<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Traits\NormalizeDecimalInput;

class WarehouseRequest extends FormRequest
{
    use NormalizeDecimalInput;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'min:2', 'max:255'],

            'price_from' => ['nullable', 'numeric', 'min:0'],
            'price_to'   => ['nullable', 'numeric', 'min:0'],

            'category_id' => ['nullable', 'integer', 'exists:categories,id'],

            'in_stock' => ['nullable', 'boolean'],

            'rating_from' => ['nullable', 'numeric', 'min:0', 'max:5'],

            'sort' => [
                'nullable',
                Rule::in([
                    'price_asc',
                    'price_desc',
                    'rating_desc',
                    'newest',
                ]),
            ],

            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function withValidator($validator)
    {
        // Правило gte:price_from применяется только если price_from задан
        $validator->sometimes('price_to', 'gte:price_from', function ($input) {
            return isset($input->price_from);
        });
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('in_stock')) {
            $this->merge([
                'in_stock' => filter_var($this->in_stock, FILTER_VALIDATE_BOOLEAN),
            ]);
        }

        $this->normalizeDecimal('price_from');
        $this->normalizeDecimal('price_to');
        $this->normalizeDecimal('rating_from');
    }
}
