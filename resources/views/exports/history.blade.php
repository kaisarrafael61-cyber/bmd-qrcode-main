@extends('layouts.app')

@section('content')
  <!-- STYLE NATIVE DARI TEMPLATE DESAIN RIWAYAT EXPORT -->
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
      height: 58px;
      display: flex;
      align-items: center;
      justify-content: flex-end;
      gap: 27px;
      color: #607894;
      font-size: 12px;
      margin-bottom: 10px;
    }

    .top-info {
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .admin {
      display: flex;
      align-items: center;
      gap: 10px;
      color: #122844;
      font-weight: 700;
    }

    .admin .avatar {
      width: 34px;
      height: 34px;
      border-radius: 50%;
      background: #f5f8fb;
      color: #0a2441;
      display: grid;
      place-items: center;
      font-weight: 800;
    }

    .page-head {
      margin-bottom: 18px;
    }

    .crumb {
      font-size: 13px;
      color: #1eabe1;
      font-weight: 700;
      margin-bottom: 5px;
    }

    .crumb span {
      color: #7a8da5;
      margin: 0 8px;
    }

    .page-head h2 {
      margin: 0;
      font-size: 28px;
      letter-spacing: -.7px;
      color: var(--text);
      font-weight: 800;
    }

    .page-head p {
      margin: 4px 0;
      color: #7590aa;
      font-size: 13px;
    }

    .stats {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 14px;
      margin-bottom: 18px;
    }

    .card {
      background: rgba(255, 255, 255, .94);
      border: 1px solid #e1ebf3;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
    }

    .stat {
      padding: 19px 20px;
      display: flex;
      gap: 14px;
      align-items: flex-start;
      min-height: 126px;
    }

    .stat-icon {
      width: 49px;
      height: 49px;
      border-radius: 50%;
      display: grid;
      place-items: center;
      font-size: 21px;
      font-weight: 800;
      flex: none;
    }

    .blue { background: #e3f5ff; color: #0799db; }
    .green { background: #ddf8eb; color: #10ad70; }
    .purple { background: #f0e6ff; color: #7b3fe0; }
    .orange { background: #fff0d6; color: #ec9b12; }

    .stat-label { color: #56718e; font-size: 13px; }
    .stat-value { font-size: 28px; font-weight: 800; margin: 5px 0 3px; color: var(--text); }
    .sub { font-size: 11px; color: #879bb1; margin-top: 4px; }

    .export-log-container {
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .log-card {
      background: #fff;
      border: 1px solid #e1ebf3;
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      padding: 20px 22px;
    }

    .log-header {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    @media (min-width: 992px) {
      .log-header {
        flex-direction: row;
        justify-content: space-between;
        align-items: flex-start;
      }
    }

    .badge-type {
      display: inline-flex;
      align-items: center;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 10px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.1em;
      background: #effbfe;
      color: #079ed1;
      border: 1px solid #bdeaf6;
    }

    .log-title {
      font-size: 16px;
      font-weight: 800;
      color: var(--text);
      margin: 8px 0 4px;
    }

    .log-meta {
      font-size: 12px;
      color: #7590aa;
    }

    .log-meta strong {
      color: #285273;
    }

    .log-summary-box {
      background: #f4f9fc;
      border: 1px solid #e3edf5;
      border-radius: 12px;
      padding: 12px 16px;
      font-size: 12px;
      color: #58718d;
      min-width: 280px;
    }

    .log-summary-box p {
      margin: 4px 0;
    }

    .log-summary-box strong {
      color: #102544;
    }

    .table-wrap {
      overflow: auto;
      border: 1px solid #e3edf5;
      border-radius: 10px;
      margin-top: 15px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      min-width: 600px;
    }

    th {
      height: 38px;
      background: #f4f9fc;
      color: #56718e;
      font-size: 11px;
      text-align: left;
      padding: 0 14px;
      font-weight: 800;
    }

    td {
      height: 40px;
      border-top: 1px solid #edf2f6;
      padding: 0 14px;
      color: #58718d;
      font-size: 11px;
    }

    .btn-back {
      height: 38px;
      border: 1px solid #dce8f2;
      background: #fff;
      color: #55718e;
      border-radius: 9px;
      padding: 0 16px;
      font-weight: 700;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      font-size: 12px;
    }

    .empty-card {
      background: #fff;
      border: 1px solid #e1ebf3;
      border-radius: var(--radius);
      padding: 40px;
      text-align: center;
      color: #7590aa;
      font-size: 13px;
    }

    .footer-row {
      margin-top: 20px;
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
  <section class="page-head" style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 12px;">
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

  <!-- LOG EXPORT LIST -->
  <section class="export-log-container">
    @forelse ($logs as $log)
      @php
        $properties = $log->properties ?? [];
        $exportAssets = $properties['assets'] ?? [];
      @endphp

      <article class="log-card">
        <div class="log-header">
          <div>
            <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
              <span class="badge-type">
                {{ ($properties['export_type'] ?? 'single') === 'bulk' ? 'Export ZIP Massal' : 'Export Single' }}
              </span>
              <span style="font-size: 12px; color: #7590aa;">{{ $log->created_at->format('d M Y H:i') }}</span>
            </div>

            <h3 class="log-title">{{ $log->description }}</h3>

            <div class="log-meta">
              Oleh <strong>{{ $log->user?->name ?? 'Sistem' }}</strong>
              dari IP <strong>{{ $log->ip_address ?: '-' }}</strong>
            </div>
          </div>

          <div class="log-summary-box">
            <p><strong>File:</strong> {{ $properties['filename'] ?? '-' }}</p>
            <p><strong>Jumlah aset:</strong> {{ $properties['total_assets'] ?? 1 }}</p>
            <p><strong>Ringkasan:</strong> {{ $properties['asset'] ?? ($log->subject_label ?: '-') }}</p>
          </div>
        </div>

        @if (! empty($exportAssets))
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>Kode Aset</th>
                  <th>Nama Barang</th>
                  <th>Penanggung Jawab</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($exportAssets as $asset)
                  <tr>
                    <td><strong style="color: #102544;">{{ $asset['asset_code'] ?? '-' }}</strong></td>
                    <td>{{ $asset['name'] ?? '-' }}</td>
                    <td>{{ $asset['person_in_charge'] ?: '-' }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @elseif ($log->subject_label)
          <div style="margin-top: 12px; padding: 10px 14px; background: #f4f9fc; border-radius: 8px; font-size: 12px; color: #58718d;">
            <strong>Aset:</strong> {{ $log->subject_label }}
          </div>
        @endif
      </article>
    @empty
      <div class="empty-card">
        Belum ada riwayat export Word.
      </div>
    @endforelse
  </section>

  <!-- PAGINATION -->
  <div class="footer-row">
    {{ $logs->links() }}
  </div>
@endsection