@extends('tata-letak.aplikasi')
@section('title', 'Ringkasan keuangan')
@section('content')
@php
    $saldoSaatIni = (float) $totalPemasukan - (float) $totalPengeluaran;
@endphp
<div class="student-welcome surface p-4 p-lg-5 mb-4">
    <div class="row align-items-center g-4">
        <div class="col-lg-8">
            <span class="eyebrow">Ruang keuanganmu</span>
            <h1 class="student-title mt-2 mb-2">Hai, {{ auth()->user()->name }} <span aria-hidden="true">✦</span></h1>
            <p class="text-secondary mb-0 student-copy">Uangmu punya cerita. Catat setiap pemasukan dan pengeluaran supaya kamu tahu ke mana uang pergi dan apa yang bisa disisihkan.</p>
            <div class="d-flex flex-wrap gap-2 mt-4">
                <a class="btn btn-primary" href="{{ route('incomes.create') }}"><i class="bi bi-plus-lg me-2"></i>Catat pemasukan</a>
                <a class="btn btn-outline-primary" href="{{ route('expenses.create') }}"><i class="bi bi-arrow-up-right me-2"></i>Catat pengeluaran</a>
            </div>
        </div>
        <div class="col-lg-4 d-none d-lg-block">
            <div class="welcome-art" aria-hidden="true">
                <div class="art-circle art-circle-one"></div>
                <div class="art-circle art-circle-two"></div>
                <div class="art-note"><i class="bi bi-wallet2"></i><span>Atur hari ini,<br>tenang nanti.</span></div>
                <div class="art-leaf"><i class="bi bi-graph-up-arrow"></i></div>
            </div>
        </div>
    </div>
</div>

@if (session('success'))
    <div class="alert alert-success mb-4" role="status"><i class="bi bi-check-circle me-2"></i>{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="alert alert-danger mb-4" role="alert"><i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}</div>
@endif

<div class="d-flex align-items-center justify-content-between gap-3 mb-3">
    <div><span class="eyebrow">Sekilas kondisi uang</span><h2 class="panel-title mt-1 mb-0">Ringkasan keuangan</h2></div>
    <span class="badge badge-soft"><i class="bi bi-calendar3 me-1"></i>Catatanmu</span>
</div>
<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <article class="surface stat-card h-100">
            <div class="d-flex justify-content-between align-items-start"><span class="stat-label">Total pemasukan</span><span class="stat-icon"><i class="bi bi-arrow-down-left"></i></span></div>
            <div class="stat-value mt-3">Rp {{ number_format((float) $totalPemasukan, 0, ',', '.') }}</div>
            <div class="small text-secondary mt-2">Uang yang sudah kamu terima</div>
        </article>
    </div>
    <div class="col-12 col-md-4">
        <article class="surface stat-card expense h-100">
            <div class="d-flex justify-content-between align-items-start"><span class="stat-label">Total pengeluaran</span><span class="stat-icon"><i class="bi bi-arrow-up-right"></i></span></div>
            <div class="stat-value mt-3">Rp {{ number_format((float) $totalPengeluaran, 0, ',', '.') }}</div>
            <div class="small text-secondary mt-2">Uang yang sudah digunakan</div>
        </article>
    </div>
    <div class="col-12 col-md-4">
        <article class="surface stat-card balance h-100">
            <div class="d-flex justify-content-between align-items-start"><span class="stat-label">Saldo saat ini</span><span class="stat-icon"><i class="bi bi-piggy-bank"></i></span></div>
            <div class="stat-value mt-3">Rp {{ number_format($saldoSaatIni, 0, ',', '.') }}</div>
            <div class="small text-secondary mt-2">Sisa dari pemasukan dikurangi pengeluaran</div>
        </article>
    </div>
</div>

<div class="row g-3">
    <div class="col-12 col-lg-7">
        <section class="surface p-4 h-100">
            <div class="d-flex align-items-start gap-3">
                <span class="quick-icon"><i class="bi bi-journal-check"></i></span>
                <div><h2 class="panel-title mb-1">Biar catatanmu tetap rapi</h2><p class="small text-secondary mb-0">Luangkan sebentar setelah transaksi untuk mencatat nominal dan kategorinya. Kebiasaan kecil ini bikin evaluasi keuangan jauh lebih mudah.</p></div>
            </div>
            <div class="d-flex flex-wrap gap-2 mt-4">
                <a class="btn btn-light" href="{{ route('incomes.index') }}"><i class="bi bi-arrow-down-left-circle me-2"></i>Lihat pemasukan</a>
                <a class="btn btn-light" href="{{ route('expenses.index') }}"><i class="bi bi-arrow-up-right-circle me-2"></i>Lihat pengeluaran</a>
            </div>
        </section>
    </div>
    <div class="col-12 col-lg-5">
        <section class="insight-band p-4 h-100 d-flex gap-3 align-items-start">
            <span class="insight-symbol"><i class="bi bi-lightbulb"></i></span>
            <div><h2 class="panel-title text-white mb-2">Tips kecil hari ini</h2><p class="small text-white-50 mb-0">Bedakan kebutuhan dan keinginan sebelum membeli sesuatu. Kalau masih ragu, beri jeda sebentar dan cek kembali saldo yang tersedia.</p></div>
        </section>
    </div>
</div>
@endsection
@push('styles')
<style>
    .student-welcome { background: linear-gradient(115deg, #fff 0%, #fff 58%, #edf6ee 100%); overflow: hidden; position: relative; }
    .student-title { color: var(--kk-forest); font-size: clamp(1.8rem, 3.6vw, 2.55rem); font-weight: 800; letter-spacing: -.065em; line-height: 1.12; }
    .student-title span { color: #d18a48; font-size: .7em; vertical-align: top; }
    .student-copy { max-width: 650px; line-height: 1.75; }
    .welcome-art { height: 190px; position: relative; }
    .art-circle { border-radius: 50%; position: absolute; }
    .art-circle-one { background: #dceee0; height: 190px; right: 18px; top: -8px; width: 190px; }
    .art-circle-two { border: 1px solid #b5d4bd; height: 150px; right: 38px; top: 12px; width: 150px; }
    .art-note { align-items: center; background: #fff; border: 1px solid #e1ebe1; border-radius: 16px; box-shadow: 0 15px 32px rgba(24,75,51,.12); color: var(--kk-forest); display: flex; gap: 12px; font-size: .9rem; font-weight: 750; line-height: 1.45; padding: 17px; position: absolute; right: 35px; top: 42px; transform: rotate(-4deg); }
    .art-note i { align-items: center; background: var(--kk-mint); border-radius: 12px; color: var(--kk-green); display: inline-flex; font-size: 1.4rem; height: 46px; justify-content: center; width: 46px; }
    .art-leaf { align-items: center; background: #1b6549; border: 5px solid #edf6ee; border-radius: 50%; color: #fff; display: flex; font-size: 1.2rem; height: 52px; justify-content: center; position: absolute; right: 5px; top: 118px; width: 52px; }
    .quick-icon { align-items: center; background: #edf5ee; border-radius: 12px; color: var(--kk-green); display: inline-flex; flex: 0 0 44px; font-size: 1.2rem; height: 44px; justify-content: center; }
    @media (max-width: 767.98px) { .student-welcome { padding: 1.4rem !important; } .student-title { font-size: 1.9rem; } }
</style>
@endpush
