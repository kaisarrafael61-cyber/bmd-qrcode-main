@extends('layouts.app')

@section('content')
  <!-- STYLE NATIVE DENGAN DIALOG MODAL SANGAT RINGAN -->
  <style>
    :root{--navy:#06152d;--cyan:#08c3ee;--bg:#f3f9fd;--text:#102746;--muted:#71869f;--line:#e3edf5;--green:#13b879;--red:#ef3d55;--orange:#efa91d}
    .top{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:18px}
    .crumb{color:#15a9dc;font-size:13px;font-weight:700}
    .title{font-size:30px;margin:4px 0;font-weight:800;color:var(--text)}
    .sub{font-size:13px;color:var(--muted)}
    .info{display:flex;gap:18px;color:#71869f;font-size:12px;align-items:center}
    .admin{display:flex;gap:8px;align-items:center;color:#172c48;font-weight:700}
    .admin b{background:#081a31;color:#fff;width:32px;height:32px;border-radius:50%;display:grid;place-items:center}
    
    .stats{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:15px}
    .stat,.table-card{background:#fff;border:1px solid var(--line);border-radius:14px;box-shadow:0 8px 24px #19416910}
    .stat{padding:17px;display:flex;gap:13px}
    .icon{width:49px;height:49px;border-radius:50%;display:grid;place-items:center;background:#e4f4ff;color:#128fdb;font-size:22px;font-weight:bold}
    .good .icon{background:#e0f7ee;color:var(--green)}
    .bad .icon{background:#ffe5e9;color:var(--red)}
    .repair .icon{background:#fff1d6;color:var(--orange)}
    .label{font-size:13px;color:#607791}
    .value{font-size:28px;font-weight:800;margin:2px 0;color:var(--text)}
    .up{font-size:12px;color:var(--green);font-weight:bold}
    .down{color:var(--red)}
    .note{font-size:10px;color:#9aabbd}

    .table-card{overflow:hidden}
    .thead{display:flex;justify-content:space-between;align-items:center;padding:16px 18px 11px}
    .thead h2{margin:0;font-size:16px;color:var(--text);font-weight:800}
    .tablewrap{overflow:auto}
    table{width:100%;border-collapse:collapse;min-width:900px}
    th{background:#f4f8fc;color:#58708a;font-size:11px;text-align:left;padding:10px 12px;font-weight:700}
    td{font-size:11px;color:#536c88;padding:9px 12px;border-top:1px solid #edf2f7;vertical-align:middle}
    
    .status{display:inline-block;padding:5px 11px;border-radius:999px;font-size:10px;font-weight:800}
    .s-good{background:#dff7ed;color:#0b9d66}
    .s-bad{background:#ffe4e8;color:#e3314b}
    .s-repair{background:#fff0d3;color:#d99508}
    .s-lost{background:#e8eef6;color:#7186a0}

    .act{border:1px solid #dce8f1;background:#fff;border-radius:7px;padding:5px 9px;color:#138fc0;font-size:10px;font-weight:bold;margin-right:3px;text-decoration:none;display:inline-block;cursor:pointer}
    .act-word{color:#08a0cb;background:#eef8ff;border-color:#cbe8fb}
    .act-edit{color:#d98a08;background:#fff9ee;border-color:#fce8c8}
    .act-del{color:#e3314b;background:#fff0f2;border-color:#fcc8cf}
    
    .btn-primary{background:linear-gradient(90deg,#08c3ee,#08afe1);border:1px solid #08b7e8;color:white;font-weight:700;padding:8px 14px;border-radius:9px;cursor:pointer;text-decoration:none;font-size:12px;display:inline-block}
    .btn-secondary{background:#eef8ff;border:1px solid #cbe8fb;color:#087da9;font-weight:700;padding:8px 14px;border-radius:9px;cursor:pointer;text-decoration:none;font-size:12px;display:inline-block}

    .foot{display:flex;justify-content:space-between;align-items:center;padding:13px 17px;color:#7890a7;font-size:11px;border-top:1px solid #edf2f7}

    /* CSS STYLE DIALOG MODAL NATIVE */
    dialog::backdrop { background: rgba(6, 21, 45, 0.6); backdrop-filter: blur(2px); }
    dialog[open] { display: flex; flex-direction: column; }
    .dialog-box { border: 0; border-radius: 20px; padding: 24px; width: min(650px, 92vw); max-height: 85vh; background: #fff; box-shadow: 0 10px 35px rgba(0,0,0,0.2); }
    .dialog-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #edf2f7; padding-bottom: 12px; margin-bottom: 16px; }
    .dialog-title { font-size: 18px; font-weight: 800; color: var(--text); margin: 0; }
    .dialog-close { border: 0; background: #f1f5f9; width: 30px; height: 30px; border-radius: 50%; cursor: pointer; font-weight: bold; }
    .dialog-body { overflow-y: auto; max-height: 50vh; margin-bottom: 16px; border: 1px solid #e3edf5; border-radius: 12px; padding: 12px; }
    .dialog-item { display: flex; align-items: center; justify-content: space-between; padding: 10px; border-bottom: 1px solid #edf2f6; }
    .dialog-item:last-child { border-bottom: 0; }

    @media(max-width:1100px){.stats{grid-template-columns:repeat(2,1fr)}}
    @media(max-width:800px){.info{display:none}.top{padding-right:10px}.stats{grid-template-columns:1fr}}
  </style>

  <!-- HEADER / TOPBAR -->
  <header class="top">
    <div>
      <div class="crumb">Dashboard › Data Aset</div>
      <h1 class="title">Daftar Aset BMD</h1>
      <div class="sub">Kelola seluruh data aset barang milik daerah dengan mudah dan terintegrasi.</div>
    </div>
    
    <div class="info">
      <span>▣ &nbsp; {{ \Carbon\Carbon::now()->translatedFormat('l, d M Y') }}</span>
      <span>⌖ &nbsp; Dinas Kominfo Kab. Kutai Barat</span>
      <div class="admin">
        <b>A</b> Admin ⌄
      </div>
    </div>
  </header>

  <!-- STATISTIK (CARDS) -->
  <section class="stats">
    <div class="stat">
      <div class="icon">▤</div>
      <div>
        <div class="label">Total Aset</div>
        <div class="value">{{ $assets->total() }}</div>
        <div class="up">↑ 12%</div>
        <div class="note">dibanding bulan lalu</div>
      </div>
    </div>

    <div class="stat good">
      <div class="icon">✓</div>
      <div>
        <div class="label">Aset Baik</div>
        <div class="value">{{ $assets->where('condition', 'baik')->count() }}</div>
        <div class="up">↑ 15%</div>
        <div class="note">dibanding bulan lalu</div>
      </div>
    </div>

    <div class="stat bad">
      <div class="icon">!</div>
      <div>
        <div class="label">Aset Rusak</div>
        <div class="value">{{ $assets->where('condition', 'rusak')->count() }}</div>
        <div class="up down">↓ 8%</div>
        <div class="note">dibanding bulan lalu</div>
      </div>
    </div>

    <div class="stat repair">
      <div class="icon">◷</div>
      <div>
        <div class="label">Dalam Perbaikan</div>
        <div class="value">0</div>
        <div class="up">↑ 0%</div>
        <div class="note">dibanding bulan lalu</div>
      </div>
    </div>
  </section>

  <!-- TABEL UTAMA DATA ASET -->
  <section class="table-card">
    <div class="thead">
      <h2>Daftar Aset</h2>
      @if (auth()->user()->isAdmin())
        <div style="display:flex; gap:8px;">
          <button type="button" onclick="document.getElementById('dialog-export-qr').showModal()" class="btn-secondary">Export QR ke Word</button>
          <a href="{{ route('assets.create') }}" class="btn-primary">＋ Tambah Aset Baru</a>
        </div>
      @endif
    </div>

    <div class="tablewrap">
      <table>
        <thead>
          <tr>
            <th>Kode Aset</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Lokasi</th>
            <th>Kondisi</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($assets as $asset)
            @php
              $cond = strtolower($asset->condition);
              $badgeClass = $cond == 'baik' ? 's-good' : ($cond == 'rusak' ? 's-bad' : ($cond == 'dalam perbaikan' ? 's-repair' : 's-lost'));
            @endphp
            <tr>
              <td><b>{{ $asset->asset_code }}</b></td>
              <td>{{ $asset->name }}</td>
              <td>{{ $asset->category }}</td>
              <td>{{ $asset->location }}</td>
              <td><span class="status {{ $badgeClass }}">{{ ucfirst($asset->condition) }}</span></td>
              <td>
                <div style="display:flex; align-items:center;">
                  <a href="{{ route('assets.show', $asset) }}" class="act">◉ Detail</a>
                  @if (auth()->user()->isAdmin())
                    <a href="{{ route('assets.export.word', $asset) }}" class="act act-word">Export Word</a>
                    <a href="{{ route('assets.edit', $asset) }}" class="act act-edit">✎ Ubah</a>
                    <form method="POST" action="{{ route('assets.destroy', $asset) }}" style="display:inline;" data-confirm-delete data-asset-label="{{ $asset->asset_code }} - {{ $asset->name }}">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="act act-del">Hapus</button>
                    </form>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="6" style="text-align:center; padding:30px;">Belum ada data aset yang tersimpan.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="foot">
      <div>Menampilkan data aset terdaftar</div>
      <div>
        {{ $assets->links() }}
      </div>
    </div>
  </section>

  <!-- MODAL DIALOG NATIVE EXPORT MASSAL (LANGSUNG KELUAR DAN BISA DIGUNAKAN) -->
  @if (auth()->user()->isAdmin())
    <dialog id="dialog-export-qr" class="dialog-box">
      <div class="dialog-header">
        <h3 class="dialog-title">Pilih Aset yang Mau Diexport</h3>
        <button type="button" class="dialog-close" onclick="document.getElementById('dialog-export-qr').close()">×</button>
      </div>

      <form action="{{ Route::has('assets.export.bulk') ? route('assets.export.bulk') : url('/assets/export-word-bulk') }}" method="POST">
        @csrf
        
        <div style="margin-bottom: 12px; display: flex; justify-content: space-between; align-items: center; font-size: 12px; color: #58708a;">
          <label style="cursor: pointer; font-weight: bold; display: flex; align-items: center; gap: 6px;">
            <input type="checkbox" id="modal-check-all"> Pilih Semua Aset di Halaman Ini
          </label>
        </div>

        <div class="dialog-body">
          @foreach ($assets as $asset)
            <div class="dialog-item">
              <div>
                <strong style="color: #102746; font-size: 12px; display: block;">{{ $asset->asset_code }}</strong>
                <span style="color: #71869f; font-size: 11px;">{{ $asset->name }} — {{ $asset->location }}</span>
              </div>
              <input type="checkbox" name="asset_ids[]" value="{{ $asset->id }}" class="modal-asset-cb" style="width: 18px; height: 18px; cursor: pointer;">
            </div>
          @endforeach
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 8px;">
          <button type="button" onclick="document.getElementById('dialog-export-qr').close()" class="act" style="padding: 10px 16px; font-size: 12px;">Batal</button>
          <button type="submit" class="btn-primary" style="padding: 10px 16px; font-size: 12px;">Download Word Aset Terpilih</button>
        </div>
      </form>
    </dialog>

    <script>
      // SCRIPT SEDERHANA CENTANG SEMUA DI MODAL DIALOG
      document.getElementById('modal-check-all')?.addEventListener('change', function() {
        document.querySelectorAll('.modal-asset-cb').forEach(cb => cb.checked = this.checked);
      });
    </script>
  @endif
@endsection