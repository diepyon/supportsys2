<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InquiryController;

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

Route::post('/inquiries/create', [InquiryController::class, 'create']);
Route::post('/inquiries/edit', [InquiryController::class, 'edit']);
Route::post('/inquiries/inhert', [InquiryController::class, 'inhert']);
Route::get('/inquiries/archive', [InquiryController::class, 'show']);
Route::get('/inquiries/{id}', [InquiryController::class, 'single']);
Route::post('/inquiries/delete', [InquiryController::class, 'delete']);
Route::post('/inquiries/reborn', [InquiryController::class, 'reborn']);

Route::post('/types/create', [InquiryController::class, 'create']);
