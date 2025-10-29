<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\POSController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes - POS System
|--------------------------------------------------------------------------
*/

// Guest routes
Route::middleware('guest')->group(function () {
    // Landing/Welcome page
    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');
    
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard/Home
    Route::get('/home', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // POS Routes - FIXED to match controller methods
    Route::prefix('pos')->name('pos.')->group(function () {
        Route::get('/', [POSController::class, 'index'])->name('index');
        
        // Search & Get Product
        Route::get('/search', [POSController::class, 'searchProduct'])->name('search');
        Route::get('/product/{id}', [POSController::class, 'getProduct'])->name('product');
        
        // Checkout - support both route names
        Route::post('/checkout', [POSController::class, 'processTransaction'])->name('checkout');
        Route::post('/process', [POSController::class, 'processTransaction'])->name('process');
        
        // Receipt
        Route::get('/receipt/{id}', [POSController::class, 'printReceipt'])->name('receipt');
        
        // History
        Route::get('/history', [POSController::class, 'transactionHistory'])->name('history');
    });
    
    // Product Management Routes
    Route::resource('products', ProductController::class);
    
    // Category Management Routes
    Route::resource('categories', CategoryController::class);
    
    // Reports Routes
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/daily', [ReportController::class, 'daily'])->name('daily');
        Route::get('/monthly', [ReportController::class, 'monthly'])->name('monthly');
        Route::get('/products', [ReportController::class, 'products'])->name('products');
        Route::get('/transactions', [ReportController::class, 'transactions'])->name('transactions');
        Route::get('/export-excel', [ReportController::class, 'exportExcel'])->name('export-excel');
        Route::get('/export-pdf', [ReportController::class, 'exportPDF'])->name('export-pdf');
    });
});