<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\StripeWebhookController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('api')->group(function () {

    // 🔥 Stripe Webhook (NO auth, NO CSRF)
    Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);

});