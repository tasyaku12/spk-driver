<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q'));

        $allUsers = User::with('driver')->get();
        $drivers  = Driver::orderBy('nama', 'asc')->get();

        $stats = [
            'total'  => $allUsers->count(),
            'admin'  => $allUsers->where('role', 'admin')->count(),
            'driver' => $allUsers->where('role', 'driver')->count(),
        ];

        $users = User::with('driver')
            ->when($q, function ($query) use ($q) {
                $query->where(function ($w) use ($q) {
                    $w->where('name', 'like', "%$q%")
                      ->orWhere('email', 'like', "%$q%")
                      ->orWhere('role', 'like', "%$q%")
                      ->orWhereHas('driver', function ($d) use ($q) {
                          $d->where('nama', 'like', "%$q%")
                            ->orWhere('kode', 'like', "%$q%")
                            ->orWhere('wilayah', 'like', "%$q%");
                      });
                });
            })
            ->orderBy('id', 'asc')
            ->get();

        return view('users.index', compact('users', 'drivers', 'stats', 'q'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'role'      => ['required', 'in:admin,driver'],
            'password'  => ['required', 'string', 'min:8'],
            'driver_id' => ['nullable', 'exists:drivers,id'],
        ]);

        $driverId = $request->driver_id;

        // Jika role driver dipilih dan tidak memilih profil driver yang sudah ada, otomatis buatkan profil driver
        if ($request->role === 'driver' && empty($driverId)) {
            $lastDriver = Driver::where('kode', 'like', 'D%')->orderBy('id', 'desc')->first();
            $nextNum = 1;
            if ($lastDriver && preg_match('/D(\d+)/i', $lastDriver->kode, $matches)) {
                $nextNum = intval($matches[1]) + 1;
            }
            $kode = 'D' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
            while (Driver::where('kode', $kode)->exists()) {
                $nextNum++;
                $kode = 'D' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
            }

            $driver = Driver::create([
                'kode'    => $kode,
                'nama'    => $request->name,
                'no_hp'   => '0812' . rand(10000000, 99999999),
                'wilayah' => 'Hub Operasional',
            ]);

            $driverId = $driver->id;
        }

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'role'      => $request->role,
            'driver_id' => ($request->role === 'driver') ? $driverId : null,
            'password'  => Hash::make($request->password),
        ]);

        return redirect()->route('users.index')->with('ok', "User baru '{$request->name}' berhasil ditambahkan.");
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role'      => ['required', 'in:admin,driver'],
            'driver_id' => ['nullable', 'exists:drivers,id'],
            'password'  => ['nullable', 'string', 'min:8'],
        ]);

        $driverId = $request->driver_id ?: $user->driver_id;

        // Jika diubah menjadi driver dan belum punya driver_id
        if ($request->role === 'driver' && empty($driverId)) {
            $lastDriver = Driver::where('kode', 'like', 'D%')->orderBy('id', 'desc')->first();
            $nextNum = 1;
            if ($lastDriver && preg_match('/D(\d+)/i', $lastDriver->kode, $matches)) {
                $nextNum = intval($matches[1]) + 1;
            }
            $kode = 'D' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
            while (Driver::where('kode', $kode)->exists()) {
                $nextNum++;
                $kode = 'D' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
            }

            $driver = Driver::create([
                'kode'    => $kode,
                'nama'    => $request->name,
                'no_hp'   => '0812' . rand(10000000, 99999999),
                'wilayah' => 'Hub Operasional',
            ]);

            $driverId = $driver->id;
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->driver_id = ($request->role === 'driver') ? $driverId : null;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('ok', "Data user '{$user->name}' dan perubahan role berhasil disimpan.");
    }

    public function destroy(User $user): RedirectResponse
    {
        if (auth()->id() === $user->id) {
            return redirect()->route('users.index')->with('warn', 'Anda tidak dapat menghapus akun admin yang sedang Anda gunakan.');
        }

        $nama = $user->name;
        $user->delete();

        return redirect()->route('users.index')->with('ok', "User '{$nama}' berhasil dihapus.");
    }
}
