<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

use App\Http\Controllers\Oauth\OAuthController;
use App\Http\Controllers\Oauth\LoginOAuthController;

// Prefijo global para todas las rutas web de OAuth federado
Route::prefix('api/v1')->middleware('web')->group(function () {

    // Ruta inicial de autorización OAuth para iOS y Android
    Route::get('/oauth/authorize', [OAuthController::class, 'authorizeRequest']);





    // Página de login (formulario)
    Route::get('/login-cliente', function () {
        return view('auth.login');
    })->name('cliente.login');

    // Procesar login (manda OTP y redirige)
    Route::post('/login-cliente', [LoginOAuthController::class, 'loginBlade'])->name('cliente.login.form');


    // Ruta para reenviar OTP (desde la vista)
    Route::post('/cliente/resend-otp', [LoginOAuthController::class, 'resendOTPBlade'])->name('cliente.resendOtp');

    Route::get('/oauth/callback', function (Request $request) {
        $code = $request->query('code');
        $state = $request->query('state');

        if (!$code) {
            abort(400, 'Missing authorization code.');
        }

        // Opcionalmente podrías validar el state aquí

        // Redirige al esquema de escritorio
        return redirect("myapp://callback?code={$code}&state={$state}");
    })->name('oauth.callback');


    // Nueva ruta de callback para la app web
    Route::get('/oauth/callback2', function (Request $request) {
        $code = $request->query('code');
        $state = $request->query('state');

        if (!$code) {
            abort(400, 'Missing authorization code.');
        }

        // Opcionalmente podrías validar el state aquí

        // Redirige al esquema de escritorio
        return redirect('https://pagina-prueba.com/web/dashboard');
    })->name('oauth.callback2');



    // Página para ingresar el OTP (vista con campos ocultos desde la sesión) 
    Route::get('/otp-cliente', function () {
        return view('auth.otp', [
            'email' => session('otp_email'),
            'client_id' => session('client_id'),
            'redirect_uri' => session('redirect_uri'),
            'state' => session('state'),
        ]);
    })->name('cliente.otp');

    // Procesar verificación del OTP
    Route::post('/verificar-otp-cliente', [LoginOAuthController::class, 'verifyOtpBlade'])->name('cliente.otp.verify.form');
});
