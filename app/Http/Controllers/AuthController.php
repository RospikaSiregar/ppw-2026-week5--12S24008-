<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // WAJIB: Regenerasi ID Sesi untuk mencegah Session Fixation
            $request->session()->regenerate();

            session(['login_timestamp' => now()->format('d M Y, H:i:s') . ' WIB']);
            
            // Simpan email ke Cookie selama 7 hari (10080 menit)
            Cookie::queue('pustakawan_email', $request->email, 10080);

            return redirect()->intended(route('buku.index'))->with('success', 'Selamat datang kembali, Pustakawan!');
        }

        return back()->with('error', 'Kredensial email atau password tidak valid.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Sesi Anda telah berhasil diakhiri.');
    }
}