<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/*
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

//* Rutas de categorias de productos
Route::get('/categories',[CategoryController::class,"index"]);
Route::get('/categorie/{id}',[CategoryController::class,"show"]);
Route::post('/create-category',[CategoryController::class,'store']);
Route::put('/updated-category/{id}',[CategoryController::class,"update"]);
Route::delete('/remove-category/{id}',[CategoryController::class,"destroy"]);

//* Rutas de proveedores de productos
Route::get('/providers',[SupplierController::class,"index"]);
Route::get('/provider/{id}',[SupplierController::class,"show"]);
Route::post('/create-provider',[SupplierController::class,"store"]);
Route::put('/updated-provider/{id}',[SupplierController::class,"update"]);
Route::delete('/remove-provider/{id}',[SupplierController::class,"destroy"]);