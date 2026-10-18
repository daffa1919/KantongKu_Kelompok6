<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pengeluaran - KantongKu</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: #f4f7f5;
            color: #26352d;
        }

        .navbar {
            background: #18794e;
            color: white;
            padding: 18px 7%;
            font-size: 22px;
            font-weight: bold;
        }

        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0 0 8px;
            font-size: 28px;
        }

        .header p {
            margin: 0;
            color: #718078;
        }

        .btn {
            display: inline-block;
            padding: 11px 16px;
            border: none;
            border-radius: 7px;
            background: #18794e;
            color: white;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-edit {
            background: #e8f3ec;
            color: #18794e;
        }

        .btn-delete {
            background: #fee9e7;
            color: #b42318;
        }

        .card {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.04);
            overflow-x: auto;
        }

        .alert {
            background: #e4f5e9;
            color: #17663f;
            padding: 13px;
            border-radius: 7px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 700px;
        }

        th, td {
            padding: 15px 12px;
            border-bottom: 1px solid #edf0ed;
            text-align: left;
        }

        th {
            background: #f7faf8;
            color: #637168;
            font-size: 13px;
        }

        .actions {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .empty {
            padding: 35px;
            text-align: center;
            color: #718078;
        }

        form {
            margin: 0;
        }
    </style>
</head>
<body>

    <div class="navbar">KantongKu</div>

    <main class="container">
        <div class="header">
            <div>
                <h1>Data Pengeluaran</h1>
                <p>Catat dan pantau pengeluaranmu dengan mudah.</p>
            </div>

            <a href="{{ route('pengeluaran.create') }}" class="btn">
                + Tambah Pengeluaran
            </a>
        </div>

        @if (session('success'))
            <div class="alert">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert" style="background:#fee9e7;color:#b42318">
                Ada data yang belum valid. Silakan periksa kembali formulir.
            </div>
        @endif

        <div class="card">
            @if ($pengeluarans->isEmpty())
                <div class="empty">
                    <h3>Belum ada pengeluaran</h3>
                    <p>Yuk, mulai catat pengeluaran pertamamu!</p>
                </div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama Pengeluaran</th>
                            <th>Kategori</th>
                            <th>Tanggal</th>
                            <th>Jumlah</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($pengeluarans as $pengeluaran)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $pengeluaran->nama_pengeluaran }}</td>
                                <td>{{ $pengeluaran->kategori }}</td>
                                <td>{{ $pengeluaran->tanggal->format('d/m/Y') }}</td>
                                <td>
                                    Rp {{ number_format((float) $pengeluaran->jumlah, 0, ',', '.') }}
                                </td>
                                <td>
                                    <div class="actions">
                                        <a
                                            href="{{ route('pengeluaran.edit', $pengeluaran) }}"
                                            class="btn btn-edit"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            action="{{ route('pengeluaran.destroy', $pengeluaran) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus pengeluaran ini?')"
                                        >
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-delete">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </main>

</body>
</html>
