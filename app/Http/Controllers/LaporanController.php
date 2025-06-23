<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LaporanController extends Controller
{
    public function index()
    {
        try {
            $laporan = Laporan::with('kategori')->get();
            return response()->json([
                'success' => true,
                'data' => $laporan
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving laporan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $laporan = Laporan::with(['kategori', 'riwayatLaporan'])->find($id);
            
            if (!$laporan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Laporan not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $laporan
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving laporan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lokasi_kejadian' => 'required|string|max:255',
            'tanggal_kejadian' => 'required|date',
            'kategori_kategori_id' => 'required|exists:kategori,kategori_id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $laporan = Laporan::create($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Laporan created successfully',
                'data' => $laporan
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating laporan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Laporan $laporan)
    {
        $validator = Validator::make($request->all(), [
            'lokasi_kejadian' => 'sometimes|required|string|max:255',
            'tanggal_kejadian' => 'sometimes|required|date',
            'kategori_kategori_id' => 'sometimes|required|exists:kategori,kategori_id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $laporan->update($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Laporan updated successfully',
                'data' => $laporan
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating laporan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Laporan $laporan)
    {
        try {
            $laporan->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Laporan deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting laporan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}