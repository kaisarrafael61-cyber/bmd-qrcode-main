@extends('layouts.app')

@section('content')
  <!-- Chart.js untuk grafik kondisi aset -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>

  <!-- STYLE NATIVE KHUSUS DASHBOARD SAMA PERSIS DENGAN KODINGAN DESAIN -->
  <style>
    :root {
      --navy: #031126;
      --navy-soft: #081a34;
      --cyan: #10c5e8;
      --cyan-dark: #05a9d0;
      --green: #18b77a;
      --green-soft: #e4f8ef;
      --red: #f04458;
      --red-soft: #ffe7eb;
      --orange: #f5aa27;
      --orange-soft: #fff1d9;
      --gray-blue: #9bb0c9;
      --gray-soft: #edf3f8;
      --text: #13243e;
      --muted: #71829a;
      --radius: 16px;
      --shadow: 0 8px 26px rgba(24, 55, 88, 0.07);
    }

    .topbar {
      min-height: 58px;
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      gap: 20px;
      margin-bottom: 18px;
    }

    .page-kicker {
      margin-bottom: 4px;
      color: var(--cyan-dark);
      font-size: 12px;
      font-weight: 800;
      letter-spacing: 0.5px;
    }

    .page-title {
      margin: 0;
      font-size: 30px;
      font-weight: 800;
      letter-spacing: -0.8px;
      line-height: 1.05;
      color: var(--text);
    }

    .page-subtitle {
      margin: 4px 0 0;
      color: var(--muted);
      font-size: 12px;
    }

    .top-meta {
      display: flex;
      align-items: center;
      gap: 15px;
      padding-top: 4px;
      color: #657993;
      font-size: 10px;
      white-space: nowrap;
    }

    .top-meta-item {
      padding-right: 15px;
      border-right: 1px solid #dce6ef;
    }

    .profile {
      display: flex;
      align-items: center;
      gap: 8px;
      color: var(--text);
      font-size: 11px;
      font-weight: 750;
    }

    .profile-avatar {
      width: 31px;
      height: 31px;
      display: grid;
      place-items: center;
      border-radius: 50%;
      color: #fff;
      background: var(--navy);
      font-size: 13px;
    }

    .card {
      border: 1px solid #e7eef5;
      border-radius: var(--radius);
      background: #ffffff;
      box-shadow: var(--shadow);
    }

    .statistics {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 15px;
      margin-bottom: 17px;
    }

    .stat-card {
      min-height: 147px;
      display: flex;
      gap: 14px;
      padding: 18px 20px;
    }

    .stat-icon {
      width: 47px;
      height: 47px;
      flex: 0 0 47px;
      display: grid;
      place-items: center;
      border-radius: 50%;
      font-size: 20px;
      font-weight: 800;
    }

    .stat-icon.blue { color: #168de2; background: #e5f3ff; }
    .stat-icon.green { color: #16af75; background: #e3f8ee; }
    .stat-icon.red { color: #ed4057; background: #ffe7eb; }
    .stat-icon.cyan { color: #158ed5; background: #e4f5ff; }

    .stat-label { margin: 4px 0 5px; color: #51657f; font-size: 12px; }
    .stat-value { font-size: 30px; font-weight: 800; line-height: 1; color: var(--text); }
    .stat-trend { margin-top: 9px; font-size: 11px; font-weight: 800; }
    .stat-trend span { display: block; margin-top: 3px; color: #8090a4; font-weight: 500; }
    .trend-up { color: #12aa6f; }
    .trend-down { color: #ed4057; }
    .trend-flat { color: #158ed5; }

    .dashboard-grid {
      display: grid;
      grid-template-columns: minmax(0, 1.08fr) minmax(360px, 0.92fr);
      gap: 15px;
    }

    .chart-card, .activity-card {
      min-height: 477px;
      padding: 21px 22px 17px;
    }

    .section-title { margin: 0; font-size: 16px; font-weight: 800; color: var(--text); }

    .chart-area {
      height: 250px;
      display: flex;
      align-items: center;
      gap: 30px;
      margin-top: 5px;
    }

    .chart-wrapper {
      position: relative;
      width: 235px;
      height: 235px;
      flex: 0 0 235px;
    }

    .chart-center {
      position: absolute;
      inset: 0;
      display: grid;
      place-content: center;
      text-align: center;
      pointer-events: none;
    }

    .chart-total { color: var(--text); font-size: 29px; font-weight: 800; line-height: 1; }
    .chart-caption { margin-top: 4px; color: #61738c; font-size: 11px; }

    .chart-legend { display: grid; gap: 15px; flex: 1; }
    .legend-row {
      display: grid;
      grid-template-columns: 11px 1fr auto auto;
      align-items: center;
      gap: 9px;
      color: #53667f;
      font-size: 11px;
    }

    .legend-dot { width: 9px; height: 9px; border-radius: 50%; display: inline-block; }
    .legend-value { min-width: 30px; color: #172b47; font-weight: 800; text-align: right; }
    .legend-percent { min-width: 29px; color: #72859e; text-align: right; }

    .condition-summary { margin-top: 13px; }
    .condition-title { margin-bottom: 11px; color: #1c2e47; font-size: 12px; font-weight: 800; }
    .condition-bar {
      width: 100%;
      height: 15px;
      display: flex;
      overflow: hidden;
      border-radius: 99px;
      background: var(--gray-soft);
    }
    .condition-segment { height: 100%; }

    .condition-values {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      margin-top: 12px;
    }

    .condition-item { min-height: 62px; padding: 0 14px; border-right: 1px solid #edf1f5; }
    .condition-item:first-child { padding-left: 0; }
    .condition-item:last-child { padding-right: 0; border-right: 0; }
    .condition-label { display: flex; align-items: center; gap: 8px; color: #50647e; font-size: 11px; }
    .condition-number { margin-top: 8px; color: #1b2d46; font-size: 14px; font-weight: 800; }
    .condition-percent { margin-top: 4px; color: #8291a5; font-size: 10px; }

    .activity-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 4px;
    }

    .see-all {
      border: 0;
      color: #11abd2;
      background: transparent;
      font-size: 10px;
      font-weight: 800;
      cursor: pointer;
      text-decoration: none;
    }

    .activity-list { display: grid; }
    .activity-item {
      min-height: 86px;
      display: grid;
      grid-template-columns: 43px 1fr auto auto;
      align-items: center;
      gap: 11px;
      border-bottom: 1px solid #edf1f5;
    }
    .activity-item:last-child { border-bottom: 0; }

    .activity-icon {
      width: 39px;
      height: 39px;
      display: grid;
      place-items: center;
      border-radius: 50%;
      font-size: 16px;
      font-weight: 800;
    }
    .activity-icon.green { color: #12a96e; background: var(--green-soft); }
    .activity-icon.blue { color: #0e89dc; background: #e4f4ff; }
    .activity-icon.orange { color: #ee9d15; background: var(--orange-soft); }
    .activity-icon.red { color: #ef4058; background: var(--red-soft); }

    .activity-title { color: #354964; font-size: 11px; font-weight: 800; }
    .activity-subtitle { margin-top: 5px; color: #7b8ca2; font-size: 10px; }
    .activity-time { color: #7e8ea3; font-size: 9px; white-space: nowrap; }

    .activity-tag {
      min-width: 57px;
      padding: 5px 8px;
      border-radius: 20px;
      font-size: 9px;
      font-weight: 800;
      text-align: center;
    }
    .tag-green { color: #0fa76d; background: #dff7eb; }
    .tag-blue { color: #168bd9; background: #e3f3ff; }
    .tag-orange { color: #e69a12; background: #fff0d6; }
    .tag-red { color: #ec4056; background: #ffe1e6; }

    @media (max-width: 1120px) {
      .statistics { grid-template-columns: repeat(2, 1fr); }
      .dashboard-grid { grid-template-columns: 1fr; }
      .chart-card, .activity-card { min-height: auto; }
    }
    @media (max-width: 620px) {
      .statistics { grid-template-columns: 1fr; }
      .chart-area { height: auto; flex-direction: column; gap: 24px; padding-top: 8px; }
      .chart-wrapper { width: 210px; height: 210px; flex-basis: 210px; }
      .chart-legend { width: 100%; }
      .condition-values { grid-template-columns: repeat(2, 1fr); gap: 15px 0; }
      .activity-item { grid-template-columns: 40px 1fr auto; gap: 9px; }
    }
  </style>

  <!-- HEADER / TOPBAR -->
  <header class="topbar">
    <div>
      <div class="page-kicker">Dashboard</div>
      <h1 class="page-title">Dashboard</h1>
      <p class="page-subtitle">Ringkasan informasi aset dan aktivitas terbaru.</p>
    </div>

    <div class="top-meta">
      <div class="top-meta-item">
        ▣ &nbsp; {{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }}
      </div>
      <div class="top-meta-item">
        ⌖ &nbsp; Dinas Kominfo Kab. Kutai Barat
      </div>
      <div class="profile">
        <div class="profile-avatar">●</div>
        <span>{{ Auth::user()->name ?? 'Admin' }}</span>
        <span>⌄</span>
      </div>
    </div>
  </header>

  <!-- STATISTIK (4 CARDS) -->
  <section class="statistics">
    @php
      $total = $summary['total'] > 0 ? $summary['total'] : 1;
      $pctBaik = round(($summary['baik'] / $total) * 100);
      $pctRusak = round(($summary['rusak'] / $total) * 100);
    @endphp

    <article class="card stat-card">
      <div class="stat-icon blue">▦</div>
      <div>
        <div class="stat-label">Total Aset</div>
        <div class="stat-value">{{ $summary['total'] }}</div>
        <div class="stat-trend trend-up">
          ↑ 12%
          <span>dibanding bulan lalu</span>
        </div>
      </div>
    </article>

    <article class="card stat-card">
      <div class="stat-icon green">✓</div>
      <div>
        <div class="stat-label">Aset Kondisi Baik</div>
        <div class="stat-value">{{ $summary['baik'] }}</div>
        <div class="stat-trend trend-up">
          ↑ 13%
          <span>dibanding bulan lalu</span>
        </div>
      </div>
    </article>

    <article class="card stat-card">
      <div class="stat-icon red">!</div>
      <div>
        <div class="stat-label">Aset Rusak</div>
        <div class="stat-value">{{ $summary['rusak'] }}</div>
        <div class="stat-trend trend-down">
          ↓ 8%
          <span>dibanding bulan lalu</span>
        </div>
      </div>
    </article>

    <article class="card stat-card">
      <div class="stat-icon cyan">●</div>
      <div>
        <div class="stat-label">Jumlah Lokasi Aset</div>
        <div class="stat-value">{{ $summary['lokasi'] }}</div>
        <div class="stat-trend trend-flat">
          ↑ 0%
          <span>dibanding bulan lalu</span>
        </div>
      </div>
    </article>
  </section>

  <!-- DASHBOARD GRID CONTENT -->
  <section class="dashboard-grid">

    <!-- GRAFIK & STATUS KONDISI -->
    <article class="card chart-card">
      <h2 class="section-title">Grafik Kondisi Aset</h2>

      <div class="chart-area">
        <div class="chart-wrapper">
          <canvas id="conditionChart"></canvas>
          <div class="chart-center">
            <div class="chart-total">{{ $summary['total'] }}</div>
            <div class="chart-caption">Total Aset</div>
          </div>
        </div>

        <div class="chart-legend">
          <div class="legend-row">
            <span class="legend-dot" style="background:#18b77a"></span>
            <span>Baik</span>
            <strong class="legend-value">{{ $summary['baik'] }}</strong>
            <span class="legend-percent">{{ $pctBaik }}%</span>
          </div>

          <div class="legend-row">
            <span class="legend-dot" style="background:#f04458"></span>
            <span>Rusak</span>
            <strong class="legend-value">{{ $summary['rusak'] }}</strong>
            <span class="legend-percent">{{ $pctRusak }}%</span>
          </div>

          <div class="legend-row">
            <span class="legend-dot" style="background:#f5aa27"></span>
            <span>Dalam Perbaikan</span>
            <strong class="legend-value">0</strong>
            <span class="legend-percent">0%</span>
          </div>

          <div class="legend-row">
            <span class="legend-dot" style="background:#9bb0c9"></span>
            <span>Hilang</span>
            <strong class="legend-value">0</strong>
            <span class="legend-percent">0%</span>
          </div>
        </div>
      </div>

      <!-- STATUS BAR -->
      <div class="condition-summary">
        <div class="condition-title">Status Kondisi Aset</div>

        <div class="condition-bar">
          <span class="condition-segment" style="width: {{ $pctBaik }}%; background: #18b77a;"></span>
          <span class="condition-segment" style="width: {{ $pctRusak }}%; background: #f04458;"></span>
          <span class="condition-segment" style="width: 0%; background: #f5aa27;"></span>
          <span class="condition-segment" style="width: 0%; background: #9bb0c9;"></span>
        </div>

        <div class="condition-values">
          <div class="condition-item">
            <div class="condition-label">
              <span class="legend-dot" style="background:#18b77a"></span>
              Baik
            </div>
            <div class="condition-number">{{ $summary['baik'] }}</div>
            <div class="condition-percent">{{ $pctBaik }}%</div>
          </div>

          <div class="condition-item">
            <div class="condition-label">
              <span class="legend-dot" style="background:#f04458"></span>
              Rusak
            </div>
            <div class="condition-number">{{ $summary['rusak'] }}</div>
            <div class="condition-percent">{{ $pctRusak }}%</div>
          </div>

          <div class="condition-item">
            <div class="condition-label">
              <span class="legend-dot" style="background:#f5aa27"></span>
              Dalam Perbaikan
            </div>
            <div class="condition-number">0</div>
            <div class="condition-percent">0%</div>
          </div>

          <div class="condition-item">
            <div class="condition-label">
              <span class="legend-dot" style="background:#9bb0c9"></span>
              Hilang
            </div>
            <div class="condition-number">0</div>
            <div class="condition-percent">0%</div>
          </div>
        </div>
      </div>
    </article>

    <!-- AKTIVITAS TERBARU -->
    <article class="card activity-card">
      <div class="activity-header">
        <h2 class="section-title">Aktivitas Terbaru</h2>
        <a href="{{ route('exports.history') }}" class="see-all">Lihat Semua</a>
      </div>

      <div class="activity-list">
        @forelse ($activities as $activity)
          @php
            $desc = strtolower($activity->description);
            $iconClass = 'orange';
            $iconChar = '✎';
            $tagClass = 'tag-orange';
            $tagName = 'Update';

            if (str_contains($desc, 'scan') || str_contains($desc, 'pindai')) {
                $iconClass = 'green';
                $iconChar = '▦';
                $tagClass = 'tag-green';
                $tagName = 'Dipindai';
            } elseif (str_contains($desc, 'tambah') || str_contains($desc, 'baru') || str_contains($desc, 'login')) {
                $iconClass = 'blue';
                $iconChar = '+';
                $tagClass = 'tag-blue';
                $tagName = 'Sistem';
            } elseif (str_contains($desc, 'rusak')) {
                $iconClass = 'red';
                $iconChar = '!';
                $tagClass = 'tag-red';
                $tagName = 'Rusak';
            }
          @endphp

          <div class="activity-item">
            <div class="activity-icon {{ $iconClass }}">
              {{ $iconChar }}
            </div>

            <div>
              <div class="activity-title">{{ $activity->description }}</div>
              <div class="activity-subtitle">
                {{ $activity->user?->name ?? 'Admin' }} - {{ $activity->created_at->format('d M Y H:i') }}
              </div>
            </div>

            <div class="activity-time">
              {{ $activity->created_at->diffForHumans() }}
            </div>

            <div class="activity-tag {{ $tagClass }}">
              {{ $tagName }}
            </div>
          </div>
        @empty
          <div style="padding: 30px 0; text-align: center; color: #8291a5; font-size: 12px;">
            Belum ada aktivitas tercatat.
          </div>
        @endforelse
      </div>
    </article>

  </section>

  <!-- JAVASCRIPT LOGIKA CHART.JS -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const chartElement = document.getElementById("conditionChart");

      if (chartElement && typeof Chart !== "undefined") {
        new Chart(chartElement, {
          type: "doughnut",
          data: {
            labels: ["Baik", "Rusak", "Dalam Perbaikan", "Hilang"],
            datasets: [{
              data: [{{ $summary['baik'] }}, {{ $summary['rusak'] }}, 0, 0],
              backgroundColor: ["#18b77a", "#f04458", "#f5aa27", "#9bb0c9"],
              borderWidth: 0,
              hoverOffset: 3
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: "68%",
            plugins: {
              legend: { display: false }
            }
          }
        });
      }
    });
  </script>
@endsection