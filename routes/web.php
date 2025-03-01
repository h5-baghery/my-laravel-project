<?php

use App\Http\Controllers\ExampleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ExampleController::class, "homePage"]);

Route::get('/about', [ExampleController::class, "aboutPage"]);
