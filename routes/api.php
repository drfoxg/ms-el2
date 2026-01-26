<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiWarehouseController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/products', [ApiWarehouseController::class, 'index']);



// проверка работы за обратным прокси
// Route::get('/debug', function (Request $request) {
//     return response()->json([
//         'ip' => request()->ip(),
//         'isFromTrustedProxy' => $request->isFromTrustedProxy(),
//         'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'],
//         'secure' => $request->secure(), // true если Laravel видит HTTPS
//         'scheme' => $request->getScheme(), // http / https
//         'url_current' => url()->current(), // текущий URL
//         'url_full' => url()->full(), // полный URL с query
//         'headers' => [
//             'x-forwarded-proto' => $request->header('X-Forwarded-Proto'),
//             'x-forwarded-scheme' => $request->header('X-Forwarded-Scheme'),
//             'host' => $request->header('Host'),
//             'x-forwarded-for' => $request->header('X-Forwarded-For'),
//         ],
//         // 'server' => $request->server(), // всё что пришло от Nginx/PHP-FPM
//     ]);
// });
