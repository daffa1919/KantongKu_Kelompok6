@extends('tata-letak.aplikasi')

@section('title', 'Edit Target Tabungan')

@push('styles')
<style>
    .target-page { max-width:850px; margin:0 auto; color:#183b30; }
    .target-heading { margin-bottom:24px; }
    .target-heading h1 { font-size:clamp(1.5rem,3vw,2rem); font-weight:800; letter-spacing:-.7px; margin-bottom:7px; }
    .target-heading p { color:#7b8c83; font-size:.88rem; margin:0; }
    .target-card { background:#fff; border:1px solid #e4ece6; border-radius:20px; box-shadow:0 10px 30px rgba(24,59,48,.04); overflow:hidden; }
    .target-card-header { padding:22px 26px; border-bottom:1px solid #edf2ef; }
    .target-card-header h2 { font-size:1rem; font-weight:800; margin:0 0 5px; }
    .target-card-header p { color:#84928c; font-size:.82rem; margin:0; }
    .target-card-body { padding:26px; }
    .target-label { display:block; color:#344d42; font-size:.84rem; font-weight:700; margin-bottom:8px; }
    .target-input { min-height:47px; border:1px solid #dfe8e2; border-radius:10px; font-size:.88rem; }
    .target-input:focus { border-color:#16805a; box-shadow:0 0 0 4px rgba(22,128,90,.09); }
    .target-help { color:#84928c; font-size:.76rem; margin-top:7px; }
    .target-error { color:#c92a3b; font-size:.78rem; margin-top:6px; }
    .target-field { margin-bottom:21px; }
    .target-actions { display:flex; justify-content:flex-end; gap:10px; flex-wrap:wrap; border-top:1px solid #edf2ef; padding-top:22px; margin-top:6px; }
    .target-btn { display:inline-flex; align-items:center; justify-content:center; gap:8px; border-radius:10px; padding:11px 17px; font-size:.85rem; font-weight:700; text-decoration:none; }
    .target-btn-primary { background:#167653; border:1px solid #167653; color:#fff; }
    .target-btn-primary:hover { background:#105e41; color:#fff; }
    .target-btn-secondary { background:#fff; border:1px solid #dfe8e2; color:#426154; }
    @media(max-width:575px) { .target-card-body,.target-card-header { padding:19px; } .target-actions>* { flex:1; } }
</style>
@endpush

@section('content')
<div class="target-page">
    <div class="target-heading">
        <h1>Edit Target Tabungan</h1>
        <p>Perbarui target dan progres tabunganmu.</p>
    </div>

    <div class="target-card">
        <div class="target-card-header">
            <h2><i class="bi bi-pencil-square me-2 text-success"></i>{{ $targetTabungan->nama_target }}</h2>
            <p>Perubahan akan disimpan setelah kamu menekan tombol simpan.</p>
        </div>

        <div class="target-card-body">
            <form method="POST" action="{{ route('tabungan.update', $targetTabungan) }}">
                @csrf
                @method('PUT')

                <div class="target-field">
                    <label for="nama_target" class="target-label">Nama target <span class="text-danger">*</span></label>
                    <input type="text" id="nama_target" name="nama_target" value="{{ old('nama_target', $targetTabungan->nama_target) }}" class="form-control target-input @error('nama_target') is-invalid @enderror" maxlength="100" required>
                    @error('nama_target')
                        <div class="target-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3">
                    <div class="col-12 col-md-6 target-field">
                        <label for="target_nominal" class="target-label">Nominal target (Rp) <span class="text-danger">*</span></label>
                        <input type="number" id="target_nominal" name="target_nominal" value="{{ old('target_nominal', (float) $targetTabungan->target_nominal) }}" class="form-control target-input @error('target_nominal') is-invalid @enderror" min="0.01" max="9999999999999.99" step="0.01" required>
                        <div class="target-help">Masukkan angka tanpa titik atau simbol mata uang.</div>
                        @error('target_nominal')
                            <div class="target-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 col-md-6 target-field">
                        <label for="terkumpul" class="target-label">Uang terkumpul (Rp) <span class="text-danger">*</span></label>
                        <input type="number" id="terkumpul" name="terkumpul" value="{{ old('terkumpul', (float) $targetTabungan->terkumpul) }}" class="form-control target-input @error('terkumpul') is-invalid @enderror" min="0" max="9999999999999.99" step="0.01" required>
                        <div class="target-help">Masukkan angka tanpa titik. Nominal terkumpul tidak boleh melebihi target.</div>
                        @error('terkumpul')
                            <div class="target-error">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="target-field">
                    <label for="tanggal_target" class="target-label">Tanggal target</label>
                    <input type="date" id="tanggal_target" name="tanggal_target" value="{{ old('tanggal_target', optional($targetTabungan->tanggal_target)->format('Y-m-d')) }}" class="form-control target-input @error('tanggal_target') is-invalid @enderror">
                    @error('tanggal_target')
                        <div class="target-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="target-field">
                    <label for="deskripsi" class="target-label">Deskripsi</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" maxlength="1000" class="form-control target-input @error('deskripsi') is-invalid @enderror">{{ old('deskripsi', $targetTabungan->deskripsi) }}</textarea>
                    @error('deskripsi')
                        <div class="target-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="target-actions">
                    <a href="{{ route('tabungan.index') }}" class="target-btn target-btn-secondary">Batal</a>
                    <button type="submit" class="target-btn target-btn-primary"><i class="bi bi-check-lg"></i>Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection