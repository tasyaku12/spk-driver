<?php
namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index(Request $r)
    {
        $q = trim((string) $r->query('q'));
        $drivers = Driver::when($q, fn ($x) => $x->where(fn ($w) => $w
                ->where('nama', 'like', "%$q%")->orWhere('kode', 'like', "%$q%")->orWhere('wilayah', 'like', "%$q%")))
            ->orderBy('kode')->paginate(10)->withQueryString();

        $nextKode = $this->generateNextKode();

        return view('drivers.index', compact('drivers', 'q', 'nextKode'));
    }

    public function store(Request $r)
    {
        if (!$r->filled('kode')) {
            $r->merge(['kode' => $this->generateNextKode()]);
        }

        $validated = $r->validate([
            'kode'    => 'required|max:20|unique:drivers,kode',
            'nama'    => 'required|max:255',
            'no_hp'   => 'nullable|max:20',
            'wilayah' => 'nullable|max:255',
        ]);

        Driver::create($validated);
        return back()->with('ok', 'Driver ' . $validated['nama'] . ' (' . $validated['kode'] . ') berhasil ditambahkan.');
    }

    /**
     * Generate kode driver otomatis berikutnya (misal D016)
     */
    private function generateNextKode(): string
    {
        $allKodes = Driver::pluck('kode');
        $maxNum = 0;
        foreach ($allKodes as $k) {
            if (preg_match('/^D(\d+)$/i', $k, $matches)) {
                $num = (int) $matches[1];
                if ($num > $maxNum) {
                    $maxNum = $num;
                }
            }
        }
        $nextNum = $maxNum + 1;
        return 'D' . str_pad((string)$nextNum, 3, '0', STR_PAD_LEFT);
    }

    public function update(Request $r, Driver $driver)
    {
        $driver->update($r->validate([
            'kode'    => 'required|max:20|unique:drivers,kode,' . $driver->id,
            'nama'    => 'required|max:255',
            'no_hp'   => 'nullable|max:20',
            'wilayah' => 'nullable|max:255',
        ]));
        return back()->with('ok', 'Data driver diperbarui.');
    }

    public function destroy(Driver $driver)
    {
        $driver->delete();
        return back()->with('ok', 'Driver dihapus beserta data penilaiannya.');
    }
}
