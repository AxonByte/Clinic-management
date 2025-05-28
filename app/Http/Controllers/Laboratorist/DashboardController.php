<?php

namespace App\Http\Controllers\Laboratorist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        return view('laboratoriest.dashboard.index');
    }
}
