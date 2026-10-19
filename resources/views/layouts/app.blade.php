<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'BMD QR Asset' }}</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('branding/logo-kominfo-kubar.jpeg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Kunci total tinggi layar 100vh tanpa scrollbar browser luar */
        html, body {
            height: 100vh;
            max-height: 100vh;
            margin: 0;
            padding: 0;
            overflow: hidden !important;
            background-color: #f1f5f9;
        }
    </style>
</head>
<body class="compact-app h-screen w-screen overflow-hidden bg-slate-100 text-slate-900">
    <div id="mobile-sidebar-overlay" class="fixed inset-0 z-40 hidden bg-slate-950/70 lg:hidden"></div>

    <div class="flex h-screen w-screen overflow-hidden lg:grid lg:grid-cols-[240px_1fr]">
        <!-- Sidebar Kiri (Tinggi Penuh & Scroll Internal jika panjang) -->
        <aside id="app-sidebar" class="fixed inset-y-0 left-0 z-50 flex w-[290px] -translate-x-full flex-col justify-between overflow-y-auto bg-slate-950 px-6 py-6 text-white shadow-2xl transition-transform duration-300 lg:static lg:h-screen lg:w-auto lg:translate-x-0 lg:px-5 lg:py-5 lg:shadow-none" data-scrollable-content>
            <div class="flex flex-col">
                <div class="flex items-start justify-between gap-4">
                    <div class="mb-5">
                        <p class="text-xs uppercase tracking-[0.3em] text-cyan-300">DINAS KOMINFO</p>
                        <div class="mt-2.5 flex items-center gap-3">
                            @include('partials.kominfo-logo', ['size' => 'h-12 w-12', 'alt' => 'Logo Kominfo', 'class' => 'rounded-full bg-white p-1'])
                            <div>
                                <h1 class="text-xl font-semibold leading-none lg:text-[1.6rem]">BMD QR Asset</h1>
                                <p class="mt-1.5 text-xs leading-5 text-slate-300">Manajemen aset dengan QR code yang mudah dipakai.</p>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="close-mobile-sidebar" class="inline-flex h-10 w-10 items-center justify-center rounded-2xl border border-slate-800 bg-slate-900 text-slate-200 lg:hidden" aria-label="Tutup menu">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                            <path stroke-linecap="round" d="M6 6l12 12M18 6L6 18" />
                        </svg>
                    </button>
                </div>

                <nav class="space-y-1.5">
                    <a href="{{ route('dashboard') }}" class="block rounded-2xl px-4 py-2.5 text-sm {{ request()->routeIs('dashboard') ? 'bg-cyan-400 text-slate-950 font-semibold' : 'bg-slate-900 text-slate-100 hover:bg-slate-800' }}">Dashboard</a>
                    <a href="{{ route('assets.index') }}" class="block rounded-2xl px-4 py-2.5 text-sm {{ request()->routeIs('assets.*') ? 'bg-cyan-400 text-slate-950 font-semibold' : 'bg-slate-900 text-slate-100 hover:bg-slate-800' }}">Data Aset</a>
                    <a href="{{ route('kir.index') }}" class="block rounded-2xl px-4 py-2.5 text-sm {{ request()->routeIs('kir.*') ? 'bg-cyan-400 text-slate-950 font-semibold' : 'bg-slate-900 text-slate-100 hover:bg-slate-800' }}">Dokumen KIR</a>
                    <a href="{{ route('exports.history') }}" class="block rounded-2xl px-4 py-2.5 text-sm {{ request()->routeIs('exports.history') ? 'bg-cyan-400 text-slate-950 font-semibold' : 'bg-slate-900 text-slate-100 hover:bg-slate-800' }}">Riwayat Export</a>
                </nav>
            </div>

            <!-- BAGIAN BAWAH SIDEBAR (PROFILE & LOGOUT) -->
            <div class="mt-6 rounded-3xl border border-slate-800 bg-slate-900 p-3.5">
                <p class="text-xs font-semibold text-white">{{ auth()->user()->name ?? 'Admin BMD' }}</p>
                <p class="text-[11px] uppercase text-slate-400">{{ auth()->user()->role ?? 'ADMIN' }}</p>
                <form id="sidebar-logout-form" method="POST" action="{{ route('logout') }}" class="mt-3" data-logout-form>
                    @csrf
                    <button type="button" id="btn-trigger-logout" class="w-full rounded-xl border border-slate-700 px-3 py-2 text-xs font-medium text-slate-200 hover:bg-slate-800 transition-colors">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content (Tinggi Terkunci & Konten Dalam Bisa Scroll Internal) -->
        <main class="flex h-screen min-w-0 flex-1 flex-col overflow-y-auto px-4 py-3 sm:px-6 sm:py-4 lg:px-6 lg:py-4">
            <div class="mb-3 flex items-center justify-between rounded-2xl bg-white px-4 py-2.5 shadow-sm lg:hidden">
                <div class="min-w-0 flex items-center gap-3">
                    @include('partials.kominfo-logo', ['size' => 'h-10 w-10', 'alt' => 'Logo Kominfo', 'class' => 'rounded-full bg-white p-1'])
                    <div class="min-w-0">
                        <p class="text-[10px] uppercase tracking-[0.24em] text-cyan-700">DINAS KOMINFO</p>
                        <p class="text-sm font-semibold text-slate-950">BMD QR Asset</p>
                    </div>
                </div>
                <button type="button" id="open-mobile-sidebar" class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-slate-950 text-white" aria-label="Buka menu" aria-expanded="false">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />
                    </svg>
                </button>
            </div>

            @if (session('status'))
                <div class="mb-4 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-xs text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Area Isi View Utama -->
            <div class="flex-1 pb-2">
                @yield('content')
            </div>

            <footer class="mt-auto py-2 text-center text-[11px] text-slate-400">© {{ now()->year }} DISKOMINFO Kabupaten Kutai Barat.</footer>
        </main>
    </div>

    <!-- Modal Logout -->
    <div id="logout-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/70 px-4">
        <div class="w-full max-w-md rounded-[2rem] bg-white p-6 text-slate-900 shadow-2xl">
            <p class="text-sm uppercase tracking-[0.3em] text-cyan-700">Konfirmasi</p>
            <h3 class="mt-3 text-2xl font-semibold">Keluar dari aplikasi?</h3>
            <p class="mt-3 text-sm leading-7 text-slate-600">Pastikan Anda benar-benar ingin logout dari sistem BMD QR Asset.</p>
            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">
                <button type="button" id="cancel-logout" class="rounded-2xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">Batal</button>
                <button type="button" id="confirm-logout" class="rounded-2xl bg-slate-950 px-4 py-3 text-sm font-semibold text-white hover:bg-slate-800">Ya, Logout</button>
            </div>
        </div>
    </div>

    <!-- Modal Hapus -->
    <div id="delete-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/70 px-4" role="dialog" aria-modal="true" aria-labelledby="delete-modal-title">
        <div class="w-full max-w-md rounded-[2rem] bg-white p-6 text-slate-900 shadow-2xl">
            <p class="text-sm uppercase tracking-[0.3em] text-rose-600">Konfirmasi Penghapusan</p>
            <h3 id="delete-modal-title" class="mt-3 text-2xl font-semibold">Hapus aset ini?</h3>
            <p class="mt-3 text-sm leading-7 text-slate-600">Anda akan menghapus <span id="delete-asset-label" class="font-semibold text-slate-800">aset ini</span>. Data aset, foto, dan QR Code terkait akan dihapus permanen dan tidak bisa dikembalikan.</p>
            <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-end">
                <button type="button" id="cancel-delete" class="rounded-2xl border border-slate-300 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-slate-100">Batal</button>
                <button type="button" id="confirm-delete" class="rounded-2xl bg-rose-600 px-4 py-3 text-sm font-semibold text-white hover:bg-rose-700">Ya, Hapus Aset</button>
            </div>
        </div>
    </div>

    <!-- Modal Loading -->
    <div id="loading-overlay" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-950/70 px-4">
        <div class="w-full max-w-sm rounded-[2rem] bg-white p-6 text-center text-slate-900 shadow-2xl">
            <div class="mx-auto h-12 w-12 animate-spin rounded-full border-4 border-slate-200 border-t-cyan-500"></div>
            <h3 class="mt-5 text-xl font-semibold">Menyimpan Data</h3>
            <p class="mt-2 text-sm text-slate-600">Tunggu sebentar, data aset sedang diproses dan QR Code sedang dibuat.</p>
        </div>
    </div>

    <!-- SCRIPT JAVASCRIPT APLIKASI -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile Sidebar Toggle
            const openMobileBtn = document.getElementById('open-mobile-sidebar');
            const closeMobileBtn = document.getElementById('close-mobile-sidebar');
            const sidebar = document.getElementById('app-sidebar');
            const overlay = document.getElementById('mobile-sidebar-overlay');

            function toggleSidebar(show) {
                if (show) {
                    sidebar?.classList.remove('-translate-x-full');
                    overlay?.classList.remove('hidden');
                } else {
                    sidebar?.classList.add('-translate-x-full');
                    overlay?.classList.add('hidden');
                }
            }

            openMobileBtn?.addEventListener('click', () => toggleSidebar(true));
            closeMobileBtn?.addEventListener('click', () => toggleSidebar(false));
            overlay?.addEventListener('click', () => toggleSidebar(false));

            // Logic Modal Logout
            const btnTriggerLogout = document.getElementById('btn-trigger-logout');
            const logoutModal = document.getElementById('logout-modal');
            const cancelLogout = document.getElementById('cancel-logout');
            const confirmLogout = document.getElementById('confirm-logout');
            const logoutForm = document.getElementById('sidebar-logout-form');

            btnTriggerLogout?.addEventListener('click', function() {
                logoutModal?.classList.remove('hidden');
                logoutModal?.classList.add('flex');
            });

            cancelLogout?.addEventListener('click', function() {
                logoutModal?.classList.add('hidden');
                logoutModal?.classList.remove('flex');
            });

            confirmLogout?.addEventListener('click', function() {
                logoutForm?.submit();
            });

            // Logic Modal Hapus Data Aset
            let targetDeleteForm = null;
            const deleteModal = document.getElementById('delete-modal');
            const cancelDelete = document.getElementById('cancel-delete');
            const confirmDelete = document.getElementById('confirm-delete');
            const deleteLabel = document.getElementById('delete-asset-label');

            document.querySelectorAll('[data-confirm-delete]').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    targetDeleteForm = this;
                    const label = this.getAttribute('data-asset-label') || 'aset ini';
                    if (deleteLabel) deleteLabel.textContent = label;
                    deleteModal?.classList.remove('hidden');
                    deleteModal?.classList.add('flex');
                });
            });

            cancelDelete?.addEventListener('click', function() {
                deleteModal?.classList.add('hidden');
                deleteModal?.classList.remove('flex');
                targetDeleteForm = null;
            });

            confirmDelete?.addEventListener('click', function() {
                if (targetDeleteForm) {
                    targetDeleteForm.submit();
                }
            });
        });
    </script>
</body>
</html>