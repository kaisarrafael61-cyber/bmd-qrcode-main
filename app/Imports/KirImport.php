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
     * Mulai membaca data dari baris ke-13 (di bawah header tabel Excel)
     */
    public function startRow(): int
    {
        return 13;
    }

    public function model(array $row): ?Model
    {
        // Abaikan jika nama barang (Kolom B / Index 1) kosong
        if (empty($row[1])) {
            return null;
        }

        // Pemetaan Kolom Excel yang Akurat
        $jenisBarang     = trim($row[1] ?? '');  // Kolom B
        $merkModel       = trim($row[2] ?? '');  // Kolom C
        $noSeri          = trim($row[3] ?? '');  // Kolom D
        $ukuran          = trim($row[4] ?? '');  // Kolom E
        $bahan           = trim($row[5] ?? '');  // Kolom F
        $tahunPembuatan  = trim($row[6] ?? '');  // Kolom G
        $kodeBarang      = trim($row[7] ?? '');  // Kolom H
        $jumlahRegister  = trim($row[8] ?? '');  // Kolom I
        $caraPerolehan   = trim($row[9] ?? '');  // Kolom J
        $rawKondisi      = strtolower(trim($row[10] ?? 'baik')); // Kolom K
        $keterangan      = trim($row[14] ?? ''); // Kolom O

        // Normalisasi kondisi untuk status dashboard ('baik' atau 'rusak')
        $kondisiNormalized = 'baik';
        if (str_contains($rawKondisi, 'rusak') || str_contains($rawKondisi, 'rb') || str_contains($rawKondisi, 'kb')) {
            $kondisiNormalized = 'rusak';
        }

        // 1. Simpan ke Tabel KIR
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
            'cara_perolehan'   => $caraPerolehan ?: null,
            'keadaan_barang'   => ucfirst($kondisiNormalized),
            'keterangan'       => $keterangan ?: null,
        ]);

        // 2. Otomatis Masuk ke Tabel Assets
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