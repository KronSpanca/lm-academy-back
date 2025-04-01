<?php

use App\Mail\TestMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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

Route::post('test-mail-sent', function(Resquest $resquest){
  try {
    $mailData = [
      'title' => 'Email Title', 
      'message' => 'This is a test e-mail directed to only students of Lutfi Musiqi High School.',
      'session_title' => $resquest->session_title
    ];

    $cc_users = [];


    Mail::to('kronspanca61@gmail.com')->send(new TestMail($mailData));


    return response()->json('success');
  } catch (\Exception $e) {
    return response()->json([
        'success' => false,
        'error' => [
          'code' => $e->getCode(),
          'message' => $e->getMessage(),
          'type' => class_basename($e)
        ]
    ], 500);
}

});