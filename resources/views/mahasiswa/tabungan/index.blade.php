@extends('tata-letak.aplikasi')

@section('title', 'Target Tabungan')

@push('styles')
<style>
    .saving-page { color: #183b30; }
    .saving-heading { display:flex; justify-content:space-between; align-items:center; gap:16px; flex-wrap:wrap; margin-bottom:26px; }
    .saving-heading h1 { font-size:clamp(1.5rem,3vw,2rem); font-weight:800; letter-spacing:-.8px; margin-bottom:7px; }
    .saving-heading p { color:#7b8c83; margin:0; font-size:.9rem; }
    .saving-btn { display:inline-flex; align-items:center; justify-content:center; gap:8px; padding:11px 16px; border-radius:11px; font-size:.85rem; font-weight:700; text-decoration:none; }
    .saving-btn-primary { background:#167653; border:1px solid #167653; color:#fff; }
    .saving-btn-primary:hover { background:#105e41; color:#fff; }
    .saving-summary { background:linear-gradient(135deg,#104737,#1d7152); color:#fff; border-radius:20px; padding:25px; margin-bottom:24px; }
    .saving-summary-label { color:rgba(255,255,255,.75); font-size:.82rem; margin-bottom:8px; }
    .saving-summary-value { font-size:clamp(1.5rem,4vw,2.1rem); font-weight:800; letter-spacing:-.7px; overflow-wrap:anywhere; }
    .saving-summary-note { color:rgba(255,255,255,.75); font-size:.78rem; margin-top:7px; }
    .saving-card { background:#fff; border:1px solid #e4ece6; border-radius:18px; padding:21px; height:100%; box-shadow:0 8px 24px rgba(24,59,48,.035); }
    .saving-card-top { display:flex; justify-content:space-between; align-items:flex-start; gap:12px; margin-bottom:17px; }
    .saving-icon { display:flex; align-items:center; justify-content:center; width:45px; height:45px; flex-shrink:0; border-radius:13px; background:#e8f3ed; color:#167653; font-size:1.25rem; }
    .saving-card h2 { font-size:1rem; font-weight:800; overflow-wrap:anywhere; margin:0 0 6px; }
    .saving-description { color:#819087; font-size:.8rem; line-height:1.6; overflow-wrap:anywhere; }
    .saving-badge { display:inline-block; background:#e8f3ed; color:#167653; padding:5px 9px; border-radius:20px; font-size:.7rem; font-weight:800; white-space:nowrap; }
    .saving-progress-label { display:flex; justify-content:space-between; gap:8px; font-size:.78rem; margin-bottom:9px; }
    .saving-progress { height:9px; overflow:hidden; border-radius:20px; background:#edf2ef; }
    .saving-progress-bar { height:100%; border-radius:20px; background:#21966a; }
    .saving-amounts { display:flex; justify-content:space-between; gap:12px; margin-top:13px; }
    .saving-amounts small { display:block; color:#84928c; font-size:.72rem; margin-bottom:4px; }
    .saving-amounts strong { display:block; font-size:.88rem; overflow-wrap:anywhere; }
    .saving-date { color:#7b8c83; font-size:.77rem; margin-top:16px; }
    .saving-actions { display:flex; justify-content:flex-end; gap:7px; padding-top:15px; margin-top:15px; border-top:1px solid #edf2ef; }
    .saving-action { display:inline-flex; align-items:center; justify-content:center; width:36px; height:36px; border:1px solid #e0e8e2; border-radius:9px; background:#fff; color:#426154; text-decoration:none; }
    .saving-action:hover { background:#e8f3ed; color:#167653; }
    .saving-action-delete { color:#bd3543; }
    .saving-action-delete:hover { background:#fff0f1; color:#a91f30; }
    .saving-empty { background:#fff; border:1px dashed #cfddd3; border-radius:18px; padding:55px 20px; text-align:center; }
    .saving-empty-icon { font-size:2.5rem; color:#6a9b80; }
    .saving-empty h2 { font-size:1.05rem; font-weight:800; margin:14px 0 8px; }
    .saving-empty p { color:#7b8c83; font-size:.85rem; margin-bottom:20px; }
    .saving-pagination { padding:18px 0 0; }
    @media(max-width:575px) {
        .saving-summary { padding:21px; }
        .saving-card { padding:17px; }
        .saving-heading .saving-btn { width:100%; }
    }
</style>
@endpush

@section('content')
@php
    $totalTarget = (float) $targets->sum('target_nominal');
    $totalTerkumpul = (float) $targets->sum('terkumpul');
    $jumlahSelesai = $targets->filter(fn ($target) => (float) $target->terkumpul >= (float) $target->target_nominal)->count();
@endphp

<div class="saving-page">
    <div class="saving-heading">
        <div>
            <h1>Target Tabungan</h1>
            <p>Rencanakan tujuanmu dan pantau progres menabungmu.</p>
        </div>
        <a href="{{ route('tabungan.create') }}" class="saving-btn saving-btn-primary">
            <i class="bi bi-plus-lg"></i>Tambah Target
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success border-0 rounded-3 small">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger border-0 rounded-3 small">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
        </div>
    @endif

    <section class="saving-summary">
        <div class="row g-4 align-items-center">
            <div class="col-12 col-md-7">
                <div class="saving-summary-label">Total uang yang sudah terkumpul</div>
                <div class="saving-summary-value">Rp {{ number_format($totalTerkumpul, 0, ',', '.') }}</div>
                <div class="saving-summary-note">Dari total target Rp {{ number_format($totalTarget, 0, ',', '.') }}</div>
            </div>
            <div class="col-6 col-md-3">
                <div class="saving-summary-label">Jumlah target</div>
                <div class="saving-summary-value">{{ $targets->total() }}</div>
                <div class="saving-summary-note">Target tabungan</div>
            </div>
            <div class="col-6 col-md-2">
                <div class="saving-summary-label">Tercapai</div>
                <div class="saving-summary-value">{{ $jumlahSelesai }}</div>
                <div class="saving-summary-note">Target selesai di halaman ini</div>
            </div>
        </div>
    </section>

    @if ($targets->count())
        <div class="row g-3">
            @foreach ($targets as $target)
                <div class="col-12 col-md-6 col-xl-4">
                    <article class="saving-card">
                        <div class="saving-card-top">
                            <div class="d-flex gap-3 align-items-start">
                                <div class="saving-icon"><i class="bi bi-piggy-bank"></i></div>
                                <div>
                                    <h2>{{ $target->nama_target }}</h2>
                                    @if ($target->deskripsi)
                                        <div class="saving-description">{{ $target->deskripsi }}</div>
                                    @endif
                                </div>
                            </div>
                            @if ((float) $target->terkumpul >= (float) $target->target_nominal)
                                <span class="saving-badge">Tercapai</span>
                            @else
                                <span class="saving-badge">{{ $target->persentase }}%</span>
                            @endif
                        </div>

                        <div class="saving-progress-label">
                            <span class="text-secondary">Progres tabungan</span>
                            <strong>{{ $target->persentase }}%</strong>
                        </div>
                        <div class="saving-progress">
                            <div class="saving-progress-bar" style="width: {{ $target->persentase }}%"></div>
                        </div>

                        <div class="saving-amounts">
                            <div>
                                <small>Terkumpul</small>
                                <strong class="text-success">Rp {{ number_format((float) $target->terkumpul, 0, ',', '.') }}</strong>
                            </div>
                            <div class="text-end">
                                <small>Target</small>
                                <strong>Rp {{ number_format((float) $target->target_nominal, 0, ',', '.') }}</strong>
                            </div>
                        </div>

                        <div class="saving-date">
                            <i class="bi bi-calendar3 me-1"></i>
                            @if ($target->tanggal_target)
                                Target tanggal {{ $target->tanggal_target->translatedFormat('d M Y') }}
                            @else
                                Belum menetapkan tanggal target
                            @endif
                        </div>

                        <div class="saving-actions">
                            <a class="saving-action" href="{{ route('tabungan.edit', $target) }}" title="Edit target" aria-label="Edit target">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('tabungan.destroy', $target) }}" data-confirm="Yakin ingin menghapus target tabungan ini?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="saving-action saving-action-delete" title="Hapus target" aria-label="Hapus target">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>

        @if ($targets->hasPages())
            <div class="saving-pagination">{{ $targets->links() }}</div>
        @endif
    @else
        <div class="saving-empty">
            <div class="saving-empty-icon"><i class="bi bi-piggy-bank"></i></div>
            <h2>Belum ada target tabungan</h2>
            <p>Mulai tentukan tujuan menabung pertamamu agar progresnya bisa dipantau.</p>
            <a href="{{ route('tabungan.create') }}" class="saving-btn saving-btn-primary">
                <i class="bi bi-plus-lg"></i>Buat Target Pertama
            </a>
        </div>
    @endif
</div>
@endsection
