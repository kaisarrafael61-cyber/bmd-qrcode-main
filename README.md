# BMD QR Asset

Sistem ini digunakan untuk mengelola data aset Barang Milik Daerah (BMD), membuat QR Code untuk setiap aset, menampilkan hasil scan QR di HP, mengelola Kartu Inventaris Ruangan (KIR), serta mengekspor QR aset ke dokumen Word/ZIP beserta informasi barangnya.

Dokumentasi ini disusun sesuai alur web terbaru yang ada di project saat ini.

---

## 🚀 Fitur & Pembaruan Terbaru

1. **🎨 Perombakan UI/UX Templating Native (Clean & Responsive)**
   - Seluruh halaman utama (`Data Aset`, `Dokumen KIR`, dan `Riwayat Export`) menggunakan **CSS Native murni** dengan variabel root warna resmi Dinas Kominfo.
   - Tampilan antarmuka bersih, rapi, responsif untuk layar HP/Tablet, dan dilengkapi kartu statistik (*cards summary*) di setiap halaman.

2. **📁 Manajemen Kartu Inventaris Ruangan (KIR) & Import Excel**
   - **Import Excel KIR:** Mendukung pengunggahan data rekap inventaris ruangan langsung dari berkas Excel (misal: `Sekretariat.xlsx`).
   - **Streaming & Export PDF:** Pengguna/Admin dapat melakukan pratinjau (*stream*) PDF dokumen KIR Ruangan secara langsung di browser tanpa perlu mengunduh terlebih dahulu.
   - **Scan QR Ruangan:** Mendukung pencetakan QR khusus untuk pintu/dinding ruangan agar publik dapat langsung men-scan untuk melihat daftar aset KIR di ruangan tersebut.

3. **📦 Bulk Export Word ke ZIP yang Andal (Native Dialog)**
   - Fitur pencetakan massal QR ke berkas Word menggunakan form submit direct dengan modal `<dialog>` native HTML.
   - Dibungkus otomatis dalam arsip **ZIP** (`kodebarang-YYYYMMDD-HHMMSS.zip`).
   - Menghindari kegagalan unduh (*File System Access API Error*) dan mendukung centang massal langsung dari tabel utama `Data Aset`.

4. **📊 Halaman Riwayat Export & Audit Trail Terperinci**
   - Halaman khusus `Riwayat Export` dilengkapi statistik total ekspor, ekspor single vs massal, serta jumlah total aset yang pernah diunduh.
   - Setiap aktivitas ekspor mencatat log detail: nama file, pengguna (admin), IP address, tanggal & jam, serta daftar ringkasan aset di dalamnya.

---

## 1. Ringkasan Fungsi

Fitur utama aplikasi:

- Login admin untuk masuk ke dashboard.
- Input dan manajemen data aset BMD.
- Import data Kartu Inventaris Ruangan (KIR) dari file Excel.
- Pratinjau (*Stream*) dan unduh PDF KIR Ruangan.
- Generate QR otomatis saat aset dibuat.
- Download file QR SVG per aset.
- Export QR ke Word untuk 1 aset (`.docx`) atau beberapa aset sekaligus (`.zip`).
- Halaman publik hasil scan QR Aset & Scan QR KIR Ruangan.
- Halaman `Riwayat Export` terpisah dengan statistik dan log aktivitas lengkap.
- Log aktivitas login, logout, tambah, ubah, hapus, import KIR, dan export.

---

## 2. Alur Kerja Sistem

Alur penggunaan sistem:

1. Admin login ke sistem.
2. Admin menambahkan data aset dari form input atau mengunggah file Excel KIR Ruangan.
3. Sistem otomatis membuat QR yang mengarah ke halaman publik aset / dokumen KIR.
4. QR Aset bisa diunduh (SVG) atau diekspor ke dokumen Word/ZIP.
5. Saat QR discan dari HP, pengguna publik langsung melihat hasil scan aset / pratinjau PDF KIR dari data terbaru.
6. Jika data aset diperbarui, QR lama tetap bisa dipakai dan hasil scan akan mengikuti data terbaru.
7. Setiap tindakan export Word tercatat otomatis di halaman `Riwayat Export`.

---

## 3. Struktur Halaman Utama

Halaman yang tersedia di web:

- `Login`  
  Untuk autentikasi admin.

- `Dashboard`  
  Menampilkan ringkasan jumlah aset, kondisi aset, lokasi aset, aset terbaru, dan aktivitas umum.

- `Data Aset`  
  Menampilkan daftar aset dengan UI native baru, tombol detail, export Word single, ubah, hapus, dan dialog centang massal untuk export Word ZIP.

- `Dokumen KIR` *(Baru)*  
  Halaman pengelolaan Kartu Inventaris Ruangan, unggah file Excel, tombol preview PDF, dan cetak QR Ruangan.

- `Detail Aset`  
  Menampilkan informasi lengkap aset, QR aset, tombol kembali, tombol download QR, dan tombol export Word.

- `Tambah Aset` dan `Ubah Aset`  
  Form input dan edit data aset.

- `Riwayat Export` *(Updated UI)*  
  Halaman khusus dengan kartu statistik dan log lengkap riwayat export Word QR aset.

- `Halaman Publik Hasil Scan`  
  Halaman responsif yang tampil setelah QR Aset atau QR KIR Ruangan discan dari HP.

---

## 4. Data Aset yang Digunakan

Field utama yang dipakai dalam sistem:

- `Nama Barang`
- `Kode Barang`
- `Nomor Register`
- `Merk / Type`
- `Tahun Perolehan`
- `Kondisi`
- `Penanggung Jawab`
- `Lokasi Barang`
- `Keterangan`

Catatan penting:

- Field `Kategori` tetap ada di database untuk kebutuhan internal, tetapi pada form sekarang nilainya disamakan otomatis dari `Nama Barang` bila tidak diisi manual.
- Field `Masih Digunakan` diisi otomatis ke nilai `Ya` secara sistem.
- QR tidak dibuat ulang saat aset diupdate. Yang berubah hanya isi data yang ditampilkan saat scan.

---

## 5. Aturan Input Data

Validasi utama input aset:

- `Kode Barang` wajib diisi.
- `Nama Barang` wajib diisi.
- `Lokasi Barang` wajib diisi.
- `Kondisi` wajib diisi dan nilainya hanya:
  - `baik`
  - `rusak`
  - `perlu perbaikan`
- `Tahun Perolehan` jika diisi harus 4 digit dan berada di antara `1900` sampai `2100`.
- `Foto` opsional dan maksimal `2 MB`.

---

## 6. Cara Kerja QR

Saat aset baru disimpan:

- Sistem membuat URL publik aset dengan format rute publik.
- Sistem membuat file QR SVG dan menyimpannya ke storage publik (`storage/app/public/assets/qrcodes`).
- Sistem menyimpan:
  - `qr_code_path`
  - `qr_target_url`

Saat QR discan:

- Pengguna diarahkan ke halaman publik aset / pratinjau PDF KIR.
- Halaman scan menampilkan data aset terbaru dari database.
- Jika admin mengubah nama, lokasi, kondisi, atau keterangan, QR lama tetap valid.

---

## 7. Export QR ke Word

Flow yang dipakai sekarang:

- Export Word per aset dari halaman detail atau daftar aset (`.docx`).
- Export Word massal dari halaman data aset via dialog native / centang tabel (`.zip` berisi berkas `.docx` tiap aset).
- Dokumen Word berisi:
  - Judul QR aset
  - Nama aset
  - Kode aset dan lokasi
  - Gambar QR (PNG sementara)
  - Tabel informasi barang

Isi tabel Word:

- Nama Barang
- Kode Barang
- Nomor Register
- Merk / Type
- Tahun Perolehan
- Kondisi
- Penanggung Jawab
- Lokasi Barang
- Keterangan

Nama file export:

- Single asset: `{nama-aset} - {kode-aset} - aset-{id}.docx`
- Bulk asset archive: `kodebarang-YYYYMMDD-HHMMSS.zip`

---

## 8. Riwayat Export

Halaman `Riwayat Export` menampilkan:

- Card statistik: Total export, Export Single, Export Massal, dan Total Aset diexport.
- Waktu export (`created_at`).
- User/Admin yang melakukan export.
- IP address pengguna.
- Nama file Word / ZIP.
- Jumlah aset dan ringkasan aset.
- Tabel rincian aset (`Kode Aset`, `Nama Barang`, `Penanggung Jawab`).

Action log khusus export:

- `export_word_asset`
- `export_word_assets_bulk`

---

## 9. Hak Akses

Hak akses saat ini:

- `Admin`  
  Bisa login, tambah aset, ubah aset, hapus aset, import Excel KIR, download QR, export Word, dan melihat riwayat export.

Catatan:

- Validasi request aset hanya mengizinkan user dengan `role = admin`.
- Method `isAdmin()` ada di model `User`.

---

## 10. Route Penting

Route utama aplikasi:

### Autentikasi
- `GET /login`
- `POST /login`
- `POST /logout`

### Dashboard & Navigation
- `GET /dashboard`
- `GET /exports/history`

### Aset publik & Scan
- `GET /aset/qr/{asset}`
- `GET /aset/qr/{asset}/lookup`
- `GET /scan/ruangan/{ruangan}` *(Baru - KIR)*

### Aset admin & Export
- `GET /assets`
- `GET /assets/create`
- `POST /assets`
- `GET /assets/{asset}`
- `GET /assets/{asset}/edit`
- `PUT/PATCH /assets/{asset}`
- `DELETE /assets/{asset}`
- `GET /assets/selection`
- `GET /assets/{asset}/download`
- `GET /assets/{asset}/export-word`
- `POST /assets/export/word`
- `POST /assets/export-word-bulk` *(Baru)*

### Dokumen KIR *(Baru)*
- `GET /kir`
- `POST /kir`
- `POST /kir/import`
- `GET /kir/stream-pdf/{ruangan?}`
- `GET /kir/export-pdf/{ruangan?}`

---

## 11. Struktur Teknologi

Teknologi utama yang dipakai:

- PHP `^8.3`
- Laravel `^13.8`
- Vite
- Tailwind CSS & Native CSS Custom Variables
- `simplesoftwareio/simple-qrcode`
- `chillerlan/php-qrcode`
- `phpoffice/phpword`
- ZipArchive (PHP Extension)

Kegunaan package:

- `simple-qrcode`: Untuk membuat QR SVG utama yang disimpan ke storage publik.
- `chillerlan/php-qrcode`: Untuk membuat QR PNG sementara yang dipakai saat export Word.
- `phpoffice/phpword`: Untuk membuat file `.docx`.

---

## 12. Instalasi Project

Langkah instalasi lokal:

```bash
composer install
copy .env.example .env
php artisan key:generate
php artisan migrate
npm install
npm run build
php artisan storage:link
php artisan serve