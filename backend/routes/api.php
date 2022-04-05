<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\CustomerController;

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


Route::get('/inquiries',  [InquiryController::class, 'index']);//20件記事を表示する

Route::post('/inquiries/create', [InquiryController::class, 'create']);
Route::post('/inquiries/edit', [InquiryController::class, 'edit']);
Route::post('/inquiries/inhert', [InquiryController::class, 'inhert']);

//Route::get('/inquiries/{id}', [InquiryController::class, 'single']);
Route::post('/inquiries/delete', [InquiryController::class, 'delete']);
Route::post('/inquiries/reborn', [InquiryController::class, 'reborn']);

Route::post('/types/create', [TypeController::class, 'create']);
Route::post('/types/delete', [TypeController::class, 'delete']);
Route::post('/types/reborn', [TypeController::class, 'reborn']);
Route::post('/types/update', [TypeController::class, 'update']);
Route::get('/types/archive', [TypeController::class, 'show']);

Route::get('/customers', [CustomerController::class, 'index']);
Route::post('/customers/create', [CustomerController::class, 'create']);
Route::post('/customers/update', [CustomerController::class, 'update']);