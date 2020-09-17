<?php

use App\Http\Controllers\Api\OrderConfirmationMessageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::name('order-confirmation-message.')
    ->prefix('/order-confirmation-message')
    ->group(function () {

    Route::post('/send', [OrderConfirmationMessageController::class, 'send'])
        ->name('send');
});
