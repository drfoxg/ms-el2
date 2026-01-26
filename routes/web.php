<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\ManufacturerController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('infop', function () {
        phpinfo();
    })->name('infop');
});


Route::get('/clear-cache', function () {
    $clearCache = Artisan::call('cache:clear');
    $configCache = Artisan::call('config:cache');
})->middleware(['auth', 'verified']);

require __DIR__ . '/auth.php';

Route::get('/test', function () {
    return view('test');
});

// Сменить язык
Route::get('/setlocale/{locale}', [LocaleController::class, 'setLocale'])->name('setlocale');

Route::controller(UserController::class)->group(function () {
    Route::get('dashboard', 'index')->name('dashboard.index');
    Route::get('dashboard/create', 'create')->name('dashboard.create')->middleware('can:create,App\Models\User');
    Route::get('dashboard/{user}/edit', 'edit')->name('dashboard.edit')->middleware('can:update,user');
    Route::put('dashboard/{user}', 'update')->name('dashboard.update')->middleware('can:update,user');
    Route::post('dashboard', 'store')->name('dashboard.store')->middleware('can:create,App\Models\User');
    Route::delete('dashboard/{user}', 'destroy')->name('dashboard.destroy')->middleware('can:delete,user');
    ;
})->middleware(['auth', 'verified']);

Route::resource('vendors', VendorController::class)->names('vendor');
Route::resource('manufacturer', ManufacturerController::class);//->middleware('guest');
Route::controller(WarehouseController::class)->group(function () {

    Route::get('removeall', 'removeall')->name('warehouse.removeall');
    Route::get('tmpdownload', 'tmpdownload')->name('warehouse.tmpdownload');
    Route::get('import', 'createImport')->name('warehouse.createimport');
    Route::post('import', 'import')->name('warehouse.import');

    Route::get('/', 'index')->name('warehouse.index');
    Route::get('create', 'create')->name('warehouse.create');
    Route::post('/', 'store')->name('warehouse.store');
    Route::get('{warehouse}', 'show')->name('warehouse.show');
    Route::get('{warehouse}/edit', 'edit')->name('warehouse.edit');
    Route::put('{warehouse}', 'update')->name('warehouse.update');
    Route::patch('{warehouse}', 'update')->name('warehouse.update');
    Route::delete('{warehouse}', 'destroy')->name('warehouse.destroy');

});//->middleware('guest');
