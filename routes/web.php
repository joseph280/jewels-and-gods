<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StakeController;
use App\Http\Controllers\LogoutController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return inertia('Welcome');
});

Route::post('/signin', [AuthController::class, 'signin'])->name('signin');
Route::redirect('/login', '/')->name('login');
Route::post('/logout', LogoutController::class)->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    Route::post('/assets/{asset_id}/stake', [StakeController::class, 'stake'])->name('stake');
    Route::post('/assets/{asset_id}/unstake', [StakeController::class, 'unstake'])->name('unstake');
    Route::post('/claim-all', [StakeController::class, 'claimAll'])->name('claim_all');
});
