<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Inventaris Ruangan (KIR)</title>
    <style>
        body { 
            font-family: sans-serif; 
            font-size: 8pt; 
            margin: 5px;
        }
        h2 { 
            text-align: center; 
            margin-bottom: 3px; 
            text-transform: uppercase;
            font-size: 12pt;
        }
        p.subtitle {
            text-align: center;
            margin-top: 0;
            margin-bottom: 12px;
            font-weight: bold;
            font-size: 9pt;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
        }
        th, td { 
            border: 1px solid #000; 
            padding: 4px 3px; 
            text-align: center; 
            vertical-align: middle;
            font-size: 7.5pt;
        }
        th { 
            background-color: #f2f2f2; 
            font-weight: bold;
        }
        .text-left {
            text-align: left;
        }
    </style>
</head>
<body>

    <h2>Daftar Inventaris Ruangan (KIR)</h2>
    @if(isset($kirs) && count($kirs) > 0)
        <p class="subtitle">Ruangan: {{ $kirs->first()->ruangan ?? 'Semua Ruangan' }}</p>
    @endif

    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="16%">Jenis Barang / Nama Barang</th>
                <th width="14%">Merk / Model</th>
                <th width="8%">No. Seri</th>
                <th width="6%">Ukuran</th>
                <th width="7%">Bahan</th>
                <th width="5%">Tahun</th>
                <th width="11%">No. Kode Barang</th>
                <th width="7%">Jumlah / Reg</th>
                <th width="8%">Cara Perolehan</th>
                <th width="6%">Kondisi</th>
                <th width="9%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kirs as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="text-left">{{ $item->jenis_barang ?? '-' }}</td>
                <td class="text-left">{{ $item->merk_model ?? '-' }}</td>
                <td>{{ $item->no_seri ?? '-' }}</td>
                <td>{{ $item->ukuran ?? '-' }}</td>
                <td>{{ $item->bahan ?? '-' }}</td>
                <td>{{ $item->tahun_pembuatan ?? '-' }}</td>
                <td>{{ $item->no_kode_barang ?? '-' }}</td>
                <td>{{ $item->jumlah_register ?? '-' }}</td>
                <td>{{ $item->cara_perolehan ?? '-' }}</td>
                <td>{{ $item->keadaan_barang ?? '-' }}</td>
                <td class="text-left">{{ $item->keterangan ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="12">Data inventaris tidak ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>