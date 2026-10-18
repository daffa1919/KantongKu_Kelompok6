<?php

namespace App\Http\Controllers;

use App\Models\TargetTabungan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TargetTabunganController extends Controller
{
    public function index(): View
    {
        $targets = auth()->user()
            ->targetTabungans()
            ->latest()
            ->paginate(10);

        return view('mahasiswa.tabungan.index', compact('targets'));
    }

    public function create(): View
    {
        return view('mahasiswa.tabungan.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama_target' => ['required', 'string', 'max:100'],
            'target_nominal' => ['required', 'numeric', 'gt:0', 'max:9999999999999.99'],
            'terkumpul' => ['nullable', 'numeric', 'gte:0', 'max:9999999999999.99', 'lte:target_nominal'],
            'tanggal_target' => ['nullable', 'date'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
        ], [
            'nama_target.required' => 'Nama target wajib diisi.',
            'nama_target.max' => 'Nama target maksimal 100 karakter.',
            'target_nominal.required' => 'Nominal target wajib diisi.',
            'target_nominal.numeric' => 'Nominal target harus berupa angka.',
            'target_nominal.gt' => 'Nominal target harus lebih besar dari 0.',
            'target_nominal.max' => 'Nominal target terlalu besar.',
            'terkumpul.numeric' => 'Nominal terkumpul harus berupa angka.',
            'terkumpul.gte' => 'Nominal terkumpul tidak boleh negatif.',
            'terkumpul.max' => 'Nominal terkumpul terlalu besar.',
            'terkumpul.lte' => 'Nominal terkumpul tidak boleh melebihi nominal target.',
            'tanggal_target.date' => 'Tanggal target tidak valid.',
            'deskripsi.max' => 'Deskripsi maksimal 1000 karakter.',
        ]);

        $validated['terkumpul'] = $validated['terkumpul'] ?? 0;

        auth()->user()->targetTabungans()->create($validated);

        return redirect()
            ->route('tabungan.index')
            ->with('success', 'Target tabungan berhasil ditambahkan.');
    }

    public function edit(TargetTabungan $targetTabungan): View
    {
        abort_unless(
            (int) $targetTabungan->user_id === (int) auth()->id(),
            404
        );

        return view('mahasiswa.tabungan.edit', compact('targetTabungan'));
    }

    public function update(
        Request $request,
        TargetTabungan $targetTabungan
    ): RedirectResponse {
        abort_unless(
            (int) $targetTabungan->user_id === (int) auth()->id(),
            404
        );

        $validated = $request->validate([
            'nama_target' => ['required', 'string', 'max:100'],
            'target_nominal' => ['required', 'numeric', 'gt:0', 'max:9999999999999.99'],
            'terkumpul' => ['required', 'numeric', 'gte:0', 'max:9999999999999.99', 'lte:target_nominal'],
            'tanggal_target' => ['nullable', 'date'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
        ], [
            'nama_target.required' => 'Nama target wajib diisi.',
            'nama_target.max' => 'Nama target maksimal 100 karakter.',
            'target_nominal.required' => 'Nominal target wajib diisi.',
            'target_nominal.numeric' => 'Nominal target harus berupa angka.',
            'target_nominal.gt' => 'Nominal target harus lebih besar dari 0.',
            'target_nominal.max' => 'Nominal target terlalu besar.',
            'terkumpul.required' => 'Nominal terkumpul wajib diisi.',
            'terkumpul.numeric' => 'Nominal terkumpul harus berupa angka.',
            'terkumpul.gte' => 'Nominal terkumpul tidak boleh negatif.',
            'terkumpul.max' => 'Nominal terkumpul terlalu besar.',
            'terkumpul.lte' => 'Nominal terkumpul tidak boleh melebihi nominal target.',
            'tanggal_target.date' => 'Tanggal target tidak valid.',
            'deskripsi.max' => 'Deskripsi maksimal 1000 karakter.',
        ]);

        $targetTabungan->update($validated);

        return redirect()
            ->route('tabungan.index')
            ->with('success', 'Target tabungan berhasil diperbarui.');
    }

    public function destroy(TargetTabungan $targetTabungan): RedirectResponse
    {
        abort_unless(
            (int) $targetTabungan->user_id === (int) auth()->id(),
            404
        );

        $targetTabungan->delete();

        return redirect()
            ->route('tabungan.index')
            ->with('success', 'Target tabungan berhasil dihapus.');
    }
}