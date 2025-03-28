<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::controller(AuthController::class)->prefix("auth")->group(function () {
    Route::post('login', 'login')->name('auth.login');
    Route::post('register', 'register')->name('auth.register');
    Route::post('refresh', 'refresh')->name('auth.refresh');
    
    // Protected routes
    Route::middleware('jwt.auth.token')->group(function () {
        Route::post("logout", "logout")->name("auth.logout");
        Route::get('user-profile', 'userProfile')->name('auth.user.profile');
        Route::post("send-registration-invite", "sendRegistrationInvite")->name("auth.sendRegistrationInvite")->middleware(['role:Admin']);

    });
});

//Route::get('test-api-endpoint', function(){
  //return response()->json(['message' => 'Api endpoint is working']);
//});
