@extends('layouts.app')

@section('content')
  <!-- STYLE NATIVE SERAGAM DENGAN THEMA BMD QR ASSET -->
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
      margin-bottom: 18px;
    }

    .crumb { color: #15a9dc; font-size: 13px; font-weight: 700; }
    .page-title { font-size: 30px; margin: 4px 0; font-weight: 800; color: var(--text); }
    .page-subtitle { font-size: 13px; color: var(--muted); margin: 0; }
    
    .top-meta { display: flex; gap: 18px; color: #71869f; font-size: 12px; align-items: center; }
    .profile { display: flex; gap: 8px; align-items: center; color: #172c48; font-weight: 700; }
    .profile-avatar { background: #081a31; color: #fff; width: 32px; height: 32px; border-radius: 50%; display: grid; place-items: center; font-size: 13px; }

    .card {
      background: #fff;
      border: 1px solid var(--line);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      padding: 22px;
    }

    .kir-grid {
      display: grid;
      grid-template-columns: 1.15fr 0.85fr;
      gap: 16px;
      margin-bottom: 18px;
    }

    .card-header-title {
      font-size: 16px;
      font-weight: 800;
      color: var(--text);
      margin: 0 0 6px 0;
    }

    .card-desc {
      font-size: 12px;
      color: var(--muted);
      margin-bottom: 16px;
      line-height: 1.55;
    }

    .file-drop-area {
      border: 2px dashed #cbe3f5;
      border-radius: 12px;
      padding: 18px;
      background: #f8fbfe;
      text-align: center;
      margin-bottom: 18px;
    }

    .file-input-custom {
      width: 100%;
      font-size: 12px;
      color: var(--text);
    }

    .btn-primary {
      background: linear-gradient(90deg, #08c3ee, #08afe1);
      border: 1px solid #08b7e8;
      color: white;
      font-weight: 700;
      padding: 10px 18px;
      border-radius: 10px;
      cursor: pointer;
      text-decoration: none;
      font-size: 12px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .btn-danger-custom {
      background: #ffe7eb;
      border: 1px solid #fcc8cf;
      color: #ef3d55;
      font-weight: 700;
      padding: 10px 18px;
      border-radius: 10px;
      cursor: pointer;
      text-decoration: none;
      font-size: 12px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .btn-outline {
      border: 1px solid #dce8f1;
      background: #fff;
      color: #087da9;
      font-weight: 700;
      padding: 8px 14px;
      border-radius: 8px;
      text-decoration: none;
      font-size: 11px;
      display: inline-flex;
      align-items: center;
      gap: 6px;
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
      padding: 14px;
      border-radius: 14px;
      border: 1px solid var(--line);
      box-shadow: 0 4px 12px rgba(0,0,0,0.03);
      margin: 10px 0 14px;
      display: inline-block;
    }

    .table-card {
      background: #fff;
      border: 1px solid var(--line);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      overflow: hidden;
    }

    .table-header {
      padding: 18px 20px 12px;
      border-bottom: 1px solid #edf2f7;
    }

    .tablewrap { overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; min-width: 800px; }
    th { background: #f4f8fc; color: #58708a; font-size: 11px; text-align: left; padding: 10px 14px; font-weight: 700; }
    td { font-size: 11px; color: #536c88; padding: 10px 14px; border-top: 1px solid #edf2f7; vertical-align: middle; }
    code { background: #eef8ff; color: #087da9; padding: 3px 6px; border-radius: 4px; font-size: 11px; font-family: monospace; }

    .alert-success-custom {
      background: #e0f7ee;
      border: 1px solid #b2ecd5;
      color: #0b9d66;
      padding: 12px 16px;
      border-radius: 12px;
      font-size: 12px;
      font-weight: 600;
      margin-bottom: 16px;
    }

    @media(max-width: 900px) {
      .kir-grid { grid-template-columns: 1fr; }
      .top-meta { display: none; }
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

  <!-- NOTIFIKASI SUKSES (TETAP SAMA) -->
  @if(session('success'))
    <div class="alert-success-custom">
      ✓ {{ session('success') }}
    </div>
  @endif

  <!-- GRID ATAS: IMPORT EXCEL & CARD QR CODE -->
  <div class="kir-grid">

    <!-- PANEL 1: FORM UPLOAD EXCEL -->
    <article class="card">
      <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:10px;">
        <div>
          <h2 class="card-header-title">Import Data Inventaris Excel</h2>
          <p class="card-desc">Unggah file Excel (seperti <code>Sekretariat.xlsx</code>) untuk memasukkan atau memperbarui data barang inventaris ruangan ke database.</p>
        </div>
      </div>

      <form action="{{ route('kir.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="file-drop-area">
          <input type="file" name="file" id="file" class="file-input-custom" required accept=".xlsx, .xls, .csv">
        </div>

        <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
          <button type="submit" class="btn-primary">
            ↑ Unggah File Excel
          </button>

          <a href="{{ route('kir.export-pdf', 'Sekretariat') }}" class="btn-danger-custom" target="_blank">
            📄 Download PDF Inventaris
          </a>
        </div>
      </form>
    </article>

    <!-- PANEL 2: QR CODE RUANGAN -->
    <article class="card qr-card-body">
      <h2 class="card-header-title" style="margin-bottom:2px;">KIR - RUANG SEKRETARIAT</h2>
      <p class="card-desc" style="margin-bottom:6px;">Scan QR Code di bawah untuk melihat PDF Daftar Barang</p>

      <!-- CONTAINER QR CODE AUTOMATIS -->
      <div class="qr-box">
        {!! QrCode::format('svg')->size(150)->margin(1)->generate(route('kir.pdf', 'Sekretariat')) !!}
      </div>

      <div>
        <a href="{{ route('kir.stream-pdf', 'Sekretariat') }}" target="_blank" class="btn-outline">
          👁 Pratinjau Tampilan PDF
        </a>
      </div>
    </article>

  </div>

  <!-- TABEL PRATINJAU DATA BARANG -->
  <section class="table-card">
    <div class="table-header">
      <h2 class="card-header-title" style="margin:0;">Daftar Barang Ruang Sekretariat</h2>
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
              <td><b>{{ $index + 1 }}</b></td>
              <td><code>{{ $item->no_kode_barang ?? '-' }}</code></td>
              <td style="font-weight: 700; color: #102746;">{{ $item->jenis_barang ?? '-' }}</td>
              <td>{{ $item->merk_model ?? '-' }}</td>
              <td>{{ $item->no_seri ?? '-' }}</td>
              <td>{{ $item->bahan ?? '-' }}</td>
              <td>{{ $item->tahun_pembuatan ?? '-' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="7" style="text-align: center; padding: 30px; color: #71869f;">
                Belum ada data barang. Silakan unggah file Excel terlebih dahulu.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </section>
@endsection