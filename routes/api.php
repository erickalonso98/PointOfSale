<?php

use App\Http\Controllers\BoxController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProductController;
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

//* Rutas de Usuarios

//* Rutas de Productos
Route::get('/products',[ProductController::class,"index"]);
Route::get('/product/{id}',[ProductController::class,"show"]);
Route::post('/create-product/',[ProductController::class,"store"]);
Route::put('/updated-product/{id}',[ProductController::class,"update"]);
Route::delete('/removed-product/{id}',[ProductController::class,"destroy"]);

//*Rutas de ventas de productos

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

//* Rutas de compañia de punto de venta
Route::get('/companies/',[CompanyController::class,"index"]);
Route::get('/company/{id}',[CompanyController::class,"show"]);
Route::post('/create-company',[CompanyController::class,"store"]);
Route::put('/updated-company/{id}',[CompanyController::class,"update"]);
Route::delete('/remove-company/{id}',[CompanyController::class,"destroy"]);

//* Rutas de Clientes
Route::get('/clients',[ClientController::class,"index"]);
Route::get('/client/{id}',[ClientController::class,"show"]);
Route::post('/create-client/',[ClientController::class,"store"]);
Route::put('/updated-client/{id}',[ClientController::class,"update"]);
Route::delete('/deleted-client/{id}',[ClientController::class,"destroy"]);

//* Ruta de Inicio de Caja de venta
Route::get('/boxes/',[BoxController::class,"index"]);
Route::get('/box/{id}',[BoxController::class,"show"]);
Route::post('/create-box',[BoxController::class,"store"]);
Route::put('/updated-box/{id}',[BoxController::class,"update"]);
Route::delete('/deleted-box/{id}',[BoxController::class,"destroy"]);