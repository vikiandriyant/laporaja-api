<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ForgotPasswordController extends Controller
{
    public function sendResetToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|digits:16|exists:users,nik'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Generate token
        $token = Str::random(60);

        // Simpan token di tabel password_reset_tokens
        DB::table('password_reset_tokens')->updateOrInsert(
            ['nik' => $request->nik],
            [
                'token' => $token,
                'created_at' => Carbon::now()
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Token reset password telah dikirim',
            'data' => [
                'token' => $token, // Hanya untuk development
                'expires_at' => Carbon::now()->addHours(1)
            ]
        ]);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'nik' => 'required|digits:16',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required|same:new_password'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Cek token di password_reset_tokens
        $tokenData = DB::table('password_reset_tokens')
            ->where('nik', $request->nik)
            ->where('token', $request->token)
            ->first();

        if (!$tokenData) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid'
            ], 400);
        }

        // Cek apakah token masih valid (1 jam)
        $tokenCreatedAt = Carbon::parse($tokenData->created_at);
        if ($tokenCreatedAt->diffInHours(Carbon::now()) > 1) {
            return response()->json([
                'success' => false,
                'message' => 'Token sudah kadaluarsa'
            ], 400);
        }

        // Update password user
        $user = User::where('nik', $request->nik)->first();
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        // Hapus token yang sudah digunakan
        DB::table('password_reset_tokens')
            ->where('nik', $request->nik)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil direset'
        ]);
    }
}