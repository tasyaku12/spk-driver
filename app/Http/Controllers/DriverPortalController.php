<?php

namespace App\Http\Controllers;

use App\Models\Hasil;
use App\Models\Nilai;
use Illuminate\Http\Request;

class DriverPortalController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = $request->user() ?? auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $driver = $user->driver;
        if (!$driver) {
            return view('driver.no_driver', ['user' => $user]);
        }

        $periods = Hasil::select('periode')->distinct()->orderBy('periode', 'desc')->pluck('periode');
        if ($periods->isEmpty()) {
            $periods = collect([now()->format('Y-m')]);
        }
        $periode = $request->query('periode', $periods->first() ?? now()->format('Y-m'));

        $hasilDriver = Hasil::where('driver_id', $driver->id)->where('periode', $periode)->first();
        $nilais = Nilai::with('criteria')->where('driver_id', $driver->id)->where('periode', $periode)->get();
        $totalEvaluasi = Hasil::where('periode', $periode)->count();

        $top3 = Hasil::with('driver')->where('periode', $periode)->orderBy('ranking')->take(3)->get();
        $riwayat = Hasil::where('driver_id', $driver->id)->orderBy('periode', 'desc')->get();

        return view('driver.dashboard', compact(
            'user',
            'driver',
            'periods',
            'periode',
            'hasilDriver',
            'nilais',
            'totalEvaluasi',
            'top3',
            'riwayat'
        ));
    }

    public function leaderboard(Request $request)
    {
        $user = $request->user() ?? auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $driver = $user->driver;
        $periods = Hasil::select('periode')->distinct()->orderBy('periode', 'desc')->pluck('periode');
        if ($periods->isEmpty()) {
            $periods = collect([now()->format('Y-m')]);
        }
        $periode = $request->query('periode', $periods->first() ?? now()->format('Y-m'));

        $rankings = Hasil::with('driver')->where('periode', $periode)->orderBy('ranking')->get();

        return view('driver.leaderboard', compact('user', 'driver', 'periods', 'periode', 'rankings'));
    }

    public function profile(Request $request)
    {
        $user = $request->user() ?? auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $driver = $user->driver;

        return view('driver.profile', compact('user', 'driver'));
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user() ?? auth()->user();

        $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'no_hp'   => ['nullable', 'string', 'max:20'],
            'wilayah' => ['nullable', 'string', 'max:100'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        if ($user->driver) {
            $user->driver->nama = $request->name;
            if ($request->filled('no_hp')) {
                $user->driver->no_hp = $request->no_hp;
            }
            if ($request->filled('wilayah')) {
                $user->driver->wilayah = $request->wilayah;
            }
            $user->driver->save();
        }

        return back()->with('ok', 'Data profil Anda berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user() ?? auth()->user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini yang Anda masukkan tidak sesuai.']);
        }

        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->save();

        return back()->with('ok', 'Password akun Anda berhasil diperbarui.');
    }
}
