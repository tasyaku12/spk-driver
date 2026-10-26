<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $r)
    {
        $r->validate(['email' => 'required|email', 'password' => 'required']);
        $user = User::where('email', $r->email)->first();
        if (!$user || !Hash::check($r->password, $user->password)) {
            return response()->json(['message' => 'Email atau password salah.'], 401);
        }
        return response()->json(['token' => $user->createToken('android')->plainTextToken, 'name' => $user->name]);
    }

    public function logout(Request $r)
    {
        $r->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout berhasil.']);
    }
}
