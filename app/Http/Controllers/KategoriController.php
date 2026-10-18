<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class KategoriController extends Controller
{
    public function index(): View
    {
        $categories = Kategori::orderBy('type')->orderBy('name')->paginate(15);

        return view('kategori.daftar', compact('categories'));
    }

    public function create(): View
    {
        return view('kategori.tambah', ['category' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);
        $this->ensureUniqueName($data['name'], $data['type']);
        Kategori::create($data);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $category): View
    {
        return view('kategori.ubah', compact('category'));
    }

    public function update(Request $request, Kategori $category): RedirectResponse
    {
        $data = $this->validatedData($request);
        $this->ensureUniqueName($data['name'], $data['type'], $category->id);
        $category->update($data);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Kategori $category): RedirectResponse
    {
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus. Riwayat transaksi tetap tersimpan.');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:60'],
            'type' => ['required', 'in:income,expense'],
        ]);

        $data['name'] = trim($data['name']);

        if ($data['name'] === '' || mb_strtolower($data['name']) === 'lainnya') {
            throw ValidationException::withMessages([
                'name' => 'Nama kategori tidak boleh kosong atau menggunakan nama Lainnya.',
            ]);
        }

        return $data;
    }

    private function ensureUniqueName(string $name, string $type, ?int $ignoreId = null): void
    {
        $exists = Kategori::query()
            ->where('type', $type)
            ->whereRaw('LOWER(name) = ?', [mb_strtolower($name)])
            ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'name' => 'Kategori dengan nama tersebut sudah ada untuk jenis transaksi ini.',
            ]);
        }
    }
}
