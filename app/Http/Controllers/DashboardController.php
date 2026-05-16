<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('pages.Dashboard.Dashboard');
    }

    public function userDashboard()
    {
        return view('pages.UserDashboard.UserDashboard');
    }

    public function categoryDashboard()
    {
        return view('pages.CategoryDashboard.CategoryDashboard');
    }
}
