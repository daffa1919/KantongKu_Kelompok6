<?php

namespace App\Http\Controllers;

use App\Http\Requests\PengeluaranRequest;
use App\Models\Kategori;
use App\Models\Pengeluaran;
use App\Models\Pengguna;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengeluaranController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:100'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $query = Pengeluaran::query()->with('user');

        if (!$request->user()->isAdmin()) {
            $query->where('user_id', $request->user()->id);
        } elseif (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        $expenses = $query
            ->when(
                $filters['q'] ?? null,
                fn (Builder $query, string $search) =>
                    $query->whereRaw(
                        'LOWER(title) LIKE ?',
                        ['%' . mb_strtolower($search, 'UTF-8') . '%']
                    )
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

        return view('pengeluaran.daftar', [
            'expenses' => $expenses,
            'filters' => $filters,
            'students' => $request->user()->isAdmin()
                ? Pengguna::where('role', 'mahasiswa')->orderBy('name')->get()
                : collect(),
        ]);
    }

    public function create(Request $request): View
    {
        $this->ensureStudentCanManage($request);

        return view('pengeluaran.tambah', [
            'students' => collect(),
            'categories' => Kategori::where('type', 'expense')
                ->orderBy('name')
                ->pluck('name'),
        ]);
    }

    public function store(PengeluaranRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        Pengeluaran::create($data);

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Pengeluaran berhasil disimpan.');
    }

    public function edit(Request $request, Pengeluaran $expense): View
    {
        $this->ensureStudentCanManage($request);
        $this->ensureOwnership($request, $expense);

        return view('pengeluaran.ubah', [
            'expense' => $expense,
            'students' => collect(),
            'categories' => Kategori::where('type', 'expense')
                ->orderBy('name')
                ->pluck('name'),
        ]);
    }

    public function update(
        PengeluaranRequest $request,
        Pengeluaran $expense
    ): RedirectResponse {
        $this->ensureOwnership($request, $expense);

        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $expense->update($data);

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    public function destroy(
        Request $request,
        Pengeluaran $expense
    ): RedirectResponse {
        $this->ensureStudentCanManage($request);
        $this->ensureOwnership($request, $expense);

        $expense->delete();

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Pengeluaran berhasil dihapus.');
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
        Pengeluaran $expense
    ): void {
        abort_unless(
            (int) $expense->user_id === (int) $request->user()->id,
            404
        );
    }
}