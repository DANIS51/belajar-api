<?php
use App\Http\Controllers\Api\UserController;
use App\Models\User;
use Illuminate\Support\Facades\Route;


Route::apiResource('users', UserController::class);

