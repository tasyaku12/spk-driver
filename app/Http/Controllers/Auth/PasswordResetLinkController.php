<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status == Password::RESET_LINK_SENT) {
                return back()->with('status', 'Tautan untuk mengatur ulang password telah berhasil dikirim ke email Anda! Silakan periksa kotak masuk (Inbox) atau folder Spam.');
            }

            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Kami tidak dapat menemukan pengguna dengan alamat email tersebut.']);
        } catch (\Throwable $e) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Gagal mengirim email: ' . $e->getMessage() . '. Pastikan konfigurasi SMTP di file .env sudah terhubung ke akun email pengirim.']);
        }
    }
}
