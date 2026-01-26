<?php

// namespace App\Imports;

// use App\Models;
// use App\Exports;
// use App\Helpers;

// use Illuminate\Database\Eloquent\Model;
// use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Collection;
// use Illuminate\Support\Facades\Validator;
// use Illuminate\Http\UploadedFile;
// use Maatwebsite\Excel\Concerns\ToCollection;
// use Maatwebsite\Excel\Concerns\ToModel;
// use Maatwebsite\Excel\Concerns\WithValidation;
// use Maatwebsite\Excel\Concerns\WithHeadingRow;


// /**
//  * Description of WarehouseImport
//  *
//  * @author a.borisov
//  */
// class WarehouseImport implements ToCollection, WithHeadingRow
// {
//     protected const CHUNKSIZE = 1000;

//     private Collection $grouped;

//     public function __construct(Collection &$grouped)
//     {
//         $this->grouped = &$grouped;
//     }

//     /**
//      *
//      * @param Collection $rows
//      * @return Collection $grouped
//      */
//     public function collection(Collection $rows)
//     {
//         $this->grouped = $this->validateImport($rows);

//         //dump($this->grouped);

//         $result = collect(['Коммент 123', 'Производитель 123', 'MS232', '32311', 'Поставщик 123']);

//         return $result;
//     }

//     private function validateImport(Collection $rows)
//     {
//         $rules['*.category_id'] = 'required|integer|min:0|exists:App\\Models\\Category,id';
//         $rules['*.part_number'] = 'required|string|max:255';
//         $rules['*.name'] = 'required|string|max:255';
//         $rules['*.price'] = 'nullable|integer|min:0';
//         $rules['*.comment'] = 'required|string|max:300';
//         $rules['*.in_stock'] = 'boolean';
//         $rules['*.rating'] = 'nullable|numeric|min:0|max:5';
//         $rules['*.stock_quantity'] = 'required|integer|min:0';
//         $rules['*.vendor_id'] = 'required|integer|min:0|exists:App\\Models\\Vendor,id';
//         $rules['*.manufacturer_id'] = 'required|integer|min:0|exists:App\\Models\\Manufacturer,id';

//         $rowsAsArray = $rows->toArray();
//         $rowsAsArrayId = [];

//         foreach ($rowsAsArray as $i => $row) {
//             foreach ($row as $j => $value) {
//                 $columnName = $this->renameColumn($j);
//                 $rowsAsArrayId[$i][$columnName] = (string)$value;
//             }
//         }

//         $validated = Validator::make($rowsAsArrayId, $rules)->validate();

//         $result = collect($validated);

//         return $result;
//     }

//     public static function getChunkSize(): int
//     {
//         return static::CHUNKSIZE;
//     }

//     private function renameColumn($name)
//     {
//         $newName = $name;
//         /*
//         if ($name == 'vendor_name') {
//             $newName = 'vendor_id';
//         }

//         if ($name == 'manufacturer_name') {
//             $newName = 'manufacturer_id';
//         }
//         */

//         return $newName;
//     }
// }

namespace App\Imports;

use App\Models\Warehouse;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\Importable;

class WarehouseImport implements ToCollection, WithHeadingRow, WithValidation, WithChunkReading
{
    use Importable;
    use SkipsFailures;

    protected $batchSize = 1000;

    public function collection(Collection $rows)
    {
        $batch = [];

        foreach ($rows as $row) {
            // Нормализуем данные
            $price = isset($row['price']) ? str_replace(',', '.', $row['price']) : null;
            $rating = isset($row['rating']) ? str_replace(',', '.', $row['rating']) : null;

            $in_stock = 0;
            if (isset($row['in_stock'])) {
                $val = strtolower(trim($row['in_stock']));
                if (in_array($val, ['1', 'yes', 'да', 'true'])) {
                    $in_stock = 1;
                }
            }

            $batch[] = [
                'category_id'      => $row['category_id'],
                'part_number'      => $row['part_number'],
                'name'             => $row['name'],
                'price'            => $price,
                'in_stock'         => $in_stock,
                'rating'           => $rating,
                'manufacturer_id'  => $row['manufacturer_id'],
                'vendor_id'        => $row['vendor_id'],
                'stock_quantity'   => $row['stock_quantity'],
                'comment'          => $row['comment'],
                'created_at'       => now(),
                'updated_at'       => now(),
            ];

            if (count($batch) >= $this->batchSize) {
                Warehouse::insert($batch);
                $batch = [];
            }
        }

        // Вставляем оставшиеся
        if (count($batch) > 0) {
            Warehouse::insert($batch);
        }
    }

    public function rules(): array
    {
        return [
            '*.category_id'     => ['required', 'integer', Rule::exists('categories', 'id')],
            '*.part_number'     => 'required|string|max:255',
            '*.name'            => 'required|string|max:255',
            '*.price'           => 'nullable|numeric|min:0',
            '*.in_stock'        => 'nullable|boolean',
            '*.rating'          => 'nullable|numeric|min:0|max:5',
            '*.manufacturer_id' => ['required','integer', Rule::exists('manufacturers', 'id')],
            '*.vendor_id'       => ['required','integer', Rule::exists('vendors', 'id')],
            '*.stock_quantity'  => 'required|integer|min:0',
            '*.comment'         => 'nullable|string|max:300',
        ];
    }

    public function chunkSize(): int
    {
        return $this->batchSize; // 500 строк за раз
    }

    public function customValidationMessages()
    {
        return [
            '*.category_id.required' => 'Категория обязательна',
            '*.part_number.required' => 'Парт номер обязателен',
            '*.name.required'        => 'Название обязательно',
            '*.price.numeric'        => 'Цена должна быть числом',
            '*.in_stock.boolean'     => 'in_stock должно быть 0 или 1',
            '*.rating.numeric'       => 'Рейтинг должен быть числом',
            '*.manufacturer_id.required' => 'Производитель обязателен',
            '*.vendor_id.required'       => 'Поставщик обязателен',
            '*.stock_quantity.required'  => 'Количество обязательно',
            '*.comment.required'         => 'Комментарий обязателен',
        ];
    }
}
