<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AssetController;

Route::get('/assets', [AssetController::class, 'index']);