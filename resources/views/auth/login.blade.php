<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login BMD QR Asset</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('branding/logo-kominfo-kubar.jpeg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Library HTML5 QR Code Scanner -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</head>
<body class="min-h-screen bg-slate-950 text-white font-sans">
    <div class="flex min-h-screen items-center justify-center px-3 py-4 sm:px-4 sm:py-10">
        <div class="grid w-full max-w-6xl overflow-hidden rounded-[1.8rem] bg-slate-900 shadow-2xl lg:grid-cols-[1.05fr_0.95fr]">
            
            <!-- Panel Informasi Kiri -->
            <section class="bg-[radial-gradient(circle_at_top_left,_rgba(34,211,238,0.35),_transparent_35%),linear-gradient(135deg,_#082f49,_#020617)] p-6 sm:p-10">
                <p class="text-sm uppercase tracking-[0.4em] text-cyan-200">DINAS KOMINFO</p>
                <div class="mt-4 flex items-center gap-4">
                    @include('partials.kominfo-logo', ['size' => 'h-16 w-16 sm:h-20 sm:w-20', 'alt' => 'Logo Kominfo', 'class' => 'rounded-full bg-white p-1.5'])
                    <div class="h-px flex-1 bg-white/15"></div>
                </div>
                <h1 class="mt-4 max-w-md text-3xl font-semibold leading-tight sm:mt-6 sm:text-4xl">Aplikasi Manajemen Aset BMD berbasis QR Code.</h1>
                <p class="mt-4 max-w-lg text-slate-300">Input barang, buat QR otomatis, tempel di aset, scan, lalu tampilkan detail barang secara cepat dan rapi.</p>
                
                <div class="mt-8 grid gap-3 sm:mt-10 sm:grid-cols-3 sm:gap-4">
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-2xl font-semibold">1</p>
                        <p class="mt-2 text-sm text-slate-300">Tambah data aset</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-2xl font-semibold">2</p>
                        <p class="mt-2 text-sm text-slate-300">Generate & cetak QR</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-2xl font-semibold">3</p>
                        <p class="mt-2 text-sm text-slate-300">Scan untuk lihat detail</p>
                    </div>
                </div>
                <p class="mt-8 border-t border-white/15 pt-4 text-xs leading-6 text-cyan-100/75">© {{ now()->year }} DISKOMINFO Kabupaten Kutai Barat.</p>
            </section>

            <!-- Panel Form Login & Scanner Kanan -->
            <section class="space-y-4 bg-slate-900 p-4 sm:space-y-6 sm:p-10">
                
                <!-- Box Form Login -->
                <div class="rounded-3xl border border-slate-800 bg-slate-950/70 p-6">
                    <h2 class="text-2xl font-semibold">Login Admin</h2>
                    <p class="mt-2 text-sm text-slate-400">Masuk untuk menambahkan barang, membuat QR, dan mencetak barcode.</p>

                    <form method="POST" action="{{ route('login.store') }}" class="mt-8 space-y-5">
                        @csrf
                        <div>
                            <label for="email" class="mb-2 block text-sm text-slate-300">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="username" maxlength="255" class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 outline-none ring-0 focus:border-cyan-400" required>
                            @error('email')
                                <p class="mt-2 text-sm text-rose-400">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="password" class="mb-2 block text-sm text-slate-300">Password</label>
                            <input id="password" name="password" type="password" autocomplete="current-password" class="w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 outline-none ring-0 focus:border-cyan-400" required>
                        </div>
                        <button type="submit" class="w-full rounded-2xl bg-cyan-400 px-4 py-3 font-semibold text-slate-950 transition hover:bg-cyan-300">Masuk ke Dashboard</button>
                    </form>
                </div>

                <!-- Box Scanner Kamera -->
                <div class="rounded-3xl border border-cyan-400/20 bg-slate-950/70 p-6">
                    <div class="flex flex-col gap-4">
                        <div class="min-w-0">
                            <h3 class="text-xl font-semibold">Scan Barcode / QR</h3>
                            <p class="mt-2 text-sm leading-7 text-slate-400">Arahkan kamera ke barcode yang dibuat admin. Detail barang akan langsung muncul secara otomatis.</p>
                        </div>
                        <button type="button" id="start-scanner" onclick="toggleScanner()" class="w-full touch-manipulation rounded-2xl bg-cyan-400 px-4 py-4 text-base font-semibold text-slate-950 transition hover:bg-cyan-300 active:scale-[0.99]">
                            Mulai Scan Sekarang
                        </button>
                    </div>

                    <div id="asset-scanner" class="mt-5 hidden">
                        <div id="reader" class="overflow-hidden rounded-3xl border border-slate-800 bg-black min-h-[220px]"></div>
                        <p id="scanner-status" class="mt-3 text-sm leading-7 text-slate-400">Mengaktifkan kamera...</p>
                        <p id="scanner-help" class="mt-2 text-xs leading-6 text-slate-500">Jika kamera HP tidak mau terbuka, pastikan izin kamera telah diperbolehkan di browser.</p>
                    </div>
                </div>

            </section>
        </div>
    </div>

    <!-- Modal Detail Aset (Pop-up Fallback) -->
    <div id="asset-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/85 px-3 py-4 sm:px-4">
        <div class="max-h-[94vh] w-full max-w-4xl overflow-y-auto rounded-[2rem] border border-cyan-300/20 bg-[radial-gradient(circle_at_top_right,_rgba(56,189,248,0.14),_transparent_28%),linear-gradient(180deg,_#0f172a,_#111c34)] p-4 text-white shadow-[0_24px_80px_rgba(2,12,27,0.6)] sm:p-6">
            <div class="rounded-[1.7rem] border border-cyan-300/15 bg-slate-950/35 p-4 sm:p-6">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                    <div class="min-w-0">
                        <div class="flex items-center gap-3">
                            @include('partials.kominfo-logo', ['size' => 'h-12 w-12 sm:h-14 sm:w-14', 'alt' => 'Logo Kominfo', 'class' => 'rounded-full bg-white p-1.5 shadow-lg shadow-cyan-400/10'])
                            <div class="min-w-0">
                                <p class="text-[11px] uppercase tracking-[0.45em] text-sky-300">Detail Hasil Scan</p>
                                <h3 id="modal-name" class="mt-2 break-words text-2xl font-semibold tracking-tight text-white sm:text-4xl"></h3>
                                <p id="modal-code" class="mt-2 break-words text-base text-slate-300 sm:text-lg"></p>
                            </div>
                        </div>
                        <div class="mt-4 inline-flex items-center gap-2 rounded-full border border-cyan-300/20 bg-cyan-400/10 px-3 py-2 text-xs font-medium text-cyan-100 sm:text-sm">
                            <span class="inline-flex h-2.5 w-2.5 rounded-full bg-cyan-300 shadow-[0_0_12px_rgba(103,232,249,0.9)]"></span>
                            Data ini tampil langsung dari informasi aset terbaru
                        </div>
                    </div>
                    <div class="flex flex-col gap-3 sm:items-end">
                        <button type="button" onclick="closeModal()" class="rounded-2xl border border-sky-300/15 bg-sky-400/10 px-5 py-3 text-sm font-semibold text-sky-100 transition hover:border-sky-300/35 hover:bg-sky-400/15">Kembali</button>
                    </div>
                </div>

                <div class="mt-6 grid gap-5 xl:grid-cols-[1.2fr_0.8fr]">
                    <section class="rounded-[1.7rem] border border-cyan-300/12 bg-white/[0.04] p-4 shadow-inner shadow-cyan-950/20 sm:p-6">
                        <div class="mb-5 flex items-center justify-between gap-3 border-b border-cyan-300/10 pb-4">
                            <div>
                                <p class="text-sm font-semibold text-white sm:text-base">Informasi Barang</p>
                                <p class="mt-1 text-xs leading-6 text-slate-400 sm:text-sm">Disusun lebih ringkas agar jelas dilihat saat scan dari HP.</p>
                            </div>
                        </div>

                        <dl class="grid gap-3 sm:grid-cols-2 sm:gap-4">
                            <div class="rounded-2xl border border-white/6 bg-slate-900/55 px-4 py-3.5">
                                <dt class="text-xs font-medium uppercase tracking-[0.24em] text-sky-200/80">Kode Barang</dt>
                                <dd id="modal-asset-code" class="mt-2 break-words text-lg font-semibold text-white sm:text-xl"></dd>
                            </div>
                            <div class="rounded-2xl border border-white/6 bg-slate-900/55 px-4 py-3.5">
                                <dt class="text-xs font-medium uppercase tracking-[0.24em] text-sky-200/80">Nomor Register</dt>
                                <dd id="modal-register-number" class="mt-2 break-words text-lg font-semibold text-white sm:text-xl"></dd>
                            </div>
                            <div class="rounded-2xl border border-white/6 bg-slate-900/55 px-4 py-3.5">
                                <dt class="text-xs font-medium uppercase tracking-[0.24em] text-sky-200/80">Merk / Type</dt>
                                <dd id="modal-brand" class="mt-2 break-words text-lg font-semibold text-white sm:text-xl"></dd>
                            </div>
                            <div class="rounded-2xl border border-white/6 bg-slate-900/55 px-4 py-3.5">
                                <dt class="text-xs font-medium uppercase tracking-[0.24em] text-sky-200/80">Tahun Perolehan</dt>
                                <dd id="modal-year" class="mt-2 break-words text-lg font-semibold text-white sm:text-xl"></dd>
                            </div>
                            <div class="rounded-2xl border border-white/6 bg-slate-900/55 px-4 py-3.5">
                                <dt class="text-xs font-medium uppercase tracking-[0.24em] text-sky-200/80">Penanggung Jawab</dt>
                                <dd id="modal-person-in-charge" class="mt-2 break-words text-lg font-semibold text-white sm:text-xl"></dd>
                            </div>
                            <div class="rounded-2xl border border-white/6 bg-slate-900/55 px-4 py-3.5">
                                <dt class="text-xs font-medium uppercase tracking-[0.24em] text-sky-200/80">Kondisi</dt>
                                <dd id="modal-condition" class="mt-2 break-words text-lg font-semibold capitalize text-white sm:text-xl"></dd>
                            </div>
                            <div class="rounded-2xl border border-white/6 bg-slate-900/55 px-4 py-3.5 sm:col-span-2">
                                <dt class="text-xs font-medium uppercase tracking-[0.24em] text-sky-200/80">Lokasi Barang</dt>
                                <dd id="modal-location" class="mt-2 break-words text-lg font-semibold text-white sm:text-xl"></dd>
                            </div>
                            <div class="rounded-2xl border border-white/6 bg-slate-900/55 px-4 py-3.5 sm:col-span-2">
                                <dt class="text-xs font-medium uppercase tracking-[0.24em] text-sky-200/80">Keterangan</dt>
                                <dd id="modal-description" class="mt-2 break-words text-base leading-7 text-slate-100 sm:text-lg"></dd>
                            </div>
                        </dl>
                    </section>

                    <section class="rounded-[1.7rem] border border-cyan-300/12 bg-white/[0.04] p-4 shadow-inner shadow-cyan-950/20 sm:p-6">
                        <div class="mb-4 flex items-center gap-3">
                            <div>
                                <p class="text-sm font-semibold text-white sm:text-base">Visual Barang</p>
                                <p class="text-xs leading-6 text-slate-400 sm:text-sm">Foto barang akan ditampilkan di sini jika tersedia.</p>
                            </div>
                        </div>

                        <div id="modal-photo-empty" class="flex min-h-48 flex-col items-center justify-center rounded-[1.6rem] border border-dashed border-cyan-300/20 bg-slate-950/35 px-6 py-8 text-center sm:min-h-56">
                            <p class="text-base font-semibold text-white sm:text-lg">Foto barang belum tersedia</p>
                            <p class="mt-1 max-w-xs text-sm leading-6 text-slate-400">Detail informasi aset tetap dapat dilihat.</p>
                        </div>
                        <img id="modal-photo" src="" alt="" class="hidden h-64 w-full rounded-[1.6rem] border border-cyan-300/14 object-cover sm:h-80">
                    </section>
                </div>
            </div>
        </div>
    </div>

    <!-- Script Logika Scanner Cerdas -->
    <script>
        let html5QrCode = null;
        let isScanning = false;

        function toggleScanner() {
            if (isScanning) {
                stopScanner();
            } else {
                startScanner();
            }
        }

        function startScanner() {
            const container = document.getElementById('asset-scanner');
            const btn = document.getElementById('start-scanner');
            const status = document.getElementById('scanner-status');

            container.classList.remove('hidden');
            status.innerText = 'Mengaktifkan kamera...';

            html5QrCode = new Html5Qrcode("reader");
            
            const config = { fps: 10, qrbox: { width: 220, height: 220 } };

            html5QrCode.start(
                { facingMode: "environment" }, 
                config, 
                onScanSuccess,
                onScanFailure
            ).then(() => {
                isScanning = true;
                btn.innerText = "Hentikan Scan";
                btn.classList.replace('bg-cyan-400', 'bg-rose-600');
                btn.classList.replace('hover:bg-cyan-300', 'hover:bg-rose-500');
                btn.classList.replace('text-slate-950', 'text-white');
                status.innerText = 'Arahkan kamera ke QR Code / Barcode aset...';
            }).catch(err => {
                console.error("Gagal mengaktifkan kamera:", err);
                status.innerText = 'Kamera gagal diakses atau izin ditolak.';
            });
        }

        function stopScanner() {
            if (html5QrCode && isScanning) {
                html5QrCode.stop().then(() => {
                    isScanning = false;
                    resetScannerUI();
                }).catch(err => console.error("Gagal menghentikan scanner:", err));
            }
        }

        function resetScannerUI() {
            const container = document.getElementById('asset-scanner');
            const btn = document.getElementById('start-scanner');

            container.classList.add('hidden');
            btn.innerText = "Mulai Scan Sekarang";
            btn.classList.replace('bg-rose-600', 'bg-cyan-400');
            btn.classList.replace('hover:bg-rose-500', 'hover:bg-cyan-300');
            btn.classList.replace('text-white', 'text-slate-950');
        }

        function onScanSuccess(decodedText) {
            stopScanner();

            let rawText = decodedText.trim();
            console.log("QR Terbaca di Login:", rawText);

            // 1. JIKA HASIL SCAN BERUPA URL (Termasuk: https://.../aset/qr/244 atau /assets/244)
            if (rawText.startsWith('http://') || rawText.startsWith('https://')) {
                try {
                    const urlObj = new URL(rawText);
                    const pathSegments = urlObj.pathname.split('/').filter(Boolean);
                    const idAtauKode = pathSegments[pathSegments.length - 1]; // Ekstrak ID paling ujung (misal: 244)

                    // Jika QR mengarah ke aset, REDIRECT LANGSUNG KE ROUTE VALID: /aset/qr/{id}
                    if (urlObj.pathname.includes('/aset/') || urlObj.pathname.includes('/assets/')) {
                        window.location.href = `/aset/qr/${idAtauKode}`;
                        return;
                    }

                    // Jika link dokumen KIR/PDF
                    window.location.href = urlObj.pathname;
                    return;
                } catch (e) {
                    console.error("Gagal parsing URL:", e);
                    window.location.href = rawText;
                    return;
                }
            }

            // 2. JIKA HASIL SCAN BERUPA ID ATAU KODE BARANG BIASA
            window.location.href = `/aset/qr/${encodeURIComponent(rawText)}`;
        }

        function onScanFailure(error) {
            // Abaikan kesalahan per frame
        }

        function closeModal() {
            document.getElementById('asset-modal').classList.add('hidden');
        }
    </script>
</body>
</html>