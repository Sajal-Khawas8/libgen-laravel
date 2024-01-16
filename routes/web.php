<?php

use App\Http\Controllers\client\BookController as ClientBookController;
use App\Http\Controllers\admin\BookController as AdminBookController;
use App\Http\Controllers\client\UserController as ClientUserController;
use App\Http\Controllers\LoginController;
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

Route::controller(ClientUserController::class)->group(function () {
    Route::middleware("guest")->group(function () {
        Route::get("/register", "create");
        Route::post("/register", "store");
    });

});


Route::controller(LoginController::class)->group(function (){
    // TODO: Change get to post later
    Route::get("/logout", "destroy")->middleware("auth");

    Route::middleware("guest")->group(function (){
        Route::get("/login", "create");
        Route::post("/login", "store");
    });
});