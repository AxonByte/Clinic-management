<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(){
        $pageTitle = 'Dashboard';
        return view('admin.dashboard.index', compact('pageTitle'));
    }
}
