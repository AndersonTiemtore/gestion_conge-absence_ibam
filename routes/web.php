<?php

use App\Http\Controllers\AuthentificationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\FonctionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });


Route::middleware('guest')->group(function () {
    Route::get('/', [AuthentificationController::class, 'loginForm'])->name('login');
    Route::post('/', [AuthentificationController::class, 'login']);

    Route::get('/mot-de-passe-oublie', [AuthentificationController::class, 'passwordForgottenForm'])->name('password.forgotten');
    Route::post('/mot-de-passe-oublie', [AuthentificationController::class, 'passwordForgotten']);

    Route::get('/reset-password/{token}', [AuthentificationController::class, 'resetPasswordForm'])
        ->name('password.reset');

    Route::post('/reset-password', [AuthentificationController::class, 'resetPassword'])
        ->name('password.update');
    // Route::get('/mot-de-passe-oublie', [AuthentificationController::class, 'passwordForgotten'])->name('password.forgotten');
});

Route::get('changement-mot-de-passe', [AuthentificationController::class, 'changePasswordForm'])->middleware('auth')->name('change.password');
Route::post('changement-mot-de-passe', [AuthentificationController::class, 'changePassword'])->middleware('auth');



Route::middleware(['auth'])->group(function () {
        Route::post('deconnexion', [AuthentificationController::class, 'deconnexion'])->name('logout');
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('profile', ProfileController::class)->name('profile');
        // Route::get('parametres', ParametreController::class)->name('parametres');

        Route::get('employes', EmployeController::class)->name('employes');
        // Route::get('responsables', ResponsableController::class)->name('responsables');
        Route::get('services', ServiceController::class)->name('services');
        Route::get('fonctions', FonctionController::class)->name('fonctions');

        Route::get('demande-conge/accepter/{demande}', 'App\Http\Controllers\DemandeValidationController@accepter')->name('demande.accepter');
        Route::get('demande-conge/refuser/{demande}', 'App\Http\Controllers\DemandeValidationController@refuser')->name('demande.refuser');
    });

    Route::post('/notifications/read/{id}', 'App\Http\Controllers\NotificationController@markAsRead')->name('notifications.read');
