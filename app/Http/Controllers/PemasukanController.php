<?php

namespace App\Http\Controllers;

use App\Http\Requests\PemasukanRequest;
use App\Models\Kategori;
use App\Models\Pemasukan;
use App\Models\Pengguna;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PemasukanController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $query = Pemasukan::query()->with('user');

        if (!$request->user()->isAdmin()) {
            $query->where('user_id', $request->user()->id);
        } elseif (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        $incomes = $query
            ->when(
                $filters['q'] ?? null,
                fn (Builder $query, string $search) =>
                    $query->where('title', 'like', "%{$search}%")
            )
            ->when(
                $filters['from'] ?? null,
                fn (Builder $query, string $date) =>
                    $query->whereDate('transaction_date', '>=', $date)
            )
            ->when(
                $filters['to'] ?? null,
                fn (Builder $query, string $date) =>
                    $query->whereDate('transaction_date', '<=', $date)
            )
            ->orderByDesc('transaction_date')
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view('pemasukan.daftar', [
            'incomes' => $incomes,
            'filters' => $filters,
            'students' => $request->user()->isAdmin()
                ? Pengguna::where('role', 'mahasiswa')->orderBy('name')->get()
                : collect(),
        ]);
    }

    public function create(Request $request): View
    {
        $this->ensureStudentCanManage($request);

        return view('pemasukan.tambah', [
            'students' => collect(),
            'categories' => Kategori::where('type', 'income')
                ->orderBy('name')
                ->pluck('name'),
        ]);
    }

    public function store(PemasukanRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        Pemasukan::create($data);

        return redirect()
            ->route('incomes.index')
            ->with('success', 'Pemasukan berhasil disimpan.');
    }

    public function edit(Request $request, Pemasukan $income): View
    {
        $this->ensureStudentCanManage($request);
        $this->ensureOwnership($request, $income);

        return view('pemasukan.ubah', [
            'income' => $income,
            'students' => collect(),
            'categories' => Kategori::where('type', 'income')
                ->orderBy('name')
                ->pluck('name'),
        ]);
    }

    public function update(
        PemasukanRequest $request,
        Pemasukan $income
    ): RedirectResponse {
        $this->ensureOwnership($request, $income);

        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $income->update($data);

        return redirect()
            ->route('incomes.index')
            ->with('success', 'Pemasukan berhasil diperbarui.');
    }

    public function destroy(
        Request $request,
        Pemasukan $income
    ): RedirectResponse {
        $this->ensureStudentCanManage($request);
        $this->ensureOwnership($request, $income);

        $income->delete();

        return redirect()
            ->route('incomes.index')
            ->with('success', 'Pemasukan berhasil dihapus.');
    }

    private function ensureStudentCanManage(Request $request): void
    {
        abort_unless(
            $request->user() !== null
            && $request->user()->role === 'mahasiswa',
            403
        );
    }

    private function ensureOwnership(
        Request $request,
        Pemasukan $income
    ): void {
        abort_unless(
            (int) $income->user_id === (int) $request->user()->id,
            404
        );
    }
}