<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CmsController;
use App\Http\Controllers\ContactController;

// Public API endpoints
Route::get('/api/content', [CmsController::class, 'getContent']);
Route::post('/api/contact', [ContactController::class, 'submitInquiry']);

// SPA catch-all route (excludes API routes)
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '^(?!api).*$');
