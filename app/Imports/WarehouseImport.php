<?php

namespace App\Imports;

use App\Models;
use App\Exports;
use App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


/**
 * Description of WarehouseImport
 *
 * @author a.borisov
 */
class WarehouseImport implements ToCollection, WithHeadingRow
{
    protected const CHUNKSIZE = 1000;

    private Collection $grouped;

    public function __construct(Collection &$grouped)
    {
        $this->grouped = &$grouped;
    }

    /**
     *
     * @param Collection $rows
     * @return Collection $grouped
     */
    public function collection(Collection $rows)
    {
        $this->grouped = $this->validateImport($rows);

        //dump($this->grouped);

        $result = collect(['Коммент 123', 'Производитель 123', 'MS232', '32311', 'Поставщик 123']);

        return $result;
    }

    private function validateImport(Collection $rows)
    {
        $rules['*.part_number'] = 'required|string|max:255';
        $rules['*.comment'] = 'required|string|max:300';
        $rules['*.stock_quantity'] = 'required|integer|min:0';
        $rules['*.vendor_id'] = 'required|integer|min:0|exists:App\\Models\\Vendor,id';
        $rules['*.manufacturer_id'] = 'required|integer|min:0|exists:App\\Models\\Manufacturer,id';

        $rowsAsArray = $rows->toArray();
        $rowsAsArrayId = [];

        foreach ($rowsAsArray as $i => $row) {
            foreach ($row as $j => $value) {
                $columnName = $this->renameColumn($j);
                $rowsAsArrayId[$i][$columnName] = (string)$value;
            }
        }

        $validated = Validator::make($rowsAsArrayId, $rules)->validate();

        $result = collect($validated);

        return $result;
    }

    public static function getChunkSize(): int
    {
        return static::CHUNKSIZE;
    }

    private function renameColumn($name)
    {
        $newName = $name;
        /*
        if ($name == 'vendor_name') {
            $newName = 'vendor_id';
        }

        if ($name == 'manufacturer_name') {
            $newName = 'manufacturer_id';
        }
        */

        return $newName;
    }
}
