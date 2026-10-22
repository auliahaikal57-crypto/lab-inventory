<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

       /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // LOGIKA PENGALIHAN PINTAR BERDASARKAN ROLE (SUDAH DIPERBAIKI 🛡️)
        if (auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin') {
            return redirect('/preferences'); // Jika admin atau super admin, masuk ke panel kelola data
        } else {
            return redirect('/katalog'); // Jika mahasiswa, masuk ke katalog kotak-kartu
        }
    }



    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
