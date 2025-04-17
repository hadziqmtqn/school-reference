<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $provinces = Province::get();

        return \view('dashboard', compact('provinces'));
    }
}
