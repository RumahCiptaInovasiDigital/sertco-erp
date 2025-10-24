<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('page.dashboard.index', [
            'totalProjects' => 120,
            'draftProjects' => 35,
            'inProgressProjects' => 60,
            'completedProjects' => 25,
        ]);
    }
}
