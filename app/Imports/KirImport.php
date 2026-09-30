<?php

namespace App\Imports;

use App\Models\Kir;
use App\Models\Asset;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class KirImport implements ToModel, WithStartRow
{
    /**
     * Mulai membaca data dari baris ke-10 (mengabaikan header judul)
     */
    public function startRow(): int
    {
        return 10;
    }

    public function model(array $row): ?Model
    {
        // Abaikan jika kolom nama barang (indeks 2) kosong
        if (empty($row[2])) {
            return null;
        }

        $jenisBarang    = trim($row[2]);               // Kolom C
        $merkModel      = trim($row[8] ?? '');         // Kolom I
        $noSeri         = trim($row[10] ?? '');        // Kolom K
        $ukuran         = trim($row[11] ?? '');        // Kolom L
        $bahan          = trim($row[12] ?? '');        // Kolom M
        $tahunPembuatan = trim($row[14] ?? '');        // Kolom O
        $kodeBarang     = trim($row[15] ?? '');        // Kolom P
        $jumlahRegister = trim($row[17] ?? '');        // Kolom R
        $rawKondisi     = strtolower(trim($row[20] ?? 'baik')); // Kolom U
        $keterangan     = trim($row[24] ?? '');        // Kolom Y

        // Normalisasi kondisi untuk status dashboard ('baik' atau 'rusak')
        $kondisiNormalized = 'baik';
        if (str_contains($rawKondisi, 'rusak') || str_contains($rawKondisi, 'rb') || str_contains($rawKondisi, 'kb')) {
            $kondisiNormalized = 'rusak';
        }

        // 1. Simpan ke Tabel KIR (Untuk Laporan PDF Dokumentasi Ruangan)
        $kir = Kir::create([
            'ruangan'          => 'Sekretariat',
            'jenis_barang'     => $jenisBarang,
            'merk_model'       => $merkModel ?: null,
            'no_seri'          => $noSeri ?: null,
            'ukuran'           => $ukuran ?: null,
            'bahan'            => $bahan ?: null,
            'tahun_pembuatan'  => $tahunPembuatan ?: null,
            'no_kode_barang'   => $kodeBarang ?: null,
            'jumlah_register'  => $jumlahRegister ?: null,
            'keadaan_barang'   => ucfirst($kondisiNormalized),
            'keterangan'       => $keterangan ?: null,
        ]);

        // 2. Otomatis Masuk ke Tabel Assets (Biar Muncul di Data Aset & Dashboard)
        $asset = Asset::create([
            'asset_code'       => $kodeBarang ?: 'KODE-'.uniqid(),
            'register_number'  => $jumlahRegister ?: '-',
            'name'             => $jenisBarang,
            'category'         => 'Inventaris Ruangan',
            'brand'            => $merkModel ?: '-',
            'year_acquired'    => is_numeric($tahunPembuatan) ? (int)$tahunPembuatan : date('Y'),
            'location'         => 'Sekretariat',
            'person_in_charge' => 'Sekretariat',
            'condition'        => $kondisiNormalized,
            'description'      => $keterangan ?: 'Hasil import dari Excel KIR Sekretariat',
            'created_by'       => Auth::id() ?? 1,
            'updated_by'       => Auth::id() ?? 1,
            'qr_code_path'     => '',
            'qr_target_url'    => '',
        ]);

        // 3. Generate QR Code Khusus Per-Barang
        $targetUrl = route('assets.public.show', $asset);
        $qrPath    = 'assets/qrcodes/asset-'.$asset->id.'.svg';
        $qrSvg     = QrCode::format('svg')->size(280)->margin(1)->generate($targetUrl);

        Storage::disk('public')->put($qrPath, $qrSvg);

        $asset->update([
            'qr_code_path'  => $qrPath,
            'qr_target_url' => $targetUrl,
        ]);

        return $kir;
    }
}