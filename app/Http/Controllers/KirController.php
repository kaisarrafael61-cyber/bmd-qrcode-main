<?php

namespace App\Http\Controllers;

use App\Models\Kir;
use App\Models\KirPdf;
use App\Imports\KirImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Throwable;

class KirController extends Controller
{
    // -------------------------------------------------------------
    // FITUR: Upload & Tampil Manual PDF KIR
    // -------------------------------------------------------------

    // Menampilkan halaman daftar, form upload, pencarian, dan filter tabel KIR
    public function index(Request $request)
    {
        $kirList = KirPdf::latest()->get();

        // Mengambil data KIR dengan filter pencarian, tahun, bahan, dan pagination
        try {
            $query = Kir::query();

            // 1. Filter Pencarian Teks
            if ($search = $request->input('search')) {
                $query->where(function($q) use ($search) {
                    $q->where('jenis_barang', 'like', "%{$search}%")
                      ->orWhere('no_kode_barang', 'like', "%{$search}%")
                      ->orWhere('merk_model', 'like', "%{$search}%");
                });
            }

            // 2. Filter Tahun Pembuatan
            if ($year = $request->input('year')) {
                $query->where('tahun_pembuatan', $year);
            }

            // 3. Filter Bahan
            if ($material = $request->input('material')) {
                $query->where('bahan', $material);
            }

            // Eksekusi paginasi 10 data per halaman dengan mempertahankan query string URL
            $kirs = $query->paginate(10)->withQueryString();

            // Ambil daftar bahan unik untuk pilihan dropdown di view
            $materials = Kir::distinct()->whereNotNull('bahan')->pluck('bahan');

        } catch (Throwable $e) {
            $kirs = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 10); // Kosong aman jika tabel belum ada
            $materials = collect();
        }
        
        return view('kir.index', compact('kirList', 'kirs', 'materials'));
    }

    // Memproses upload file PDF KIR secara manual
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'pdf_file' => 'required|mimes:pdf|max:10240', // Maksimal 10MB
        ]);

        if ($request->hasFile('pdf_file')) {
            $file = $request->file('pdf_file');
            $originalName = $file->getClientOriginalName();
            
            // Simpan file ke storage/app/public/kir_pdf/
            $filePath = $file->storeAs('kir_pdf', time() . '_' . $originalName, 'public');

            // Simpan data metadata ke MySQL
            KirPdf::create([
                'title' => $request->title,
                'file_path' => $filePath,
                'file_name' => $originalName,
            ]);

            return redirect()->route('kir.index')->with('success', 'File PDF KIR berhasil diunggah!');
        }

        return back()->with('error', 'Gagal mengunggah file.');
    }

    // Menampilkan detail KIR beserta QR Code-nya
    public function show($id)
    {
        $kir = KirPdf::findOrFail($id);

        // Ambil URL file PDF
        $publicPdfUrl = asset('storage/' . $kir->file_path);
        
        if (config('app.url_lan')) {
            $publicPdfUrl = rtrim(config('app.url_lan'), '/') . '/storage/' . $kir->file_path;
        }

        // Generate QR Code SVG berbasis String URL
        $qrCode = QrCode::size(250)->margin(1)->generate($publicPdfUrl);

        return view('kir.show', compact('kir', 'qrCode', 'publicPdfUrl'));
    }

    // -------------------------------------------------------------
    // FITUR: Import Excel Data Barang & Generate PDF
    // -------------------------------------------------------------

    // Memproses import file Excel (Sekretariat.xlsx)
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240'
        ]);

        Excel::import(new KirImport, $request->file('file'));

        return redirect()->route('kir.index')->with('success', 'Data Excel KIR berhasil di-import!');
    }

    // Stream/Pratinjau PDF di browser (bisa berdasarkan ruangan atau cetak semua)
    public function streamPdf($ruangan = null)
    {
        // Jika ada parameter ruangan, filter berdasar ruangan. Jika tidak, ambil semua data.
        if ($ruangan) {
            $kirs = Kir::where('ruangan', $ruangan)->get();
            $fileName = "KIR-$ruangan.pdf";
        } else {
            $kirs = Kir::all();
            $fileName = "KIR-Semua-Ruangan.pdf";
        }

        $pdf = Pdf::loadView('kir.pdf', compact('kirs', 'ruangan'))
                  ->setPaper('a4', 'landscape');

        return $pdf->stream($fileName);
    }

    // Langsung Mengunduh / Download File PDF KIR + QR Code
    public function exportPdf($ruangan = null)
    {
        if ($ruangan) {
            $kirs = Kir::where('ruangan', $ruangan)->get();
            $fileName = "KIR-$ruangan.pdf";
        } else {
            $kirs = Kir::all();
            $fileName = "KIR-Semua-Ruangan.pdf";
        }

        $pdf = Pdf::loadView('kir.pdf', compact('kirs', 'ruangan'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download($fileName);
    }
}