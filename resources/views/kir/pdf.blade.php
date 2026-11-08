<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daftar Inventaris Ruangan (KIR)</title>
    <style>
        @page {
            margin: 15px 20px 20px 20px;
        }
        body { 
            font-family: Arial, Helvetica, sans-serif; 
            font-size: 8pt; 
            color: #000;
            margin: 0;
            padding: 0;
        }
        .header-title {
            text-align: center;
            margin-bottom: 2px;
            font-weight: bold;
            font-size: 11pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .subtitle {
            text-align: center;
            margin-top: 0;
            margin-bottom: 12px;
            font-weight: bold;
            font-size: 9pt;
            color: #333;
        }
        table.table-kir { 
            width: 100%; 
            border-collapse: collapse; 
            table-layout: fixed;
            word-wrap: break-word;
        }
        table.table-kir th, 
        table.table-kir td { 
            border: 1px solid #000; 
            padding: 4px 3px; 
            text-align: center; 
            vertical-align: middle;
            font-size: 7.5pt;
            line-height: 1.2;
        }
        table.table-kir th { 
            background-color: #f2f2f2; 
            font-weight: bold;
            text-transform: uppercase;
            font-size: 7pt;
        }
        .text-left {
            text-align: left !important;
        }
        .code-badge {
            font-family: monospace;
            font-size: 7pt;
        }
        .empty-row {
            padding: 20px;
            font-style: italic;
            color: #666;
        }
    </style>
</head>
<body>

    <div class="header-title">DAFTAR INVENTARIS RUANGAN (KIR)</div>
    @if(isset($kirs) && count($kirs) > 0)
        <div class="subtitle">RUANGAN: {{ strtoupper($kirs->first()->ruangan ?? ($ruangan ?? 'SEKRETARIAT')) }}</div>
    @else
        <div class="subtitle">RUANGAN: {{ strtoupper($ruangan ?? 'SEKRETARIAT') }}</div>
    @endif

    <table class="table-kir">
        <thead>
            <tr>
                <th style="width: 3%;">No</th>
                <th style="width: 15%;">Jenis Barang / Nama Barang</th>
                <th style="width: 14%;">Merk / Model</th>
                <th style="width: 8%;">No. Seri</th>
                <th style="width: 6%;">Ukuran</th>
                <th style="width: 7%;">Bahan</th>
                <th style="width: 5%;">Tahun</th>
                <th style="width: 12%;">No. Kode Barang</th>
                <th style="width: 7%;">Jumlah / Reg</th>
                <th style="width: 8%;">Cara Perolehan</th>
                <th style="width: 6%;">Kondisi</th>
                <th style="width: 9%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kirs as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td class="text-left" style="font-weight: bold;">{{ $item->jenis_barang ?? '-' }}</td>
                <td class="text-left">{{ $item->merk_model ?? '-' }}</td>
                <td>{{ $item->no_seri ?? '-' }}</td>
                <td>{{ $item->ukuran ?? '-' }}</td>
                <td>{{ $item->bahan ?? '-' }}</td>
                <td>{{ $item->tahun_pembuatan ?? '-' }}</td>
                <td class="code-badge">{{ $item->no_kode_barang ?? '-' }}</td>
                <td>{{ $item->jumlah_register ?? '-' }}</td>
                <td>{{ $item->cara_perolehan ?? '-' }}</td>
                <td>{{ $item->keadaan_barang ?? $item->kondisi ?? 'Baik' }}</td>
                <td class="text-left">{{ $item->keterangan ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="12" class="empty-row">Data inventaris ruangan tidak ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>