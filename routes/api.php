<?php

use App\Http\Controllers\Customer\StripeWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('api')->group(function () {

    // 🔥 Stripe Webhook (NO auth, NO CSRF)
    Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);

});
