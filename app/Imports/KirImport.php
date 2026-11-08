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
        $jenisBarang     = trim($row[1] ?? '');  // Kolom B (Jenis Barang)
        $merkModel       = trim($row[2] ?? '');  // Kolom C (Merk/Model)
        $noSeri          = trim($row[3] ?? '');  // Kolom D (No Seri Pabrik)
        $ukuran          = trim($row[4] ?? '');  // Kolom E (Ukuran)
        $bahan           = trim($row[5] ?? '');  // Kolom F (Bahan)
        $tahunPembuatan  = trim($row[6] ?? '');  // Kolom G (Tahun Pembuatan)
        $kodeBarang      = trim($row[7] ?? '');  // Kolom H (No Kode Barang)
        $jumlahRegister  = trim($row[8] ?? '');  // Kolom I (Jumlah/Register)
        $caraPerolehan   = trim($row[9] ?? '');  // Kolom J (Asal/Cara Perolehan)
        
        // Membaca 3 sub-kolom Keadaan Barang di Excel (Baik, Kurang Baik, Rusak Berat)
        $rawBaik         = trim($row[10] ?? ''); // Kolom K: Baik (B)
        $rawKb           = trim($row[11] ?? ''); // Kolom L: Kurang Baik (KB)
        $rawRb           = trim($row[12] ?? ''); // Kolom M: Rusak Berat (RB)
        $keterangan      = trim($row[13] ?? ''); // Kolom N / Indeks 13 (Keterangan)

        // Deteksi Kondisi Secara Akurat dari 3 Kolom Keadaan Barang
        $kondisiNormalized = 'baik';
        $keadaanBarang     = 'Baik';

        if (!empty($rawRb) || stripos($rawRb, 'rusak') !== false || stripos($rawRb, 'rb') !== false) {
            $kondisiNormalized = 'rusak';
            $keadaanBarang     = $rawRb === '1 Rusak Berat' || $rawRb === 'Rusak Berat' ? $rawRb : 'Rusak Berat';
        } elseif (!empty($rawKb) || stripos($rawKb, 'kurang') !== false || stripos($rawKb, 'kb') !== false) {
            $kondisiNormalized = 'rusak';
            $keadaanBarang     = $rawKb ?: 'Kurang Baik';
        } elseif (!empty($rawBaik) && (stripos($rawBaik, 'rusak') !== false || stripos($rawBaik, 'rb') !== false || stripos($rawBaik, 'kb') !== false)) {
            $kondisiNormalized = 'rusak';
            $keadaanBarang     = $rawBaik;
        } else {
            $keadaanBarang     = $rawBaik ?: 'Baik';
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
            'keadaan_barang'   => $keadaanBarang,
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