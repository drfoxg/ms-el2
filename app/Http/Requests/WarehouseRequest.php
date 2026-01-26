<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
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
            'category_id' => 'required|integer|exists:categories,id',
            'part_number' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'in_stock' => 'boolean',
            'rating' => 'nullable|numeric|min:0|max:5',
            'manufacturer_id' => 'nullable|integer|exists:manufacturers,id',
            'vendor_id' => 'nullable|integer|exists:vendors,id',
            'stock_quantity' => 'required|integer|min:0',
            'comment' => 'nullable|string|max:300',
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Категория обязательна',
            'category_id.exists' => 'Выбранная категория не существует',
            'part_number.required' => 'Поле part_number обязательно',
            'name.required' => 'Название компонента обязательно',
            'price.numeric' => 'Цена должна быть числом',
            'rating.max' => 'Рейтинг не может быть больше 5',
            'manufacturer_id.exists' => 'Производитель не найден',
            'vendor_id.exists' => 'Поставщик не найден',
            'stock_quantity.required' => 'Количество обязательно',
            'stock_quantity.min' => 'Количество не может быть отрицательным',
            'comment.required' => 'Комментарий обязателен',
        ];
    }

    protected function prepareForValidation(): void
    {

        $this->normalizeDecimal('price');
        $this->normalizeDecimal('rating');
    }
}
