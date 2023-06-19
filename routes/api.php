<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MinisterController;
use App\Http\Controllers\MinistryController;
use App\Http\Controllers\PartyController;
use App\Http\Controllers\StatusController;

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

Route::post('/register', [AuthenticationController::class, 'register']);

Route::post('/login', [AuthenticationController::class, 'login']);

Route::get('v1/ministers/status', [StatusController::class, 'index']);
Route::get('v1/ministers/category', [CategoryController::class, 'index']);
Route::get('v1/ministers/party', [PartyController::class, 'index']);
Route::get('v1/ministers/ministry', [MinistryController::class, 'index']);
Route::get('/v1/ministers/active/{active}', [MinisterController::class, 'showActive']);
Route::get('/v1/ministers/party/{party}', [MinisterController::class, 'showParty']);
Route::get('/v1/ministers/category/{category}', [MinisterController::class, 'showCategory']);
Route::get('/v1/ministers/category/{category}/active/{active}', [MinisterController::class, 'showActiveCategory']);
Route::get('/v1/ministers/party/{party}/active/{active}', [MinisterController::class, 'showActiveParty']);
Route::get('/v1/ministers/search/{search}', [MinisterController::class, 'search']);
Route::get('/v1/ministers', [MinisterController::class, 'page']);
Route::get('/v1/ministers/all', [MinisterController::class, 'index']);
Route::get('/v1/minister/{id}', [MinisterController::class, 'show']);
Route::get('/v1/ministers/{year}', [MinisterController::class, 'showYear']);
Route::get('/v1/ministers/start/{start}/end/{end}', [MinisterController::class, 'showBetweenYear']);
Route::get('/v1/minister/{id}', [MinisterController::class, 'show']);
Route::get('/me', [AuthenticationController::class, 'detail']);
Route::post('/v1/minister/create/', [MinisterController::class, 'store']);
Route::post('/v1/minister/status/create/', [StatusController::class, 'store']);
Route::post('/v1/minister/ministry/create/', [MinistryController::class, 'store']);
Route::post('/v1/minister/category/create/', [CategoryController::class, 'store']);
Route::post('/v1/minister/party/create/', [PartyController::class, 'store']);
Route::post('/v1/minister/update/{id}', [MinisterController::class, 'update']);
Route::patch('/v1/minister/category/update/{id}', [CategoryController::class, 'update']);
Route::patch('/v1/minister/status/update/{id}', [StatusController::class, 'update']);
Route::patch('/v1/minister/party/update/{id}', [PartyController::class, 'update']);
Route::patch('/v1/minister/ministry/update/{id}', [MinistryController::class, 'update']);
Route::delete('/v1/minister/{id}', [MinisterController::class, 'destroy']);
Route::delete('/v1/minister/category/{id}', [CategoryController::class, 'destroy']);
Route::delete('/v1/minister/status/{id}', [StatusController::class, 'destroy']);
Route::delete('/v1/minister/party/{id}', [PartyController::class, 'destroy']);
Route::delete('/v1/minister/ministry/{id}', [MinistryController::class, 'destroy']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/logout', [AuthenticationController::class, 'logout']);
    Route::get('/user', [AuthenticationController::class, 'detail']);
});
