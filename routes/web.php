<?php

use App\Http\Controllers\client\BookController as ClientBookController;
use App\Http\Controllers\admin\BookController as AdminBookController;
use App\Http\Controllers\client\UserController as ClientUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [ClientBookController::class, 'index']);
Route::get('/bookDetails/{book:uuid}', [ClientBookController::class, 'show'])->whereUuid('uuid');


Route::get("/newUser", [ClientUserController::class, "create"]);
Route::post("/newUser", [ClientUserController::class, "store"]);
Route::view("/login", "pages.client.login");