<?php

use Illuminate\Support\Facades\Route;

Route::any('{any}', function () {
    abort(503, 'Forbidden');
})->where('any', '.*');
