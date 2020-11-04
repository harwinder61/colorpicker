<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Reward;
use App\Tournament;
use App\Store;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
    	$user_count = User::where('type','!=','admin')->get()->count();

    	return view('admin.dashboard', compact('user_count'));
    }

    
}
