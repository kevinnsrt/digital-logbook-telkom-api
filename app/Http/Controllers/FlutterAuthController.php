<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class FlutterAuthController extends Controller
{
    //
public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
            ]);

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken('flutter_token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Registrasi Berhasil',
                'token'   => $token,
                'user'    => $user
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registrasi Gagal: ' . $e->getMessage()
            ], 422);
        }
    }

    // --- FUNGSI LOGIN ---
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau Password salah!',
            ], 401);
        }

        // Hapus token lama biar ga numpuk (opsional)
        $user->tokens()->delete();
        $token = $user->createToken('flutter_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login Berhasil',
            'token'   => $token,
            'user'    => $user
        ], 200);
    }

    // --- FUNGSI LOGOUT ---
public function logout(Request $request)
{
    // 1. Ambil data user yang sedang aktif dan simpan ke variabel $user
    $user = $request->user();

    if ($user) {
        // 2. Set fcm_token menjadi null terlebih dahulu
        $user->update([
            'fcm_token' => null
        ]);

        // 3. Baru hapus token access Sanctum yang sedang digunakan saat ini
        $user->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout Berhasil, Token FCM dan Akses Dihapus',
        ], 200);
    }

    return response()->json([
        'success' => false,
        'message' => 'User tidak ditemukan',
    ], 404);
}
}
