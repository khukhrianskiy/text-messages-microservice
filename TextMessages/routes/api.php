<?php

use App\Http\Controllers\Api\TextMessageController;
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

Route::name('text-messages.')
    ->prefix('/text-messages')
    ->group(function () {

    Route::post('/send-order-confirmation', [TextMessageController::class, 'sendOrderConfirmation'])
        ->name('send-order-confirmation');

    Route::get('latest', [TextMessageController::class, 'latest'])
        ->name('latest');

    Route::get('failed', [TextMessageController::class, 'failed'])
        ->name('failed');
});
