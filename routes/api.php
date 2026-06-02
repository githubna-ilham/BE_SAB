<?php

use App\Http\Controllers\Api\CatatanController;
use Illuminate\Support\Facades\Route;

Route::middleware('api.key')->group(function () {
    Route::get('/catatan', [CatatanController::class, 'index']);
    Route::get('/catatan/{id}', [CatatanController::class, 'show']);
    Route::post('/catatan', [CatatanController::class, 'store']);
    Route::put('/catatan/{id}', [CatatanController::class, 'update']);
    Route::delete('/catatan/{id}', [CatatanController::class, 'destroy']);
});
