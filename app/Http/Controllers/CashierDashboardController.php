<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CashierDashboardController extends Controller
{
   public function index()
    {
        return view('dashboard.cashier'); 
    }

    public function index1()
    {
        return view('dashboard.index1'); 
    }
}
