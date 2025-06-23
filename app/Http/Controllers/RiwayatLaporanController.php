<?php

namespace App\Http\Controllers;

use App\Models\RiwayatLaporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RiwayatLaporanController extends Controller
{
    // Ambil semua data riwayat laporan
    public function index()
    {
        // Jika user adalah admin, tampilkan semua data
        if (auth('api')->user()->role === 'admin') {
            $riwayat = RiwayatLaporan::with(['user', 'laporan', 'surat', 'laporan.kategori'])->get();
        } else {
            // Jika bukan admin, hanya tampilkan data milik user yang login
            $riwayat = RiwayatLaporan::with(['user', 'laporan', 'surat', 'laporan.kategori'])
                ->where('users_user_id', auth('api')->id())
                ->get();
        }

        // Transform data untuk menambahkan URL file yang benar
        $riwayat = $riwayat->map(function ($item) {
            return $this->transformRiwayatData($item);
        });

        return response()->json([
            'success' => true,
            'data' => $riwayat
        ]);
    }

    // Ambil detail riwayat laporan
    public function show($id)
    {
        // Jika admin, bisa akses semua data
        if (auth('api')->user()->role === 'admin') {
            $riwayat = RiwayatLaporan::with(['user', 'laporan', 'surat', 'laporan.kategori'])
                ->find($id);
        } else {
            // Jika bukan admin, hanya bisa akses data miliknya sendiri
            $riwayat = RiwayatLaporan::with(['user', 'laporan', 'surat', 'laporan.kategori'])
                ->where('users_user_id', auth('api')->id())
                ->find($id);
        }

        if (!$riwayat) {
            return response()->json([
                'success' => false,
                'message' => 'Riwayat laporan tidak ditemukan atau Anda tidak memiliki akses'
            ], 404);
        }

        // Transform data untuk menambahkan URL file yang benar
        $riwayat = $this->transformRiwayatData($riwayat);

        return response()->json([
            'success' => true,
            'data' => $riwayat
        ]);
    }

    // Buat riwayat laporan baru
    public function store(Request $request)
    {
        $request->validate([
            'jenis' => 'required|in:laporan,surat',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,ppt,pptx,mp4,mp3,csv|max:20480',
            'kontak' => 'nullable|string',
            'laporan_laporan_id' => 'nullable|exists:laporan,laporan_id',
            'surat_surat_id' => 'nullable|exists:surat,surat_id'
        ]);

        // Ambil data dari request kecuali file dan users_user_id
        $data = $request->except(['file', 'users_user_id']);

        // Set users_user_id otomatis dari user yang sedang login
        $data['users_user_id'] = auth('api')->id();

        // Jika ada file yang diupload
        if ($request->hasFile('file')) {
            $uploadedFile = $request->file('file');
            $filename = time() . '_' . $uploadedFile->getClientOriginalName();

            // Simpan file ke storage/app/public/uploads/riwayat
            $path = $uploadedFile->storeAs('uploads/riwayat', $filename, 'public');

            // Simpan path relatif ke database
            $data['file'] = $path;
        }

        $riwayat = RiwayatLaporan::create($data);

        // Load relasi yang diperlukan
        $riwayat->load(['laporan.kategori', 'surat', 'user', 'laporan']);

        // Transform data untuk response
        $responseData = $this->transformRiwayatData($riwayat);

        return response()->json([
            'success' => true,
            'message' => 'Riwayat laporan berhasil dibuat',
            'data' => $responseData
        ], 201);
    }

    // Update riwayat laporan (untuk user biasa)
public function update(Request $request, $id)
{
    $request->validate([
        'judul' => 'required|string|max:255',
        'deskripsi' => 'required|string',
        'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,ppt,pptx,mp4,mp3,csv|max:20480',
        'kontak' => 'nullable|string',
    ]);

    // Perbaiki query: pastikan riwayat milik user yang login
    $riwayat = RiwayatLaporan::where('users_user_id', auth('api')->id())
        ->where('riwayat_id', $id) // Gunakan riwayat_id
        ->first();

    if (!$riwayat) {
        return response()->json([
            'success' => false,
            'message' => 'Riwayat laporan tidak ditemukan atau Anda tidak memiliki akses'
        ], 404);
    }

    $data = $request->except(['status', 'komentar', 'file']);

    // Handle file upload
    if ($request->hasFile('file')) {
        // Hapus file lama jika ada
        if ($riwayat->file && Storage::disk('public')->exists($riwayat->file)) {
            Storage::disk('public')->delete($riwayat->file);
        }

        // Upload file baru
        $uploadedFile = $request->file('file');
        $filename = time() . '_' . $uploadedFile->getClientOriginalName();
        $path = $uploadedFile->storeAs('uploads/riwayat', $filename, 'public');
        $data['file'] = $path;
    }

    $riwayat->update($data);

    $responseData = $this->transformRiwayatData($riwayat->fresh()); // Load data terbaru

    return response()->json([
        'success' => true,
        'message' => 'Riwayat laporan berhasil diperbarui',
        'data' => $responseData
    ]);
}

    // Update status riwayat laporan (khusus admin)
    public function updateStatus(Request $request, $id)
    {
        // Cek apakah user adalah admin
        if (auth('api')->user()->role !== 'admin') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya admin yang dapat mengupdate status'
            ], 403);
        }

        $request->validate([
            'status' => 'required|in:perlu ditinjau,dalam proses,selesai,ditolak',
            'komentar' => 'nullable|string'
        ]);

        $riwayat = RiwayatLaporan::with(['user', 'laporan', 'surat', 'laporan.kategori'])
            ->find($id);

        if (!$riwayat) {
            return response()->json([
                'success' => false,
                'message' => 'Riwayat laporan tidak ditemukan'
            ], 404);
        }

        $riwayat->update([
            'status' => $request->status,
            'komentar' => $request->komentar
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diperbarui',
            'data' => $this->transformRiwayatData($riwayat)
        ]);
    }

    // Hapus riwayat laporan
    public function destroy($id)
    {
        $riwayat = RiwayatLaporan::find($id);

        if (!$riwayat) {
            return response()->json([
                'success' => false,
                'message' => 'Riwayat laporan tidak ditemukan'
            ], 404);
        }

        // Hapus file jika ada
        if ($riwayat->file && Storage::disk('public')->exists($riwayat->file)) {
            Storage::disk('public')->delete($riwayat->file);
        }

        $riwayat->delete();

        return response()->json([
            'success' => true,
            'message' => 'Riwayat laporan berhasil dihapus'
        ]);
    }

    /**
     * Transform data riwayat untuk menambahkan URL file yang benar
     */
    private function transformRiwayatData($riwayat)
    {
        // Ambil hanya field yang diperlukan
        $data = [
            'riwayat_id' => $riwayat->riwayat_id,
            'jenis' => $riwayat->jenis,
            'judul' => $riwayat->judul,
            'deskripsi' => $riwayat->deskripsi,
            'kontak' => $riwayat->kontak,
            'status' => $riwayat->status,
            'komentar' => $riwayat->komentar,
            'created_at' => $riwayat->created_at,
            'updated_at' => $riwayat->updated_at,
            'users_user_id' => $riwayat->users_user_id,
            'laporan_laporan_id' => $riwayat->laporan_laporan_id,
            'surat_surat_id' => $riwayat->surat_surat_id,
        ];

        // Tambahkan URL file yang benar
        if ($riwayat->file) {
            $fileUrl = asset('storage/' . $riwayat->file);
            $data['file_url'] = $fileUrl;
            $data['media'] = [$fileUrl];
        } else {
            $data['file_url'] = null;
            $data['media'] = [];
        }

        // Tambahkan data relasi (hanya yang diperlukan)
        if ($riwayat->jenis === 'laporan' && $riwayat->laporan) {
            $data['laporan'] = [
                'laporan_id' => $riwayat->laporan->laporan_id,
                'lokasi_kejadian' => $riwayat->laporan->lokasi_kejadian,
                'tanggal_kejadian' => $riwayat->laporan->tanggal_kejadian,
            ];

            if ($riwayat->laporan->kategori) {
                $data['laporan']['kategori'] = [
                    'kategori_id' => $riwayat->laporan->kategori->kategori_id,
                    'nama_kategori' => $riwayat->laporan->kategori->nama_kategori,
                ];
            }
        }

        if ($riwayat->jenis === 'surat' && $riwayat->surat) {
            $data['surat'] = [
                'surat_id' => $riwayat->surat->surat_id,
                'jenis_surat' => $riwayat->surat->jenis_surat,
            ];
        }

        if ($riwayat->user) {
            $data['user'] = [
                'id' => $riwayat->user->id,
                'nama_lengkap' => $riwayat->user->nama_lengkap,
                'nik' => $riwayat->user->nik,
                'tempat_tinggal' => $riwayat->user->tempat_tinggal,
                'tanggal_lahir' => $riwayat->user->tanggal_lahir,
                'jenis_kelamin' => $riwayat->user->jenis_kelamin,
                'no_telepon' => $riwayat->user->no_telepon
            ];
        }

        return $data;
    }
}
