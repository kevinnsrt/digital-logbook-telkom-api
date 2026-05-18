<?php

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

    // pending documents
    Route::post('/pending/{id}', [DocumentsController::class, 'pending']);

    // approved documents
    Route::post('/approved/{id}', [DocumentsController::class, 'approved']);

    // taken documents
    Route::post('/taken/{id}', [DocumentsController::class, 'taken']);
 });
