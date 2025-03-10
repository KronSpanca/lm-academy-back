<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/test-api-endpoint', function (Request $request) {
    return response()->json([
        'message' => 'Hello, Welcome to Rest API Architecture World!',
        'parameters' => $request->all(),
        'moral' => $request->query('Moral')
    ], 200);
});
