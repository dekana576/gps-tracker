<?php

// app/Http/Controllers/AdminController.php
namespace App\Http\Controllers;

class AdminController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }
}
