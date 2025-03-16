<?php

use Illuminate\Support\Facades\Route;

    /** Rotas para links de pagamentos */
    Route::prefix('pagamentos')->group(function () {
        Route::get('payment-link', [\App\Http\Controllers\Api\ApiLinkPagamentoController\apiPaymentLinkController::class, 'listaLinksPagamento'])->middleware('auth:sanctum');
        Route::post('payment-link', [\App\Http\Controllers\Api\ApiLinkPagamentoController\apiPaymentLinkController::class, 'createLink'])->middleware('auth:sanctum');
        Route::put('payment-link/{id}', [\App\Http\Controllers\Api\ApiLinkPagamentoController\apiPaymentLinkController::class, 'editLink'])->middleware('auth:sanctum');
    });

    Route::prefix('auth')->group(function () {
        Route::post('register', [\App\Http\Controllers\Api\Auth\ApiAuthController::class, 'register']);
        Route::post('login', [\App\Http\Controllers\Api\Auth\ApiAuthController::class, 'login']);
        Route::post('logout', [\App\Http\Controllers\Api\Auth\ApiAuthController::class, 'logout']);
    });
