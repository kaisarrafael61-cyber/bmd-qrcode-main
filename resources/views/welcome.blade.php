<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'BMD QR Asset') }}</title>
        <link rel="icon" type="image/jpeg" href="{{ asset('branding/logo-kominfo-kubar.jpeg') }}">

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <!-- Library HTML5 QR Code Scanner -->
        <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    </head>
    <body class="min-h-screen bg-slate-950 text-slate-100 flex flex-col justify-between p-4 lg:p-8 font-sans">
        
        <!-- Header / Navbar Top -->
        <header class="w-full max-w-5xl mx-auto flex items-center justify-between py-4">
            <div class="flex items-center gap-3">
                @include('partials.kominfo-logo', ['size' => 'h-10 w-10', 'alt' => 'Logo Kominfo', 'class' => 'rounded-full bg-white p-1'])
                <div>
                    <p class="text-[10px] uppercase tracking-[0.25em] text-cyan-400 font-semibold">DINAS KOMINFO</p>
                    <h1 class="text-lg font-bold leading-none text-white">BMD QR Asset</h1>
                </div>
            </div>

            @if (Route::has('login'))
                <nav class="flex items-center gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="rounded-xl bg-cyan-400 px-5 py-2 text-sm font-semibold text-slate-950 hover:bg-cyan-300 transition">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="rounded-xl border border-slate-700 bg-slate-900 px-5 py-2 text-sm font-medium text-slate-200 hover:bg-slate-800 transition">
                            Login Admin
                        </a>
                    @endauth
                </nav>
            @endif
        </header>

        <!-- Main Content Grid -->
        <main class="w-full max-w-5xl mx-auto my-auto grid grid-cols-1 lg:grid-cols-2 gap-8 items-center py-6">
            
            <!-- Informasi Aplikasi (Kiri) -->
            <div class="space-y-6">
                <div>
                    <span class="inline-block px-3 py-1 text-xs font-semibold uppercase tracking-wider text-cyan-300 bg-cyan-950/60 border border-cyan-800 rounded-full mb-3">
                        Sistem Informasi Inventaris
                    </span>
                    <h2 class="text-3xl lg:text-4xl font-extrabold text-white leading-tight">
                        Manajemen Aset BMD Berbasis QR Code
                    </h2>
                    <p class="mt-3 text-slate-400 text-sm leading-relaxed">
                        Pindai QR Code pada stiker aset untuk melihat informasi detail barang secara cepat, akurat, dan transparan.
                    </p>
                </div>

                <div class="space-y-3">
                    <div class="flex items-start gap-3 p-3.5 rounded-2xl bg-slate-900/80 border border-slate-800">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-cyan-500/10 text-cyan-400 font-bold text-sm">1</div>
                        <div>
                            <p class="text-sm font-semibold text-slate-200">Arahkan Kamera</p>
                            <p class="text-xs text-slate-400">Tekan tombol scan dan arahkan kamera HP ke QR Code aset.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3.5 rounded-2xl bg-slate-900/80 border border-slate-800">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-xl bg-cyan-500/10 text-cyan-400 font-bold text-sm">2</div>
                        <div>
                            <p class="text-sm font-semibold text-slate-200">Pindai Otomatis</p>
                            <p class="text-xs text-slate-400">Sistem akan secara otomatis mengekstrak data aset yang terpindai.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Box Scanner Kamera (Kanan) -->
            <div class="w-full rounded-[2rem] bg-slate-900 border border-slate-800 p-6 shadow-2xl space-y-4">
                <div class="text-center space-y-1">
                    <h3 class="text-xl font-bold text-white">Scan Barcode / QR</h3>
                    <p class="text-xs text-slate-400">Arahkan kamera ke QR Code yang terpasang pada barang.</p>
                </div>

                <!-- Reader Area -->
                <div class="relative overflow-hidden rounded-2xl bg-slate-950 border border-slate-800 min-h-[260px] flex items-center justify-center">
                    <div id="reader" class="w-full"></div>
                    <div id="scanner-placeholder" class="absolute inset-0 flex flex-col items-center justify-center p-4 text-center">
                        <svg class="w-12 h-12 text-slate-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m0 14v1m8-8h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <p class="text-xs text-slate-500">Kamera belum diaktifkan</p>
                    </div>
                </div>

                <!-- Controls & Status -->
                <div class="space-y-3">
                    <button type="button" id="btn-toggle-scan" onclick="toggleScanner()" class="w-full py-3 px-4 rounded-xl bg-cyan-400 hover:bg-cyan-300 text-slate-950 font-semibold text-sm transition">
                        Mulai Scan Sekarang
                    </button>
                    
                    <p id="scan-status" class="text-center text-xs text-slate-400 hidden"></p>
                </div>
            </div>

        </main>

        <!-- Footer -->
        <footer class="w-full max-w-5xl mx-auto text-center py-4 text-xs text-slate-500">
            © {{ date('Y') }} DISKOMINFO Kabupaten Kutai Barat.
        </footer>

        <!-- Modal Detail Aset (Pop-up) -->
        <div id="asset-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/80 p-4">
            <div class="w-full max-w-md rounded-[2rem] bg-slate-900 border border-slate-800 p-6 text-slate-100 shadow-2xl space-y-4">
                <div class="flex items-center justify-between border-b border-slate-800 pb-3">
                    <div>
                        <p class="text-[10px] uppercase tracking-wider text-cyan-400 font-semibold">Detail Aset Terpindai</p>
                        <h4 id="modal-asset-name" class="text-lg font-bold text-white">Nama Aset</h4>
                    </div>
                    <button onclick="closeModal()" class="text-slate-400 hover:text-white p-1">&times;</button>
                </div>

                <div class="space-y-2 text-xs">
                    <div class="grid grid-cols-3 py-1 border-b border-slate-800/60">
                        <span class="text-slate-400">Kode Barang</span>
                        <span id="modal-asset-code" class="col-span-2 font-medium text-slate-200">-</span>
                    </div>
                    <div class="grid grid-cols-3 py-1 border-b border-slate-800/60">
                        <span class="text-slate-400">Nomor Register</span>
                        <span id="modal-register-number" class="col-span-2 font-medium text-slate-200">-</span>
                    </div>
                    <div class="grid grid-cols-3 py-1 border-b border-slate-800/60">
                        <span class="text-slate-400">Merk / Type</span>
                        <span id="modal-brand" class="col-span-2 font-medium text-slate-200">-</span>
                    </div>
                    <div class="grid grid-cols-3 py-1 border-b border-slate-800/60">
                        <span class="text-slate-400">Lokasi</span>
                        <span id="modal-location" class="col-span-2 font-medium text-slate-200">-</span>
                    </div>
                    <div class="grid grid-cols-3 py-1 border-b border-slate-800/60">
                        <span class="text-slate-400">Penanggung Jawab</span>
                        <span id="modal-pic" class="col-span-2 font-medium text-slate-200">-</span>
                    </div>
                    <div class="grid grid-cols-3 py-1">
                        <span class="text-slate-400">Kondisi</span>
                        <span id="modal-condition" class="col-span-2 font-medium text-slate-200">-</span>
                    </div>
                </div>

                <div class="pt-2 flex gap-2">
                    <a id="modal-detail-link" href="#" target="_blank" class="flex-1 py-2.5 text-center rounded-xl bg-cyan-400 text-slate-950 font-semibold text-xs hover:bg-cyan-300 transition">
                        Buka Halaman Lengkap
                    </a>
                    <button onclick="closeModal()" class="px-4 py-2.5 rounded-xl border border-slate-700 text-slate-300 font-semibold text-xs hover:bg-slate-800 transition">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <!-- Script Logika Scanner & Modal -->
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
                const placeholder = document.getElementById('scanner-placeholder');
                const btn = document.getElementById('btn-toggle-scan');
                const status = document.getElementById('scan-status');

                placeholder.classList.add('hidden');
                status.classList.remove('hidden');
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
                    status.innerText = 'Arahkan kamera ke QR Code...';
                }).catch(err => {
                    console.error("Gagal mengaktifkan kamera:", err);
                    status.innerText = 'Kamera gagal diakses atau izin ditolak.';
                    placeholder.classList.remove('hidden');
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
                const placeholder = document.getElementById('scanner-placeholder');
                const btn = document.getElementById('btn-toggle-scan');
                const status = document.getElementById('scan-status');

                placeholder.classList.remove('hidden');
                status.classList.add('hidden');
                btn.innerText = "Mulai Scan Sekarang";
                btn.classList.replace('bg-rose-600', 'bg-cyan-400');
                btn.classList.replace('hover:bg-rose-500', 'hover:bg-cyan-300');
                btn.classList.replace('text-white', 'text-slate-950');
            }

            function onScanSuccess(decodedText) {
                stopScanner();

                let rawText = decodedText.trim();
                console.log("Isi QR Terbaca:", rawText);

                // 1. JIKA HASIL SCAN BERUPA URL / LINK LENGKAP
                if (rawText.startsWith('http://') || rawText.startsWith('https://')) {
                    try {
                        const urlObj = new URL(rawText);
                        const pathSegments = urlObj.pathname.split('/').filter(Boolean);
                        const idAtauKode = pathSegments[pathSegments.length - 1]; // Ekstrak ID/Kode paling ujung (misal: 243)

                        // Jika URL adalah link detail aset (baik /aset/qr/243, /assets/243, atau /assets/public/243)
                        if (urlObj.pathname.includes('/aset/') || urlObj.pathname.includes('/assets/')) {
                            // PAKSA REDIRECT KE ROUTE PUBLIK ASET YANG VALID KANAN SAMA SEPERTI GOOGLE LENS
                            window.location.href = `/assets/public/${idAtauKode}`;
                            return;
                        }

                        // Jika URL link lain (misal Dokumen KIR)
                        window.location.href = urlObj.pathname;
                        return;
                    } catch (e) {
                        console.error("Gagal parsing URL:", e);
                        window.location.href = rawText;
                        return;
                    }
                }

                // 2. JIKA HASIL SCAN BERUPA ID ATAU KODE BARANG BIASA
                window.location.href = `/assets/public/${encodeURIComponent(rawText)}`;
            }

            function onScanFailure(error) {
                // Abaikan kesalahan per frame
            }

            function showModal(data) {
                document.getElementById('modal-asset-name').innerText = data.name || '-';
                document.getElementById('modal-asset-code').innerText = data.asset_code || '-';
                document.getElementById('modal-register-number').innerText = data.register_number || '-';
                document.getElementById('modal-brand').innerText = data.brand || '-';
                document.getElementById('modal-location').innerText = data.location || '-';
                document.getElementById('modal-pic').innerText = data.person_in_charge || '-';
                document.getElementById('modal-condition').innerText = data.condition || '-';
                
                const detailLink = document.getElementById('modal-detail-link');
                if (data.detail_url) {
                    detailLink.href = data.detail_url;
                    detailLink.classList.remove('hidden');
                } else {
                    detailLink.classList.add('hidden');
                }

                document.getElementById('asset-modal').classList.remove('hidden');
                document.getElementById('asset-modal').classList.add('flex');
            }

            function closeModal() {
                document.getElementById('asset-modal').classList.add('hidden');
                document.getElementById('asset-modal').classList.remove('flex');
            }
        </script>
    </body>
</html>