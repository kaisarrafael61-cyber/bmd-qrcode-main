@extends('layouts.app')

@section('content')
  <!-- STYLE NATIVE SERAGAM & PRESISI DENGAN HALAMAN DATA ASET -->
  <style>
    :root {
      --navy: #06152d;
      --cyan: #08c3ee;
      --bg: #f3f9fd;
      --text: #102746;
      --muted: #71869f;
      --line: #e3edf5;
      --green: #13b879;
      --red: #ef3d55;
      --orange: #efa91d;
      --radius: 16px;
      --shadow: 0 8px 26px rgba(24, 55, 88, 0.07);
    }

    .topbar {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 10px;
    }

    .crumb { color: #15a9dc; font-size: 11px; font-weight: 700; }
    .page-title { font-size: 22px; margin: 2px 0; font-weight: 800; color: var(--text); }
    .page-subtitle { font-size: 11px; color: var(--muted); margin: 0; }
    
    .top-meta { display: flex; gap: 14px; color: #71869f; font-size: 11px; align-items: center; }
    .profile { display: flex; gap: 6px; align-items: center; color: #172c48; font-weight: 700; }
    .profile-avatar { background: #081a31; color: #fff; width: 26px; height: 26px; border-radius: 50%; display: grid; place-items: center; font-size: 10px; }

    .card {
      background: #fff;
      border: 1px solid var(--line);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      padding: 12px 16px;
    }

    .kir-grid {
      display: grid;
      grid-template-columns: 1.15fr 0.85fr;
      gap: 10px;
      margin-bottom: 10px;
    }

    .card-header-title {
      font-size: 13px;
      font-weight: 800;
      color: var(--text);
      margin: 0 0 3px 0;
    }

    .card-desc {
      font-size: 11px;
      color: var(--muted);
      margin-bottom: 8px;
      line-height: 1.4;
    }

    .file-drop-area {
      border: 2px dashed #cbe3f5;
      border-radius: 10px;
      padding: 8px 12px;
      background: #f8fbfe;
      text-align: center;
      margin-bottom: 8px;
    }

    .file-input-custom {
      width: 100%;
      font-size: 11px;
      color: var(--text);
    }

    /* Filter Bar Presisi */
    .filters {
      background: #fff;
      border: 1px solid var(--line);
      border-radius: 12px;
      box-shadow: 0 4px 16px #19416908;
      padding: 6px 10px;
      display: grid;
      grid-template-columns: 1.8fr 1fr 1fr auto auto;
      gap: 8px;
      margin-bottom: 10px;
      align-items: center;
    }

    .field {
      height: 34px;
      border: 1px solid #dce7f0;
      border-radius: 8px;
      padding: 0 10px;
      background: #fff;
      color: #58708a;
      font-size: 11px;
      line-height: 32px;
      width: 100%;
      box-sizing: border-box;
    }

    select.field {
      padding-top: 0;
      padding-bottom: 0;
      line-height: 32px;
      font-size: 11px;
      cursor: pointer;
    }

    button.field { cursor: pointer; display: inline-flex; align-items: center; justify-content: center; font-weight: 700; line-height: 1; }
    .primary { background: linear-gradient(90deg, #08c3ee, #08afe1); border-color: #08b7e8; color: white; }

    .btn-primary {
      background: linear-gradient(90deg, #08c3ee, #08afe1);
      border: 1px solid #08b7e8;
      color: white;
      font-weight: 700;
      padding: 6px 12px;
      border-radius: 8px;
      cursor: pointer;
      text-decoration: none;
      font-size: 11px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .btn-danger-custom {
      background: #ffe7eb;
      border: 1px solid #fcc8cf;
      color: #ef3d55;
      font-weight: 700;
      padding: 6px 12px;
      border-radius: 8px;
      cursor: pointer;
      text-decoration: none;
      font-size: 11px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .btn-outline {
      border: 1px solid #dce8f1;
      background: #fff;
      color: #087da9;
      font-weight: 700;
      padding: 5px 10px;
      border-radius: 6px;
      text-decoration: none;
      font-size: 10px;
      display: inline-flex;
      align-items: center;
      gap: 5px;
      cursor: pointer;
    }

    .qr-card-body {
      text-align: center;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
    }

    .qr-box {
      background: #ffffff;
      padding: 5px;
      border-radius: 10px;
      border: 1px solid var(--line);
      box-shadow: 0 4px 12px rgba(0,0,0,0.03);
      margin: 2px 0 6px;
      display: inline-block;
    }

    /* KUNCI TINGGI TABEL CARD AGAR PAS TERLIHAT TANPA MEPET */
    .table-card {
      background: #fff;
      border: 1px solid var(--line);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      display: flex;
      flex-direction: column;
      height: calc(100vh - 380px);
      min-height: 240px;
      overflow: hidden;
    }

    .table-header {
      padding: 8px 14px;
      border-bottom: 1px solid #edf2f7;
      flex-shrink: 0;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .tablewrap {
      flex: 1;
      overflow-y: auto;
      overflow-x: auto;
      min-height: 0;
    }

    table { width: 100%; border-collapse: collapse; min-width: 800px; }
    th { background: #f4f8fc; color: #58708a; font-size: 10px; text-align: left; padding: 7px 12px; font-weight: 700; position: sticky; top: 0; z-index: 10; }
    td { font-size: 11px; color: #536c88; padding: 7px 12px; border-top: 1px solid #edf2f7; vertical-align: middle; }
    code { background: #eef8ff; color: #087da9; padding: 2px 5px; border-radius: 4px; font-size: 10px; font-family: monospace; }

    /* FOOTER CLEAN & RAPI (HILANGKAN TEKS TAILWIND DOBEL) */
    .foot {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 6px 14px;
      color: #7890a7;
      font-size: 11px;
      border-top: 1px solid #edf2f7;
      background: #fafcff;
      flex-shrink: 0;
      min-height: 38px;
    }

    .foot nav > div.flex.justify-between,
    .foot nav > div > p.text-sm.text-gray-700,
    .foot nav p {
      display: none !important;
    }

    .foot nav > div:last-child { display: flex; align-items: center; justify-content: flex-end; gap: 4px; }

    .foot nav span[aria-current="page"] > span,
    .foot nav a,
    .foot nav span[aria-disabled="true"] > span {
      display: inline-flex !important;
      align-items: center;
      justify-content: center;
      min-width: 26px;
      height: 26px;
      padding: 0 6px;
      border: 1px solid #dce7f0;
      background: #fff;
      border-radius: 6px;
      font-size: 11px;
      color: #58708a;
      text-decoration: none;
    }

    .foot nav span[aria-current="page"] > span {
      background: #08c3ee !important;
      border-color: #08c3ee !important;
      color: #fff !important;
      font-weight: bold;
    }

    .foot nav span[aria-disabled="true"] > span {
      opacity: 0.5;
      background: #f8fafc;
    }

    .alert-success-custom {
      background: #e0f7ee;
      border: 1px solid #b2ecd5;
      color: #0b9d66;
      padding: 8px 12px;
      border-radius: 8px;
      font-size: 11px;
      font-weight: 600;
      margin-bottom: 8px;
    }

    @media(max-width: 900px) {
      .kir-grid { grid-template-columns: 1fr; }
      .filters { grid-template-columns: 1fr; }
      .top-meta { display: none; }
      .table-card { height: auto; }
    }
  </style>

  <!-- HEADER TOPBAR -->
  <header class="topbar">
    <div>
      <div class="crumb">Dashboard › Dokumen KIR</div>
      <h1 class="page-title">Dokumen KIR</h1>
      <p class="page-subtitle">Manajemen Kartu Inventaris Ruangan (KIR) & Cetak QR Code Daftar Aset</p>
    </div>

    <div class="top-meta">
      <span>▣ &nbsp; {{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }}</span>
      <span>⌖ &nbsp; Dinas Kominfo Kab. Kutai Barat</span>
      <div class="profile">
        <div class="profile-avatar">●</div>
        <span>{{ Auth::user()->name ?? 'Admin' }}</span>
      </div>
    </div>
  </header>

  <!-- NOTIFIKASI SUKSES -->
  @if(session('success'))
    <div class="alert-success-custom">
      ✓ {{ session('success') }}
    </div>
  @endif

  <!-- GRID ATAS: IMPORT EXCEL & CARD QR CODE -->
  <div class="kir-grid">

    <!-- PANEL 1: FORM UPLOAD EXCEL -->
    <article class="card">
      <div style="margin-bottom:4px;">
        <h2 class="card-header-title">Import Data Inventaris Excel</h2>
        <p class="card-desc">Unggah file Excel (seperti <code>Sekretariat.xlsx</code>) untuk memasukkan atau memperbarui data barang inventaris ruangan ke database.</p>
      </div>

      <form action="{{ route('kir.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="file-drop-area">
          <input type="file" name="file" id="file" class="file-input-custom" required accept=".xlsx, .xls, .csv">
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px;">
          <button type="submit" class="btn-primary">
            ↑ Unggah File Excel
          </button>

          <a href="{{ route('kir.export-pdf', 'Sekretariat') }}" class="btn-danger-custom" target="_blank">
            📄 Download PDF Inventaris
          </a>
        </div>
      </form>
    </article>

    <!-- PANEL 2: QR CODE RUANGAN & TOMBOL DOWNLOAD -->
    <article class="card qr-card-body">
      <h2 class="card-header-title" style="margin-bottom:2px;">KIR - RUANG SEKRETARIAT</h2>
      <p class="card-desc" style="margin-bottom:2px;">Scan QR Code di bawah untuk melihat PDF Daftar Barang</p>

      <div class="qr-box" id="qrCodeContainer">
        {!! QrCode::format('svg')->size(95)->margin(1)->generate(route('kir.pdf', 'Sekretariat')) !!}
      </div>

      <div style="display: flex; gap: 6px; align-items: center; flex-wrap: wrap; justify-content: center;">
        <a href="{{ route('kir.stream-pdf', 'Sekretariat') }}" target="_blank" class="btn-outline">
          👁 Pratinjau Tampilan PDF
        </a>
        <button type="button" onclick="downloadQrCode()" class="btn-outline" style="background: #eef8ff; border-color: #cbe8fb; color: #087da9;">
          ↓ Download Gambar QR
        </button>
      </div>
    </article>

  </div>

  <!-- FILTER BAR TERINTEGRASI -->
  <form method="GET" action="{{ route('kir.index') }}" class="filters">
    <input class="field" type="text" name="search" placeholder="⌕ Cari nama barang, kode, atau merk..." value="{{ request('search') }}">
    
    <!-- Filter Tahun (2023 - 2026) -->
    <select class="field" name="year">
      <option value="">Semua Tahun</option>
      <option value="2026" {{ request('year') == '2026' ? 'selected' : '' }}>2026</option>
      <option value="2025" {{ request('year') == '2025' ? 'selected' : '' }}>2025</option>
      <option value="2024" {{ request('year') == '2024' ? 'selected' : '' }}>2024</option>
      <option value="2023" {{ request('year') == '2023' ? 'selected' : '' }}>2023</option>
    </select>

    <!-- Filter Bahan -->
    <select class="field" name="material">
      <option value="">Semua Bahan</option>
      @foreach($materials ?? ['Plastik', 'Aluminium', 'Baja', 'Besi', 'Stainless steel'] as $mat)
        <option value="{{ $mat }}" {{ request('material') == $mat ? 'selected' : '' }}>{{ $mat }}</option>
      @endforeach
    </select>
    
    <button class="field primary" type="submit">⚲ Filter</button>
    <a href="{{ route('kir.index') }}" class="field" style="text-decoration:none; display:flex; align-items:center; justify-content:center;">↻ Reset</a>
  </form>

  <!-- TABEL PRATINJAU DATA BARANG (DENGAN SCROLL INTERNAL & PAGINASI) -->
  <section class="table-card">
    <div class="table-header">
      <h2 class="card-header-title" style="margin:0;">Daftar Barang Ruang Sekretariat</h2>
      <div style="font-size: 11px; color: #71869f;">Total: {{ isset($kirs) ? $kirs->total() : 0 }} Item</div>
    </div>

    <div class="tablewrap">
      <table>
        <thead>
          <tr>
            <th style="width: 45px;">No</th>
            <th>Kode Barang</th>
            <th>Jenis / Nama Barang</th>
            <th>Merk / Model</th>
            <th>No. Seri</th>
            <th>Bahan</th>
            <th>Tahun</th>
          </tr>
        </thead>
        <tbody>
          @forelse($kirs as $index => $item)
            <tr>
              <td><b>{{ $kirs->firstItem() + $index }}</b></td>
              <td><code>{{ $item->no_kode_barang ?? '-' }}</code></td>
              <td style="font-weight: 700; color: #102746;">{{ $item->jenis_barang ?? '-' }}</td>
              <td>{{ $item->merk_model ?? '-' }}</td>
              <td>{{ $item->no_seri ?? '-' }}</td>
              <td>{{ $item->bahan ?? '-' }}</td>
              <td>{{ $item->tahun_pembuatan ?? '-' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="7" style="text-align: center; padding: 25px; color: #71869f;">
                Data barang tidak ditemukan. Silakan unggah file Excel atau sesuaikan filter pencarian.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- FOOTER INFORMASI & PAGINASI ANGKANYA PAS DI KANAN BAWAH -->
    <div class="foot">
      <div>
        @if(isset($kirs) && $kirs->total() > 0)
          Menampilkan {{ $kirs->firstItem() }} - {{ $kirs->lastItem() }} dari {{ $kirs->total() }} data
        @else
          Menampilkan 0 data
        @endif
      </div>
      <div>
        @if(isset($kirs))
          {{ $kirs->links() }}
        @endif
      </div>
    </div>
  </section>

  <!-- SCRIPT DOWNLOAD QR CODE -->
  <script>
    function downloadQrCode() {
      const svgElement = document.querySelector('#qrCodeContainer svg');
      if (!svgElement) return;

      const svgData = new XMLSerializer().serializeToString(svgElement);
      const canvas = document.createElement('canvas');
      const ctx = canvas.getContext('2d');
      const img = new Image();

      canvas.width = 600;
      canvas.height = 600;

      img.onload = function() {
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
        
        const pngFile = canvas.toDataURL('image/png');
        const downloadLink = document.createElement('a');
        downloadLink.href = pngFile;
        downloadLink.download = 'QR-KIR-Ruang-Sekretariat.png';
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
      };

      img.src = 'data:image/svg+xml;base64,' + btoa(unescape(encodeURIComponent(svgData)));
    }
  </script>
@endsection