<?php

namespace App\Http\Controllers;

use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function dashboard(): View
    {
        return view('dashboard.admin', [
            'totalUsers' => User::where('role', 'mahasiswa')->count(),
            'totalAdmins' => User::where('role', 'admin')->count(),
            'totalPemasukan' => (float) Pemasukan::sum('jumlah'),
            'totalPengeluaran' => (float) Pengeluaran::sum('jumlah'),
            'recentUsers' => User::latest()->take(5)->get(),
            'recentPemasukan' => Pemasukan::with('user')->latest()->take(5)->get(),
            'recentPengeluaran' => Pengeluaran::with('user')->latest()->take(5)->get(),
        ]);
    }

    public function users(): View
    {
        return view('admin.users', ['users' => User::latest()->paginate(15)]);
    }

    public function destroyUser(User $user): RedirectResponse
    {
        abort_if($user->is(auth()->user()), 422, 'Akun yang sedang digunakan tidak dapat dihapus.');
        abort_if($user->role === 'admin' && User::where('role', 'admin')->count() <= 1, 422, 'Admin terakhir tidak dapat dihapus.');
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Akun berhasil dihapus.');
    }
}
