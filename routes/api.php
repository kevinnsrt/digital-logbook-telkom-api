<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DocumentsController;
use App\Http\Controllers\FlutterAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


    // route untuk method yang tidak perlu login
    Route::post('/register',[FlutterAuthController::class,'register']);
    Route::post('/login',[FlutterAuthController::class,'login']);


    Route::get('/notifications', function() {
    // Ambil notifikasi terbaru agar muncul paling atas di aplikasi Flutter
    return response()->json(App\Models\Notifications::latest()->get(), 200);
})->middleware('auth:sanctum');

    // middleware kalau sudah login
 Route::middleware('auth:sanctum')->group(function(){

    // logout 
    Route::post('/logout',[FlutterAuthController::class,'logout']);

    // show all documents
    Route::get('/documents', [DocumentsController::class, 'index']);

    // add documents
    Route::post('/add', [DocumentsController::class, 'add']);

    // delete documents
    Route::post('/delete/{id}', [DocumentsController::class, 'destroy']);

    // reject
     Route::post('/reject/{id}', [DocumentsController::class, 'reject']);

    // pending documents
    Route::post('/pending/{id}', [DocumentsController::class, 'pending']);

    // approved documents
    Route::post('/approved/{id}', [DocumentsController::class, 'approved']);

    // taken documents
    Route::post('/taken/{id}', [DocumentsController::class, 'taken']);

    Route::get('/history', [DocumentsController::class, 'history']);
    Route::get('/superadmin', [DocumentsController::class, 'total']);
 });


 Route::middleware('auth:sanctum')->group(function () {
    // Route untuk update token FCM
    Route::post('/user/update-fcm', [AuthenticatedSessionController::class, 'updateFcmToken']);
});

// buat testing pakai postman


Route::get('/users/list', [FlutterAuthController::class, 'userLists']);


