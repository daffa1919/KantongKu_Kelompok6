<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use Illuminate\View\View;

class MahasiswaDashboardController extends Controller
{
    public function index(): View
    {
        $userId = auth()->id();
        $totalPemasukan = (float) Pemasukan::where('user_id', $userId)->sum('jumlah');
        $totalPengeluaran = (float) Pengeluaran::where('user_id', $userId)->sum('jumlah');

        return view('dashboard.mahasiswa', compact('totalPemasukan', 'totalPengeluaran'));
    }
}
