<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\Category;
use App\Models\Vendor;
use App\Models\Manufacturer;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports;
use App\Imports;
use Illuminate\Support\Collection;
use App\Http\Requests\WarehouseRequest;

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
        $categories = $this->getCategoriesWithDepth();
        $flatCategories = $this->flattenCategories($categories);

        $categories = Category::all();
        $manufacturers = Manufacturer::all();
        $vendors = Vendor::all();

        return view('warehouse.create', [
            'warehouse' => new Warehouse(),
            'categories' => $flatCategories,
            'manufacturers' => $manufacturers,
            'vendors' => $vendors,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(WarehouseRequest $request)
    {
        // dd(
        //     $request->all(),
        //     $request->validated()
        // );

        $data = $request->validated();

        //Warehouse::create($request->except('_token'));
        Warehouse::create($data);

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

        $categories = $this->getCategoriesWithDepth();
        $flatCategories = $this->flattenCategories($categories);

        $manufacturers = Manufacturer::all();
        $vendors = Vendor::all();

        return view('warehouse.edit', [
            'warehouse' => $warehouse,
            'categories' => $flatCategories,
            'manufacturers' => $manufacturers,
            'vendors' => $vendors,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WarehouseRequest $request, Warehouse $warehouse)
    {
        $data = $request->validated();

        $warehouse->update($data);

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

        (new Warehouse())->query()->forceDelete();

        return redirect()->route('warehouse.index')->withSuccess('Все позиции были удалены успешно.');
    }

    /**
     * Скачивает шаблон для Excel
     * @param Request $request
     * @return type
     */
    public function tmpdownload(Request $request)
    {
        $fileName = 'import_template.xlsx';

        return Excel::download(new Exports\CommonExport(new Warehouse()), $fileName);
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
            'excel' => 'required|file|mimetypes:application/zip,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel|max:100000',
        ];

        $data = $request->validate($inputFields);

        // $imports = collect([]);
        // $result = Excel::import(new Imports\WarehouseImport($imports), $data['excel']);

        // foreach (array_chunk($imports->toArray(), Imports\WarehouseImport::getChunkSize() / 10) as $chunk) {
        //     $warehouse->insert($chunk);
        // }

        // return redirect()->route('warehouse.index');



        try {
            Excel::import(new Imports\WarehouseImport(), $data['excel']);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {

            $failures = $e->failures();

            $errors = [];
            foreach ($failures as $failure) {
                foreach ($failure->errors() as $error) {
                    $errors[] = "Строка {$failure->row()}: {$error}";
                }
            }

            return back()->withErrors($errors, 'import');
        }


        return redirect()->route('warehouse.index')->with('success', 'Импорт завершён!');

    }

    public function getCategoriesWithDepth(): Collection
    {
        $categories = Category::all()->keyBy('id');

        $tree = collect();
        foreach ($categories as $category) {
            $category->children = collect();
            $category->depth = 0;

            if ($category->parent_id && isset($categories[$category->parent_id])) {
                $parent = $categories[$category->parent_id];
                $parent->children->push($category);
            } else {
                $tree->push($category);
            }
        }

        return $tree;
    }


    public function flattenCategories(Collection $tree): Collection
    {
        $flat = collect();
        $stack = $tree->reverse()->all();

        while (!empty($stack)) {
            $cat = array_pop($stack);
            $flat->push($cat);

            if ($cat->children->isNotEmpty()) {
                foreach ($cat->children->reverse() as $child) {
                    $child->depth = $cat->depth + 1;
                    $stack[] = $child;
                }
            }
        }

        return $flat;
    }

}
