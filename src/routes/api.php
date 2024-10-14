<?php

use App\Features\Shipment\Presentation\Controllers\ShipmentController;
use App\Features\User\Presentation\Controllers\LoginController;
use App\Features\User\Presentation\Controllers\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [RegisterController::class, 'c']); // ユーザー登録
Route::post('/login', [LoginController::class, 'login']); // ログイン


Route::get('/shipments', [ShipmentController::class, 'index'])->middleware('auth:sanctum');
Route::post('/shipments', [ShipmentController::class, 'store'])->middleware('auth:sanctum');
Route::get('/shipments/{id}', [ShipmentController::class, 'show'])->middleware('auth:sanctum');
Route::put('/shipments/{id}', [ShipmentController::class, 'update'])->middleware('auth:sanctum');
