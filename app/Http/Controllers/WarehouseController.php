<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\Vendor;
use App\Models\Manufacturer;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

use Maatwebsite\Excel\Facades\Excel;

use App\Exports;
use App\Imports;

class WarehouseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified'])->except(['index']);
        //$this->authorizeResource('post');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $warehouses = Warehouse::orderBy('id', 'asc')->paginate(10);

        //dump($warehouses);

        return view('warehouse.index', [
            'warehouses' => $warehouses,
            'tePaginatorActive' => true,
        ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $manufacturers = Manufacturer::all();
        $vendors = Vendor::all();

        return view('warehouse.create', [
            'manufacturers' => $manufacturers,
            'vendors' => $vendors,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'part_number' => 'required',
            'manufacturer_id' => 'required|integer|min:0',
            'vendor_id' => 'required|integer|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'comment' => 'required',
        ]);

        //dd($request->input());

        Warehouse::create($request->except('_token'));

        return redirect()->route('warehouse.index')->withSuccess('Позиция была создан успешно.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Warehouse $warehouse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Warehouse $warehouse)
    {
        $manufacturers = Manufacturer::all();
        $vendors = Vendor::all();

        return view('warehouse.edit', [
            'warehouse' => $warehouse,
            'manufacturers' => $manufacturers,
            'vendors' => $vendors,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'part_number' => 'required',
            'manufacturer_id' => 'required|integer|min:0',
            'vendor_id' => 'required|integer|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'comment' => 'required',
        ]);


        $warehouse->update($request->all());

        return redirect()->route('warehouse.index')->withSuccess('Данные Позиции были обновлены.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Warehouse $warehouse)
    {
        try {
            $warehouse->delete();
        } catch (QueryException $ex) {
            return redirect()->route('warehouse.index')->withFailure('Позиция не была удалена из-за ограничений целостности.');
        }

        return redirect()->route('warehouse.index')->withSuccess('Позиция была удалена успешно.');
    }

    public function removeall()
    {
        \DB::table('warehouses')->truncate();

        (new Warehouse)->query()->forceDelete();

        return redirect()->route('warehouse.index')->withSuccess('Все позиции были удалены успешно.');
    }

    /**
     * Скачивает шаблон для Excel
     * @param Request $request
     * @return type
     */
    public function tmpdownload(Request $request) {
        $fileName = 'import_template.xlsx';

        return Excel::download(new Exports\CommonExport(new Warehouse), $fileName);
    }

    /**
     * Show the form for creating a new import resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createImport(Request $request)
    {
        $warehouses = 'Какой-то склад';

        return view('warehouse.import', [
            'routeName' => 'warehouse.' . $request->path(),
            'routeBack' => 'warehouse.index',
            'warehouses' => $warehouses,
        ]);
    }

    /**
     * Store a newly created resources in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function import(Warehouse $warehouse, Request $request)
    {

        $inputFields = [
            'excel' => 'required|file|mimetypes:application/zip,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel|max:50000',
            //'excel' => 'string',
        ];

        $data = $request->validate($inputFields);

        $imports = collect([]);
        $result = Excel::import(new Imports\WarehouseImport($imports), $data['excel']);


        //dump($imports);
        //dd($result);

        //foreach (array_chunk($importRows, Imports\WarehouseImport::getChunkSize()/10) as $chunk) {
        foreach (array_chunk($imports->toArray(), Imports\WarehouseImport::getChunkSize()/10) as $chunk) {
            $warehouse->insert($chunk);
        }

        return redirect()->route('warehouse.index');

        //return 1;
    }
}

