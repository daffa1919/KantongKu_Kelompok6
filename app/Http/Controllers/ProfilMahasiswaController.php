<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;

class ProfilMahasiswaController extends Controller
{
    public function edit(): View
    {
        $user = auth()->user();

        $photoUrl = null;

        if (
            $user->profile_photo &&
            Storage::disk('public')->exists($user->profile_photo)
        ) {
            $photoUrl = Storage::disk('public')->url($user->profile_photo)
                . '?v=' . ($user->updated_at?->timestamp ?? time());
        }

        return view('mahasiswa.profil.edit', [
            'user' => $user,
            'photoUrl' => $photoUrl,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'regex:/^[a-zA-Z0-9._%+-]+@gmail\.com$/i',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
            'current_password' => [
                'required_with:password',
                'current_password',
            ],
            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'name.string' => 'Nama lengkap harus berupa teks.',
            'name.min' => 'Nama lengkap minimal 2 karakter.',
            'name.max' => 'Nama lengkap maksimal 100 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.string' => 'Email harus berupa teks.',
            'email.email' => 'Format email tidak valid.',
            'email.regex' => 'Email wajib menggunakan domain @gmail.com, contohnya nama@gmail.com.',
            'email.max' => 'Email maksimal 255 karakter.',
            'email.unique' => 'Email sudah digunakan oleh akun lain.',
            'photo.image' => 'File yang dipilih harus berupa gambar.',
            'photo.mimes' => 'Foto harus berformat JPG, JPEG, PNG, atau WEBP.',
            'photo.max' => 'Ukuran foto maksimal 2 MB.',
            'current_password.required_with' => 'Kata sandi saat ini wajib diisi untuk mengganti kata sandi.',
            'current_password.current_password' => 'Kata sandi saat ini tidak sesuai.',
            'password.min' => 'Kata sandi baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $oldPhotoPath = $user->profile_photo;
        $newPhotoPath = null;

        try {
            if ($request->hasFile('photo')) {
                $newPhotoPath = $request->file('photo')
                    ->store('profile-photos', 'public');

                if (!$newPhotoPath) {
                    throw new \RuntimeException(
                        'Foto profil gagal disimpan.'
                    );
                }
            }

            DB::transaction(function () use (
                $user,
                $validated,
                $newPhotoPath
            ): void {
                $user->name = $validated['name'];
                $user->email = $validated['email'];

                if (!blank($validated['password'] ?? null)) {
                    $user->password = $validated['password'];
                }

                if ($newPhotoPath !== null) {
                    $user->profile_photo = $newPhotoPath;
                }

                $user->saveOrFail();
            });
        } catch (Throwable $exception) {
            if (
                $newPhotoPath !== null &&
                Storage::disk('public')->exists($newPhotoPath)
            ) {
                Storage::disk('public')->delete($newPhotoPath);
            }

            Log::error('Gagal memperbarui profil mahasiswa.', [
                'user_id' => $user->id,
                'exception' => $exception->getMessage(),
            ]);

            return back()
                ->withInput($request->only('name', 'email'))
                ->with(
                    'error',
                    'Profil belum berhasil disimpan. Silakan coba kembali.'
                );
        }

        if (
            $newPhotoPath !== null &&
            $oldPhotoPath !== null &&
            $oldPhotoPath !== $newPhotoPath
        ) {
            try {
                Storage::disk('public')->delete($oldPhotoPath);
            } catch (Throwable $exception) {
                Log::warning('Foto profil lama gagal dihapus.', [
                    'user_id' => $user->id,
                    'exception' => $exception->getMessage(),
                ]);
            }
        }

        return redirect()
            ->route('mahasiswa.profil.edit')
            ->with(
                'success',
                blank($validated['password'] ?? null)
                    ? 'Profil berhasil diperbarui.'
                    : 'Profil dan kata sandi berhasil diperbarui.'
            );
    }
}