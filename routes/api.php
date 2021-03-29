<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionLogController;

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

Route::post('log', [SessionLogController::class, 'store']);
Route::get('overall', [SessionLogController::class, 'overall']);
Route::get('last7DaysDuration', [SessionLogController::class, 'last7DaysDuration']);
Route::get('activeDaysOfMonth', [SessionLogController::class, 'activeDaysOfMonth']);
