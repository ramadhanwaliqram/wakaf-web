<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Wakaf;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $dataWakaf = Wakaf::count();
        $dataWakif = User::where('role', 'user')->count();
        $dataCommittee = User::where('role', 'committee')->count();
        return view('admin.dashboard', compact(['dataWakaf', 'dataWakif', 'dataCommittee']));
    }
}
