<?php

use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\PaymentController;
use App\Http\Controllers\admin\ReadersController;
use App\Http\Controllers\client\BookController as ClientBookController;
use App\Http\Controllers\admin\BookController as AdminBookController;
use App\Http\Controllers\client\BookHistory;
use App\Http\Controllers\client\UserController as ClientUserController;
use App\Http\Controllers\admin\UserController as AdminUserController;
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

Route::middleware("client")->group(function () {
    Route::controller(ClientBookController::class)->group(function () {
        Route::get('/', 'index');
        Route::get('/bookDetails/{book:uuid}', 'show')->whereUuid('uuid');
    });

    Route::controller(ClientUserController::class)->group(function () {
        Route::middleware("guest")->group(function () {
            Route::get("/register", "create");
            Route::post("/register", "store");
        });

        Route::middleware("auth")->group(function () {
            Route::get("/settings", "show");
            Route::get("/update", "edit");
            Route::put("/update", "update");
            Route::delete("/delete", "destroy");
        });
    });

    Route::controller(BookHistory::class)->middleware("auth")->group(function (){
        Route::get("/mybooks", "index");
        Route::get("/returnBook/{book:uuid}", "returnBook")->whereUuid("uuid");
        Route::get("/rentHistory/{book:uuid}", "rentHistory")->whereUuid("uuid");
    });
});

Route::controller(LoginController::class)->group(function () {
    Route::middleware("guest")->group(function () {
        Route::get("/login", "create")->name("login");
        Route::post("/login", "store");
    });

    Route::post("/logout", "destroy")->middleware("auth");
});

Route::middleware("admin")->prefix("/admin")->group(function () {
    Route::get("/", DashboardController::class);
    Route::controller(AdminBookController::class)->group(function () {
        Route::get("/books", "index");
        Route::get("/rentedBooks", "rentedBooks");
    });
    Route::get("/readers", [ReadersController::class, "index"]);
    // TODO: Block/Unblock User

    Route::get("/payment", [PaymentController::class, "index"]);
    Route::controller(AdminUserController::class)->group(function (){
        Route::get("/settings", "show");
    });
});