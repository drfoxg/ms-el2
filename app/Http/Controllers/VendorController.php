<?php

namespace App\Http\Controllers;

use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class VendorController extends Controller
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
        $vendors = Vendor::orderBy('id', 'asc')->paginate(10);

        return view('vendor.index', [
            'vendors' => $vendors,
            'tePaginatorActive' => true,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vendor.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        Vendor::create($request->except('_token'));

        return redirect()->route('vendor.index')->withSuccess('Поставщик был создан успешно.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vendor $vendor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vendor $vendor)
    {
        return view('vendor.edit', [
            'vendor' => $vendor,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vendor $vendor)
    {
        $request->validate([
            'name' => 'required',

        ]);

        $vendor->update($request->all());

        return redirect()->route('vendor.index')->withSuccess('Данные Поставщика были обновлены.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vendor $vendor)
    {
        try {
            $vendor->delete();
        } catch (QueryException $ex) {
            return redirect()->route('vendor.index')->withFailure('Позиция не была удалена из-за ограничений целостности.');
        }

        return redirect()->route('vendor.index')->withSuccess('Поставщик был удален успешно.');
    }
}
