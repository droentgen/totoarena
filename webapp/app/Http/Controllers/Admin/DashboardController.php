<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Family;
use App\Models\Student;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'guardianCount' => User::count(),
            'studentCount' => Student::count(),
            'familyCount' => Family::count(),
            'recentGuardians' => User::latest()->take(10)->get(),
        ]);
    }
}
