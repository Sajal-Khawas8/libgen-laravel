<?php

use App\Http\Controllers\admin\AdminController;
use App\Http\Controllers\admin\CategoryController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\PaymentController;
use App\Http\Controllers\admin\ReadersController;
use App\Http\Controllers\client\BookController as ClientBookController;
use App\Http\Controllers\admin\BookController as AdminBookController;
use App\Http\Controllers\client\BookHistory;
use App\Http\Controllers\client\CartController;
use App\Http\Controllers\client\RentController;
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
        Route::get('/bookDetails/{book:uuid}', 'show')->name('bookDetails');
    });

    Route::controller(ClientUserController::class)->group(function () {
        Route::middleware("guest")->group(function () {
            Route::get("/register", "create");
            Route::post("/register", "store");
        });
    });

    Route::middleware("auth")->group(function (){
        Route::controller(ClientUserController::class)->group(function () {
            Route::get("/settings", "show");
            Route::get("/update", "edit");
            Route::put("/update", "update");
            Route::delete("/delete", "destroy");
        });

        Route::controller(BookHistory::class)->group(function (){
            Route::get("/mybooks", "index")->name("myBooks");
            Route::get("/returnBook/{book:uuid}", "returnBook")->whereUuid("uuid");
            Route::get("/rentHistory/{book:uuid}", "rentHistory")->whereUuid("uuid");
        });

        Route::controller(CartController::class)->group(function (){
            Route::get("/cart", "index")->name("cart.index");
            Route::post("/cart", "store")->name("cart.store");
            Route::delete("/cart", "destroy")->name("cart.destroy");
        });

        Route::controller(RentController::class)->group(function (){
            Route::post("/bookPayment/{book:uuid}", "bookPayment")->name("payment.book");
            Route::post("/acceptPayment", "acceptPayment")->name("acceptPayment");
        });
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
        Route::get("/books", "index")->name("admin.books.index");
        Route::get("/books/addBook", "create")->name("admin.books.create");
        Route::post("/books/addBook", "store")->name("admin.books.store");
        Route::get("/books/updateBook/{book:uuid}", "edit")->name("admin.books.edit");
        Route::put("/books/updateBook/{book:uuid}", "update")->name("admin.books.update");
        Route::delete("/books/deleteBook/{book:uuid}", "destroy")->name("admin.books.destroy");
        Route::get("/rentedBooks", "rentedBooks");
    });
    Route::controller(ReadersController::class)->group(function () {
        Route::get("/readers", "index")->name("readers.index");
        Route::delete("/readers/block", "destroy");
    });

    Route::controller(CategoryController::class)->group(function () {
        Route::get("/categories", "index");
        Route::get("/categories/addCategory", "create");
        Route::post("/categories/addCategory", "store");
        Route::get("/categories/updateCategory/{category}", "edit");
        Route::patch("/categories/updateCategory/{category}", "update");
        Route::delete("/categories/delete", "destroy");
    });
    Route::get("/payment", [PaymentController::class, "index"]);

    Route::controller(AdminController::class)->group(function (){
        Route::get("/team", "index");
        Route::get("/settings", "show");
        Route::get("/settings/update", "edit");
        Route::put("/settings/update", "update");
        Route::delete("/delete", "destroy");

        Route::middleware("can:modify-admin-status")->group(function (){
            Route::patch("/makeSuperAdmin", "makeSuperAdmin");
            Route::patch("/removeSuperAdmin", "removeSuperAdmin");
            Route::delete("/removeAdmin", "removeAdmin");
        });

        Route::get("/team/addAdmin", "create");
        Route::post("/team/addAdmin", "store");
    });
});