<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    // This Method will show home page
    public function index()
    {
        return view('welcome');
    }
}
