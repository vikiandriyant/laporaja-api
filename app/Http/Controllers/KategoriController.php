<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    public function index()
    {
        try {
            $kategori = Kategori::with('user')->get();
            return response()->json([
                'success' => true,
                'data' => $kategori
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving kategori data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $kategori = Kategori::with('user')->find($id);
            
            if (!$kategori) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kategori not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $kategori
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required|string|max:200',
            'users_user_id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $kategori = Kategori::create($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Kategori created successfully',
                'data' => $kategori
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Kategori $kategori)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'sometimes|required|string|max:200',
            'users_user_id' => 'sometimes|required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $kategori->update($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Kategori updated successfully',
                'data' => $kategori
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Kategori $kategori)
    {
        try {
            $kategori->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Kategori deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting kategori',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}