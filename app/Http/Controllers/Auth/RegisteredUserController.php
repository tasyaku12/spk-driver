<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

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
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'role' => ['nullable', 'in:driver,admin'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'wilayah' => ['nullable', 'string', 'max:100'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $role = $request->input('role', 'driver');
        $driverId = null;

        if ($role === 'driver') {
            // Generate next available driver code (D001, D002, ...)
            $lastDriver = \App\Models\Driver::where('kode', 'like', 'D%')->orderBy('id', 'desc')->first();
            $nextNum = 1;
            if ($lastDriver && preg_match('/D(\d+)/i', $lastDriver->kode, $matches)) {
                $nextNum = intval($matches[1]) + 1;
            }
            $kode = 'D' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
            while (\App\Models\Driver::where('kode', $kode)->exists()) {
                $nextNum++;
                $kode = 'D' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
            }

            $driver = \App\Models\Driver::create([
                'kode'    => $kode,
                'nama'    => $request->name,
                'no_hp'   => $request->no_hp ?? '0812' . rand(10000000, 99999999),
                'wilayah' => $request->wilayah ?? 'Hub Operasional',
            ]);

            $driverId = $driver->id;
        }

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'role'      => $role,
            'driver_id' => $driverId,
            'password'  => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        if ($user->isDriver()) {
            return redirect()->route('driver.dashboard');
        }

        return redirect(RouteServiceProvider::HOME);
    }
}
