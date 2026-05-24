<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CmsController;
use App\Http\Controllers\ContactController;

// Public API endpoints
Route::get('/api/content', [CmsController::class, 'getContent']);
Route::post('/api/contact', [ContactController::class, 'submitInquiry']);

// Admin API endpoints (Session based Auth)
Route::post('/api/admin/login', [\App\Http\Controllers\Admin\AuthController::class, 'login']);

Route::middleware('auth')->group(function () {
    Route::get('/api/admin/user', [\App\Http\Controllers\Admin\AuthController::class, 'user']);
    Route::post('/api/admin/logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout']);

    // Products
    Route::get('/api/admin/products', [\App\Http\Controllers\Admin\ProductController::class, 'index']);
    Route::post('/api/admin/products', [\App\Http\Controllers\Admin\ProductController::class, 'store']);
    Route::put('/api/admin/products/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'update']);
    Route::delete('/api/admin/products/{id}', [\App\Http\Controllers\Admin\ProductController::class, 'destroy']);

    // Machines
    Route::get('/api/admin/machines', [\App\Http\Controllers\Admin\MachineController::class, 'index']);
    Route::post('/api/admin/machines', [\App\Http\Controllers\Admin\MachineController::class, 'store']);
    Route::put('/api/admin/machines/{id}', [\App\Http\Controllers\Admin\MachineController::class, 'update']);
    Route::delete('/api/admin/machines/{id}', [\App\Http\Controllers\Admin\MachineController::class, 'destroy']);

    // Clients
    Route::get('/api/admin/clients', [\App\Http\Controllers\Admin\ClientController::class, 'index']);
    Route::post('/api/admin/clients', [\App\Http\Controllers\Admin\ClientController::class, 'store']);
    Route::put('/api/admin/clients/{id}', [\App\Http\Controllers\Admin\ClientController::class, 'update']);
    Route::delete('/api/admin/clients/{id}', [\App\Http\Controllers\Admin\ClientController::class, 'destroy']);

    // Certificates
    Route::get('/api/admin/certificates', [\App\Http\Controllers\Admin\CertificateController::class, 'index']);
    Route::post('/api/admin/certificates', [\App\Http\Controllers\Admin\CertificateController::class, 'store']);
    Route::put('/api/admin/certificates/{id}', [\App\Http\Controllers\Admin\CertificateController::class, 'update']);
    Route::delete('/api/admin/certificates/{id}', [\App\Http\Controllers\Admin\CertificateController::class, 'destroy']);

    // Facilities
    Route::get('/api/admin/facilities', [\App\Http\Controllers\Admin\FacilityController::class, 'index']);
    Route::post('/api/admin/facilities', [\App\Http\Controllers\Admin\FacilityController::class, 'store']);
    Route::put('/api/admin/facilities/{id}', [\App\Http\Controllers\Admin\FacilityController::class, 'update']);
    Route::delete('/api/admin/facilities/{id}', [\App\Http\Controllers\Admin\FacilityController::class, 'destroy']);

    // CMS & Inquiries
    Route::get('/api/admin/cms', [\App\Http\Controllers\Admin\CmsController::class, 'index']);
    Route::post('/api/admin/cms', [\App\Http\Controllers\Admin\CmsController::class, 'updateBatch']);
    Route::get('/api/admin/inquiries', [\App\Http\Controllers\Admin\ContactInquiryController::class, 'index']);

    // Galleries
    Route::get('/api/admin/galleries', [\App\Http\Controllers\Admin\GalleryController::class, 'index']);
    Route::post('/api/admin/galleries', [\App\Http\Controllers\Admin\GalleryController::class, 'store']);
    Route::put('/api/admin/galleries/{id}', [\App\Http\Controllers\Admin\GalleryController::class, 'update']);
    Route::delete('/api/admin/galleries/{id}', [\App\Http\Controllers\Admin\GalleryController::class, 'destroy']);
});

// Public API endpoints (read-only for entities)
Route::get('/api/products', [\App\Http\Controllers\Admin\ProductController::class, 'index']);
Route::get('/api/machines', [\App\Http\Controllers\Admin\MachineController::class, 'index']);
Route::get('/api/clients', [\App\Http\Controllers\Admin\ClientController::class, 'index']);
Route::get('/api/certificates', [\App\Http\Controllers\Admin\CertificateController::class, 'index']);
Route::get('/api/facilities', [\App\Http\Controllers\Admin\FacilityController::class, 'index']);
Route::get('/api/galleries', [\App\Http\Controllers\Admin\GalleryController::class, 'index']);

// SPA catch-all route (excludes API routes)
Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '^(?!api).*$');
