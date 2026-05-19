<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\BusinessController as AdminBusinessController;
use App\Http\Controllers\Admin\BusinessOwnerController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CitySearchController;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Owner\OwnerDashboardController;
use App\Http\Controllers\Owner\OwnerOrderController;
use App\Http\Controllers\Owner\OwnerProductController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/businesses', [BusinessController::class, 'index'])->name('businesses.index');
Route::get('/businesses/city/search', [CitySearchController::class, 'index'])->name('businesses.by-city');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/search', [SearchController::class, 'index'])->name('search.index');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isBusinessOwner()) {
            return redirect()->route('owner.dashboard');
        }

        return redirect()->route('user.dashboard');
    })->name('dashboard');

    Route::middleware('role:user')->group(function () {
        Route::get('/user/dashboard', [UserDashboardController::class, 'index'])->name('user.dashboard');
        Route::get('/my-orders', [CustomerOrderController::class, 'index'])->name('orders.index');
        Route::post('/products/{product}/order', [CustomerOrderController::class, 'store'])->name('orders.store');
        Route::patch('/orders/{order}/cancel', [CustomerOrderController::class, 'cancel'])->name('orders.cancel');
    });

    // Business owner dashboard & orders
    Route::middleware('role:business_owner')->prefix('owner')->name('owner.')->group(function () {
        Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/products', [OwnerProductController::class, 'index'])->name('products.index');
        Route::get('/orders', [OwnerOrderController::class, 'index'])->name('orders.index');
        Route::patch('/orders/{order}', [OwnerOrderController::class, 'update'])->name('orders.update');
    });

    Route::middleware('role:business_owner,admin')->group(function () {
        Route::get('/businesses/create', [BusinessController::class, 'create'])->name('businesses.create');
        Route::post('/businesses', [BusinessController::class, 'store'])->name('businesses.store');
        Route::get('/my-businesses', [BusinessController::class, 'myBusinesses'])->name('businesses.my');
        Route::get('/businesses/{business}/edit', [BusinessController::class, 'edit'])->name('businesses.edit');
        Route::put('/businesses/{business}', [BusinessController::class, 'update'])->name('businesses.update');
        Route::delete('/businesses/{business}', [BusinessController::class, 'destroy'])->name('businesses.destroy');

        Route::get('/businesses/{business}/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/businesses/{business}/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    });

    Route::post('/businesses/{business}/reviews', [ReviewController::class, 'store'])->name('reviews.store');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/businesses/{business}', [BusinessController::class, 'show'])->name('businesses.show');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/business-owners', [BusinessOwnerController::class, 'index'])->name('business-owners.index');
    Route::get('/business-owners/create', [BusinessOwnerController::class, 'create'])->name('business-owners.create');
    Route::post('/business-owners', [BusinessOwnerController::class, 'store'])->name('business-owners.store');
    Route::get('/business-owners/{owner}/edit', [BusinessOwnerController::class, 'edit'])->name('business-owners.edit');
    Route::put('/business-owners/{owner}', [BusinessOwnerController::class, 'update'])->name('business-owners.update');
    Route::delete('/business-owners/{owner}', [BusinessOwnerController::class, 'destroy'])->name('business-owners.destroy');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    Route::get('/businesses', [AdminBusinessController::class, 'index'])->name('businesses.index');
    Route::patch('/businesses/{business}', [AdminBusinessController::class, 'update'])->name('businesses.update');
    Route::delete('/businesses/{business}', [AdminBusinessController::class, 'destroy'])->name('businesses.destroy');
    Route::resource('categories', AdminCategoryController::class)->except(['show']);
});

require __DIR__.'/auth.php';
