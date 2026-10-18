
@extends('tata-letak.aplikasi')

@section('title', 'Edit Profil Mahasiswa')

@push('styles')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    .profile-page {
        font-family: 'Inter', sans-serif;
        color: #183b30;
    }

    .profile-heading {
        margin-bottom: 24px;
    }

    .profile-heading h1 {
        font-size: clamp(1.5rem, 3vw, 2rem);
        font-weight: 800;
        letter-spacing: -.8px;
        margin-bottom: 8px;
    }

    .profile-heading p {
        color: #73847d;
        margin: 0;
        line-height: 1.7;
    }

    .profile-card {
        background: #fff;
        border: 1px solid #e6eee9;
        border-radius: 22px;
        box-shadow: 0 12px 35px rgba(24, 59, 48, .05);
        overflow: hidden;
    }

    .profile-card-header {
        padding: 22px 26px;
        border-bottom: 1px solid #edf2ef;
    }

    .profile-card-header h2 {
        font-size: 1rem;
        font-weight: 700;
        margin: 0 0 5px;
    }

    .profile-card-header p {
        color: #84928c;
        font-size: .84rem;
        margin: 0;
    }

    .profile-card-body {
        padding: 26px;
    }

    .profile-photo-wrap {
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 30px;
    }

    .profile-photo {
        width: 104px;
        height: 104px;
        border-radius: 50%;
        object-fit: cover;
        border: 4px solid #e3f4eb;
        background: #edf7f1;
        flex-shrink: 0;
    }

    .profile-photo-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        color: #16805a;
        font-size: 2rem;
        font-weight: 700;
    }

    .profile-photo-info h3 {
        font-size: .98rem;
        font-weight: 700;
        margin-bottom: 6px;
    }

    .profile-photo-info p {
        color: #84928c;
        font-size: .8rem;
        margin-bottom: 12px;
    }

    .profile-label {
        display: block;
        font-size: .86rem;
        font-weight: 700;
        margin-bottom: 9px;
        color: #344d42;
    }

    .profile-input {
        width: 100%;
        min-height: 48px;
        border: 1px solid #dfe8e2;
        border-radius: 11px;
        padding: 12px 14px;
        font: inherit;
        font-size: .9rem;
        color: #183b30;
        background: #fff;
        transition: border-color .2s, box-shadow .2s;
    }

    .profile-input:focus {
        outline: none;
        border-color: #16805a;
        box-shadow: 0 0 0 4px rgba(22, 128, 90, .1);
    }

    .profile-input.is-invalid {
        border-color: #dc3545;
    }

    .profile-field {
        margin-bottom: 22px;
    }

    .profile-help {
        display: block;
        font-size: .78rem;
        color: #84928c;
        margin-top: 8px;
    }

    .profile-error {
        display: block;
        color: #c92a3b;
        font-size: .8rem;
        margin-top: 7px;
    }

    .profile-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding-top: 22px;
        border-top: 1px solid #edf2ef;
        flex-wrap: wrap;
    }

    .profile-btn {
        border-radius: 10px;
        padding: 11px 18px;
        font: inherit;
        font-size: .86rem;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    .profile-btn-primary {
        color: #fff;
        background: #167653;
        border: 1px solid #167653;
    }

    .profile-btn-primary:hover {
        color: #fff;
        background: #105e41;
    }

    .profile-btn-secondary {
        color: #426154;
        background: #fff;
        border: 1px solid #dfe8e2;
    }

    .profile-alert {
        border-radius: 12px;
        padding: 14px 16px;
        margin-bottom: 20px;
        font-size: .88rem;
    }

    .profile-section-label {
        color: #16805a;
        font-size: .72rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }

    @media (max-width: 575px) {
        .profile-card-body,
        .profile-card-header {
            padding: 19px;
        }

        .profile-photo-wrap {
            gap: 14px;
        }

        .profile-photo {
            width: 84px;
            height: 84px;
        }

        .profile-actions > * {
            flex: 1;
        }
    }
</style>
@endpush

@section('content')
@php
    $user = auth()->user();
    $photoPath = $user->profile_photo;
    $photoExists = $photoPath
        && \Illuminate\Support\Facades\Storage::disk('public')->exists($photoPath);
    $photoUrl = $photoExists
        ? \Illuminate\Support\Facades\Storage::disk('public')->url($photoPath)
        : null;
    $initial = mb_strtoupper(mb_substr(trim($user->name), 0, 1));
@endphp

<div class="profile-page">
    <div class="profile-heading">
        <div class="profile-section-label">Pengaturan akun</div>
        <h1>Edit Profil</h1>
        <p>Perbarui informasi pribadi dan foto profil akun KantongKu kamu.</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success profile-alert" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger profile-alert" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger profile-alert" role="alert">
            <strong>Profil belum bisa disimpan.</strong>
            <div class="mt-1">Periksa kembali kolom yang ditandai dan coba lagi.</div>
        </div>
    @endif

    <div class="profile-card">
        <div class="profile-card-header">
            <h2>Informasi pribadi</h2>
            <p>Pastikan informasi akun kamu selalu benar dan terbaru.</p>
        </div>

        <div class="profile-card-body">
            <form method="POST"
                  action="{{ route('mahasiswa.profil.update') }}"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="profile-photo-wrap">
                    <img
                        id="profilePhotoPreview"
                        class="profile-photo"
                        src="{{ $photoUrl ? $photoUrl . '?v=' . ($user->updated_at?->timestamp ?? time()) : '' }}"
                        alt="Foto profil {{ $user->name }}"
                        @if (!$photoUrl) hidden @endif
                    >

                    <div
                        id="profilePhotoPlaceholder"
                        class="profile-photo profile-photo-placeholder"
                        @if ($photoUrl) hidden @endif
                        aria-label="Foto profil belum tersedia"
                    >
                        {{ $initial }}
                    </div>

                    <div class="profile-photo-info">
                        <h3>Foto profil</h3>
                        <p>JPG, PNG, atau WEBP. Ukuran maksimal 2 MB.</p>

                        <label for="photo" class="profile-btn profile-btn-secondary mb-0">
                            <i class="bi bi-camera"></i>
                            Pilih foto
                        </label>

                        <input
                            type="file"
                            id="photo"
                            name="photo"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            class="d-none @error('photo') is-invalid @enderror"
                        >

                        @error('photo')
                            <span class="profile-error">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 profile-field">
                        <label class="profile-label" for="name">Nama lengkap</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="profile-input @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}"
                            maxlength="100"
                            minlength="2"
                            autocomplete="name"
                            placeholder="Masukkan nama lengkap"
                            required
                        >
                        @error('name')
                            <span class="profile-error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="col-12 profile-field">
                        <label class="profile-label" for="email">Alamat email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="profile-input @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}"
                            maxlength="255"
                            autocomplete="email"
                            placeholder="nama@email.com"
                            required
                        >
                        @error('email')
                            <span class="profile-error">{{ $message }}</span>
                        @enderror
                        <span class="profile-help">
                            Email harus unik dan belum digunakan akun lain.
                        </span>
                    </div>
                </div>

                <div class="profile-actions">
                    <a href="{{ route('dashboard') }}" class="profile-btn profile-btn-secondary">
                        Batal
                    </a>

                    <button type="submit" class="profile-btn profile-btn-primary">
                        <i class="bi bi-check2-circle"></i>
                        Simpan perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('photo');
        const preview = document.getElementById('profilePhotoPreview');
        const placeholder = document.getElementById('profilePhotoPlaceholder');

        if (!input || !preview || !placeholder) {
            return;
        }

        input.addEventListener('change', function () {
            const file = this.files && this.files[0];

            if (!file) {
                return;
            }

            const allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];

            if (!allowedTypes.includes(file.type)) {
                alert('Pilih foto berformat JPG, PNG, atau WEBP.');
                this.value = '';
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran foto maksimal 2 MB.');
                this.value = '';
                return;
            }

            const reader = new FileReader();

            reader.onload = function (event) {
                preview.src = event.target.result;
                preview.hidden = false;
                placeholder.hidden = true;
            };

            reader.onerror = function () {
                alert('Foto tidak dapat dibaca. Silakan pilih file lain.');
                input.value = '';
            };

            reader.readAsDataURL(file);
        });

        preview.addEventListener('error', function () {
            preview.hidden = true;
            placeholder.hidden = false;
        });
    });
</script>
@endpush