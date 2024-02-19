<?php

namespace App\Http\Controllers;

use App\Models\Manufacturer;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class ManufacturerController extends Controller
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
        $manufacturers = Manufacturer::orderBy('id', 'asc')->get();//->paginate(10);

        return view('manufacturer.index', [
            'manufacturers' => $manufacturers,
            'tePaginatorActive' => false,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('manufacturer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        try {
            Manufacturer::create($request->except('_token'));
        } catch (QueryException $ex) {
            return redirect()->route('manufacturer.index')->withFailure('Данный производитель уже внесен.');
        }

        return redirect()->route('manufacturer.index')->withSuccess('Производитель был создан успешно.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Manufacturer $manufacturer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Manufacturer $manufacturer)
    {
        return view('manufacturer.edit', [
            'manufacturer' => $manufacturer,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Manufacturer $manufacturer)
    {
        $request->validate([
            'name' => 'required',
            'brand' => 'required',

        ]);

        $manufacturer->update($request->all());

        return redirect()->route('manufacturer.index')->withSuccess('Данные Производителя были обновлены.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Manufacturer $manufacturer)
    {
        try {
            $manufacturer->delete();
        } catch (QueryException $ex) {
            return redirect()->route('manufacturer.index')->withFailure('Позиция не была удалена из-за ограничений целостности.');
        }

        return redirect()->route('manufacturer.index')->withSuccess('Производитель был удален успешно.');
    }
}
