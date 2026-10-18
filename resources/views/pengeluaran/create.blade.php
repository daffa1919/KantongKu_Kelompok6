<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengeluaran - KantongKu</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px 20px;
            font-family: 'Inter', sans-serif;
            background: #f4f7f5;
            color: #26352d;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            padding: 30px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.06);
        }

        h1 {
            color: #18794e;
            margin-top: 0;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
        }

        textarea {
            resize: vertical;
            min-height: 90px;
        }

        .button {
            display: inline-block;
            padding: 12px 18px;
            border: none;
            border-radius: 8px;
            background: #18794e;
            color: white;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }

        .back {
            background: #e5eee8;
            color: #18794e;
            margin-left: 8px;
        }

        .error {
            color: #dc2626;
            font-size: 13px;
            margin-top: 5px;
        }

        .alert {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 18px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Tambah Pengeluaran</h1>
        <p>Catat pengeluaranmu agar keuangan tetap terpantau.</p>

        @if ($errors->any())
            <div class="alert">
                <strong>Periksa kembali data yang kamu masukkan.</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('pengeluaran.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="nama_pengeluaran">Nama Pengeluaran</label>
                <input
                    type="text"
                    id="nama_pengeluaran"
                    name="nama_pengeluaran"
                    value="{{ old('nama_pengeluaran') }}"
                    placeholder="Contoh: Makan siang"
                    required
                >
            </div>

            <div class="form-group">
                <label for="jumlah">Jumlah (Rp)</label>
                <input
                    type="number"
                    id="jumlah"
                    name="jumlah"
                    value="{{ old('jumlah') }}"
                    min="1"
                    step="0.01"
                    placeholder="Contoh: 15000"
                    required
                >
            </div>

            <div class="form-group">
                <label for="kategori">Kategori</label>
                <select id="kategori" name="kategori" required>
                    <option value="">Pilih kategori</option>
                    @foreach (['Makanan', 'Transportasi', 'Pendidikan', 'Belanja', 'Hiburan', 'Tagihan', 'Lainnya'] as $kategori)
                        <option value="{{ $kategori }}"
                            @selected(old('kategori') === $kategori)>
                            {{ $kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="tanggal">Tanggal</label>
                <input
                    type="date"
                    id="tanggal"
                    name="tanggal"
                    value="{{ old('tanggal', date('Y-m-d')) }}"
                    required
                >
            </div>

            <div class="form-group">
                <label for="keterangan">Keterangan (Opsional)</label>
                <textarea
                    id="keterangan"
                    name="keterangan"
                    placeholder="Tambahkan keterangan jika diperlukan"
                >{{ old('keterangan') }}</textarea>
            </div>

            <button type="submit" class="button">Simpan Pengeluaran</button>
            <a href="{{ route('pengeluaran.index') }}"
               class="button back">Kembali</a>
        </form>
    </div>
</body>
</html>