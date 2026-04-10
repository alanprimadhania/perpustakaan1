<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View; 
use App\Models\Siswa;  

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'nis' => 'required|unique:siswas',
            'kelas' => 'required',
            'jurusan' => 'nullable',
            'password' => 'required|confirmed|min:6',
        ]);
    
        // ✅ 1. CREATE USER
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'siswa',
        ]);
    
        // ✅ 2. CREATE SISWA
        Siswa::create([
            'user_id' => $user->id,
            'nis' => $request->nis,
            'kelas' => $request->kelas,
            'jurusan' => $request->jurusan,
            'tanggal_lahir' => $request->tanggal_lahir,
            'status' => 'aktif',
        ]);
    
        event(new Registered($user));
    
        Auth::login($user);
    
        return redirect()->route('dashboard');
    }
}
