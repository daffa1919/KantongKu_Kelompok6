<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class AkunMahasiswaController extends Controller
{
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string'],
        ], [
            'password.required' => 'Password wajib diisi untuk menghapus akun.',
        ]);

        $user = $request->user();

        if (!Hash::check($request->input('password'), $user->password)) {
            return back()->withErrors([
                'password' => 'Password yang kamu masukkan tidak sesuai.',
            ]);
        }

        if ($user->isAdmin()) {
            abort(403, 'Akun admin tidak dapat dihapus melalui fitur ini.');
        }

        $photoPath = $user->profile_photo;

        try {
            $user->delete();
        } catch (Throwable $exception) {
            Log::error('Gagal menghapus akun mahasiswa.', [
                'user_id' => $user->id,
                'exception' => $exception->getMessage(),
            ]);

            return back()->with(
                'error',
                'Akun belum berhasil dihapus. Silakan coba kembali.'
            );
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($photoPath) {
            try {
                Storage::disk('public')->delete($photoPath);
            } catch (Throwable $exception) {
                Log::warning('Foto profil akun yang dihapus gagal dibersihkan.', [
                    'exception' => $exception->getMessage(),
                ]);
            }
        }

        return redirect()
            ->route('login')
            ->with('success', 'Akun berhasil dihapus.');
    }
}
