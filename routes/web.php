<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PageController;
use App\Http\Controllers\SocialConnectController;
use App\Http\Controllers\DashboardController;


Route::get('/', [PageController::class, 'home']);
Route::get('connecting/facebook', [SocialConnectController::class, 'redirectToProvider']);
Route::get('connecting/facebook/callback', [SocialConnectController::class, 'handleProviderCallback']);
Route::get('/dashboard', [DashboardController::class, 'index']);
// Route::get('/dashboard/page/{id}/{token}', [DashboardController::class, 'page']);
Route::get('/dashboard/page/{id}', [DashboardController::class, 'page']);
