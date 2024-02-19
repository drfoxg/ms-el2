<?php

namespace App\Exports;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

use Maatwebsite\Excel\Concerns\FromCollection;

use Schema;

class CommonExport implements FromCollection
{
    private Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return new Collection([$this->getExcelColumnListing()]);
    }

    public function getExcelColumnListing() : array
    {

        $tableName = $this->model->getTable();
        $columns = Schema::getColumnListing($tableName);

        //dump($columns);

        $subsetColumns = [];
        $counter = 0;
        foreach ($columns as $value) {
            if ($value !== 'id'
                && $value !== 'created_at'
                && $value !== 'updated_at') {

                $subsetColumns[$counter] = $this->renameColumn($value);

                $counter++;
            }
        }

        //dd($subsetColumns);

        return $subsetColumns;
    }

    private function renameColumn($name)
    {
        $newName = $name;
        /*
        if ($name === 'vendor_id') {
            $newName = 'vendor_name';
        }

        if ($name === 'manufacturer_id') {
            $newName = 'manufacturer_name';
        }
        */

        return $newName;
    }
}
