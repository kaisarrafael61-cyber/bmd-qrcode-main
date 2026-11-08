@extends('layouts.app')

@section('content')
  <!-- STYLE NATIVE DISESUAIKAN AGAR PAS 1 LAYAR & SERAGAM DATA ASET -->
  <style>
    :root {
      --navy: #06172f;
      --navy2: #0a2343;
      --cyan: #08c9ee;
      --cyan2: #16bfe9;
      --bg: #eef7fd;
      --card: #fff;
      --text: #102544;
      --muted: #6f849e;
      --line: #e3edf5;
      --green: #19b979;
      --red: #ef4353;
      --orange: #f5ae32;
      --purple: #8b54e8;
      --shadow: 0 8px 25px rgba(30, 77, 112, .07);
      --radius: 14px;
    }

    .topbar {
      height: 48px;
      display: flex;
      align-items: center;
      justify-content: flex-end;
      gap: 20px;
      color: #607894;
      font-size: 11px;
      margin-bottom: 8px;
    }

    .top-info {
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .admin {
      display: flex;
      align-items: center;
      gap: 8px;
      color: #122844;
      font-weight: 700;
    }

    .admin .avatar {
      width: 28px;
      height: 28px;
      border-radius: 50%;
      background: #f5f8fb;
      color: #0a2441;
      display: grid;
      place-items: center;
      font-weight: 800;
      font-size: 10px;
    }

    .page-head {
      margin-bottom: 12px;
    }

    .crumb {
      font-size: 11px;
      color: #1eabe1;
      font-weight: 700;
      margin-bottom: 3px;
    }

    .crumb span {
      color: #7a8da5;
      margin: 0 6px;
    }

    .page-head h2 {
      margin: 0;
      font-size: 22px;
      letter-spacing: -.5px;
      color: var(--text);
      font-weight: 800;
    }

    .page-head p {
      margin: 2px 0;
      color: #7590aa;
      font-size: 11px;
    }

    .stats {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 10px;
      margin-bottom: 12px;
    }

    .card {
      background: rgba(255, 255, 255, .94);
      border: 1px solid #e1ebf3;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
    }

    .stat {
      padding: 12px 14px;
      display: flex;
      gap: 10px;
      align-items: center;
      min-height: unset;
    }

    .stat-icon {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      display: grid;
      place-items: center;
      font-size: 16px;
      font-weight: 800;
      flex: none;
    }

    .blue { background: #e3f5ff; color: #0799db; }
    .green { background: #ddf8eb; color: #10ad70; }
    .purple { background: #f0e6ff; color: #7b3fe0; }
    .orange { background: #fff0d6; color: #ec9b12; }

    .stat-label { color: #56718e; font-size: 10px; }
    .stat-value { font-size: 20px; font-weight: 800; margin: 2px 0 1px; color: var(--text); line-height: 1; }
    .sub { font-size: 9px; color: #879bb1; margin-top: 2px; }

    /* TABEL CARD UTAMA DENGAN SCROLL INTERNAL PERSIS DATA ASET */
    .table-card {
      background: #fff;
      border: 1px solid var(--line);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      display: flex;
      flex-direction: column;
      height: calc(100vh - 275px);
      min-height: 280px;
      overflow: hidden;
    }

    .table-header {
      padding: 10px 16px;
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

    table {
      width: 100%;
      border-collapse: collapse;
      min-width: 800px;
    }

    th {
      background: #f4f8fc;
      color: #58708a;
      font-size: 10px;
      text-align: left;
      padding: 8px 12px;
      font-weight: 700;
      position: sticky;
      top: 0;
      z-index: 10;
    }

    td {
      font-size: 11px;
      color: #536c88;
      padding: 8px 12px;
      border-top: 1px solid #edf2f7;
      vertical-align: middle;
    }

    .badge-type {
      display: inline-flex;
      align-items: center;
      padding: 3px 8px;
      border-radius: 20px;
      font-size: 9px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      background: #effbfe;
      color: #079ed1;
      border: 1px solid #bdeaf6;
    }

    .btn-back {
      height: 32px;
      border: 1px solid #dce8f2;
      background: #fff;
      color: #55718e;
      border-radius: 8px;
      padding: 0 12px;
      font-weight: 700;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      font-size: 11px;
    }

    .empty-card {
      background: #fff;
      border: 1px solid #e1ebf3;
      border-radius: var(--radius);
      padding: 30px;
      text-align: center;
      color: #7590aa;
      font-size: 11px;
    }

    /* FOOTER & PAGINASI KANAN BAWAH PERSIS DATA ASET */
    .foot {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 8px 16px;
      color: #7890a7;
      font-size: 11px;
      border-top: 1px solid #edf2f7;
      background: #fafcff;
      flex-shrink: 0;
    }

    .foot nav > div.flex.justify-between,
    .foot nav > div > p.text-sm.text-gray-700,
    .foot nav p {
      display: none !important;
    }

    .foot nav > div:last-child {
      display: flex;
      align-items: center;
      justify-content: flex-end;
      gap: 4px;
    }

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

    @media(max-width:1100px) {
      .stats { grid-template-columns: repeat(2, 1fr); }
    }

    @media(max-width:760px) {
      .topbar { display: none; }
      .stats { grid-template-columns: 1fr; }
    }
  </style>

  <!-- HEADER / TOPBAR -->
  <header class="topbar">
    <div class="top-info">▣ &nbsp; {{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }}</div>
    <div class="top-info">⌖ &nbsp; Dinas Kominfo Kab. Kutai Barat</div>
    <div class="admin">
      <div class="avatar">●</div>
      {{ Auth::user()->name ?? 'Admin' }} ⌄
    </div>
  </header>

  <!-- PAGE HEAD -->
  <section class="page-head" style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 8px;">
    <div>
      <div class="crumb">Dashboard <span>›</span> <b>Riwayat Export</b></div>
      <h2>Log Export Word QR Aset</h2>
      <p>Halaman ini khusus menampilkan semua riwayat export Word agar lebih jelas dan mudah dicek.</p>
    </div>
    <div>
      <a href="{{ route('assets.index') }}" class="btn-back">← Kembali ke Data Aset</a>
    </div>
  </section>

  <!-- STATS SUMMARY CARDS -->
  <section class="stats">
    <article class="card stat">
      <div class="stat-icon blue">▣</div>
      <div>
        <div class="stat-label">Total Export</div>
        <div class="stat-value">{{ $summary['total_exports'] }}</div>
        <div class="sub">semua riwayat diekspor</div>
      </div>
    </article>

    <article class="card stat">
      <div class="stat-icon green">▣</div>
      <div>
        <div class="stat-label">Export Single</div>
        <div class="stat-value">{{ $summary['single_exports'] }}</div>
        <div class="sub">berkas per individu</div>
      </div>
    </article>

    <article class="card stat">
      <div class="stat-icon purple">▤</div>
      <div>
        <div class="stat-label">Export Massal</div>
        <div class="stat-value">{{ $summary['bulk_exports'] }}</div>
        <div class="sub">berkas format ZIP</div>
      </div>
    </article>

    <article class="card stat">
      <div class="stat-icon orange">▤</div>
      <div>
        <div class="stat-label">Total Aset Diexport</div>
        <div class="stat-value">{{ $summary['assets_exported'] }}</div>
        <div class="sub">item terakomodasi</div>
      </div>
    </article>
  </section>

  <!-- TABEL UTAMA RIWAYAT EXPORT DENGAN SCROLL INTERNAL -->
  <section class="table-card">
    <div class="table-header">
      <h2 style="margin: 0; font-size: 13px; color: var(--text); font-weight: 800;">Daftar Log Riwayat Export</h2>
      <div style="font-size: 11px; color: #71869f;">Total: {{ method_exists($logs, 'total') ? $logs->total() : count($logs) }} Log</div>
    </div>

    <div class="tablewrap">
      <table>
        <thead>
          <tr>
            <th style="width: 45px;">No</th>
            <th style="width: 130px;">Waktu Export</th>
            <th style="width: 120px;">Tipe</th>
            <th>Keterangan File & Rincian Aset</th>
            <th style="width: 130px;">Admin Pelaksana</th>
            <th style="width: 100px;">IP Address</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($logs as $index => $log)
            @php
              $properties = $log->properties ?? [];
              $exportAssets = $properties['assets'] ?? [];
              $isBulk = ($properties['export_type'] ?? 'single') === 'bulk';
            @endphp
            <tr>
              <td><b>{{ method_exists($logs, 'firstItem') ? $logs->firstItem() + $index : $loop->iteration }}</b></td>
              <td>{{ $log->created_at->format('d M Y H:i') }}</td>
              <td>
                <span class="badge-type">
                  {{ $isBulk ? 'ZIP Massal' : 'Single' }}
                </span>
              </td>
              <td>
                <div style="font-weight: 700; color: #102544;">{{ $log->description }}</div>
                <div style="font-size: 10px; color: #7590aa; margin-top: 2px;">
                  File: <b>{{ $properties['filename'] ?? '-' }}</b> | Jumlah: {{ $properties['total_assets'] ?? 1 }} Aset
                  @if (!empty($exportAssets))
                    <br>Rincian: 
                    @foreach($exportAssets as $ast)
                      <code>{{ $ast['asset_code'] ?? '-' }} ({{ $ast['name'] ?? '-' }})</code>{{ !$loop->last ? ', ' : '' }}
                    @endforeach
                  @elseif ($log->subject_label)
                    <br>Aset: <code>{{ $log->subject_label }}</code>
                  @endif
                </div>
              </td>
              <td>{{ $log->user?->name ?? 'Sistem' }}</td>
              <td><code>{{ $log->ip_address ?: '-' }}</code></td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="text-align: center; padding: 35px; color: #71869f;">
                Belum ada riwayat export Word.
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- FOOTER INFORMASI & PAGINASI KANAN BAWAH -->
    <div class="foot">
      <div>
        @if(method_exists($logs, 'total') && $logs->total() > 0)
          Menampilkan {{ $logs->firstItem() }} - {{ $logs->lastItem() }} dari {{ $logs->total() }} data
        @else
          Menampilkan {{ count($logs) }} data
        @endif
      </div>
      <div>
        @if(method_exists($logs, 'links'))
          {{ $logs->links() }}
        @endif
      </div>
    </div>
  </section>
@endsection