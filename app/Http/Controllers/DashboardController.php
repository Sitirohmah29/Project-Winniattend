<?php

namespace App\Http\Controllers;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Role;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
{
    // Ambil 10 data presensi terakhir + user-nya
    $attendances = Attendance::with('user')->orderByDesc('date')->limit(10)->get();

    // Hitung total user (pegawai)
    $totalEmployees = User::count();

    // Hitung berdasarkan role
    $laravelCount = User::whereHas('role', fn($q) => $q->where('name', 'Laravel Developer'))->count();
    $fullstackCount = User::whereHas('role', fn($q) => $q->where('name', 'Fullstack Developer'))->count();
    $copywriterCount = User::whereHas('role', fn($q) => $q->where('name', 'Copy Writer'))->count();

    return view('management_system.dashboardWeb', compact(
        'attendances',
        'totalEmployees',
        'laravelCount',
        'fullstackCount',
        'copywriterCount'
    ));
}

}
