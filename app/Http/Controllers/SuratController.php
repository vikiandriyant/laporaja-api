<?php

namespace App\Http\Controllers;

use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SuratController extends Controller
{
    public function index()
    {
        try {
            $surat = Surat::all();
            return response()->json([
                'success' => true,
                'data' => $surat
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving surat data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $surat = Surat::with('riwayatLaporan')->find($id);

            if (!$surat) {
                return response()->json([
                    'success' => false,
                    'message' => 'Surat not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $surat
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving surat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'jenis_surat' => 'required|in:keterangan,pengantar,izin'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $surat = Surat::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Surat created successfully',
                'data' => $surat
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating surat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Surat $surat)
    {
        $validator = Validator::make($request->all(), [
            'jenis_surat' => 'sometimes|required|in:keterangan,pengantar,izin'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $surat->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Surat updated successfully',
                'data' => $surat
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating surat',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Surat $surat)
    {
        try {
            $surat->delete();

            return response()->json([
                'success' => true,
                'message' => 'Surat deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting surat',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}