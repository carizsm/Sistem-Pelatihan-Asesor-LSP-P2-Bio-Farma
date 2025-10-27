<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // sementara kosong dulu
        $tugas = collect([]);
        $riwayat = collect([]);

        return view('dashboard', compact('user', 'tugas', 'riwayat'));
    }
}
