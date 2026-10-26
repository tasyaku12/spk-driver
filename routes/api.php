<?php
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RankingController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/periode', [RankingController::class, 'periode']);
    Route::get('/ranking', [RankingController::class, 'index']);
    Route::get('/ranking/driver/{kode}', [RankingController::class, 'driver']);
});
