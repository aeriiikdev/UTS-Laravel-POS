<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    // Konstruktor untuk middleware (biar hanya bisa diakses setelah login)
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Method utama yang dipanggil oleh route /dashboard
    public function index()
    {
        // Menampilkan tampilan dashboard
        return view('dashboard');
    }
}
