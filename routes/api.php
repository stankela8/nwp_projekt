<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

Log::info('WEB ROUTES LOADED');


Route::get('/test-api', function (Request $request) {
    return response()->json(['ok' => true]);
});
