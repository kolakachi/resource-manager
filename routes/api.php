<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/admin/store', [AdminController::class,'store'])->name('admin.store');
Route::get('/admin/list', [AdminController::class,'list'])->name('admin.list');
Route::delete('/admin/delete', [AdminController::class, 'delete'])->name('admin.delete');    
Route::post('/admin/update', [AdminController::class, 'update'])->name('admin.update');    


Route::get('/resources/list', [HomeController::class,'list'])->name('admin.list');
