<?php

use App\Http\Controllers\Api\V1\StopController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/stops')->group(function () {
    Route::get('/', [StopController::class, 'index']);

    Route::get('/{year}/{month?}/{date?}', [StopController::class, 'show'])
        ->where([
            'year' => '\d{4}',
            'month' => '(0[1-9]|1[0-2])',
            'date' => '(0[1-9]|[12][0-9]|3[01])',
        ]);

    Route::post('/', [StopController::class, 'store']);

    Route::delete('/{id}', [StopController::class, 'destroyById'])->whereUuid('id');
    Route::delete('/{year}/{month?}/{date?}', [StopController::class, 'destroy'])
        ->where([
            'year' => '\d{4}',
            'month' => '(0[1-9]|1[0-2])',
            'date' => '(0[1-9]|[12][0-9]|3[01])',
        ]);
});
// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');
