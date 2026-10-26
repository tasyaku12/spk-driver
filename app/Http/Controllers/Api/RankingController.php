<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Hasil;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    // GET /api/periode
    public function periode()
    {
        return response()->json(['data' => Hasil::select('periode')->distinct()->orderBy('periode', 'desc')->pluck('periode')]);
    }

    // GET /api/ranking?periode=2026-10
    public function index(Request $r)
    {
        $periode = $r->query('periode', Hasil::max('periode'));
        $data = Hasil::with('driver')->where('periode', $periode)->orderBy('ranking')->get()
            ->map(fn ($h) => [
                'ranking' => $h->ranking,
                'kode'    => $h->driver->kode,
                'nama'    => $h->driver->nama,
                'skor'    => (float) $h->nilai_preferensi,
            ]);
        return response()->json(['periode' => $periode, 'data' => $data]);
    }

    // GET /api/ranking/driver/{kode}?periode=2026-10
    public function driver(Request $r, string $kode)
    {
        $driver  = Driver::where('kode', $kode)->firstOrFail();
        $periode = $r->query('periode', Hasil::max('periode'));
        $h = Hasil::where('driver_id', $driver->id)->where('periode', $periode)->first();
        return response()->json([
            'periode'      => $periode,
            'kode'         => $driver->kode,
            'nama'         => $driver->nama,
            'ranking'      => $h?->ranking,
            'skor'         => $h ? (float) $h->nilai_preferensi : null,
            'total_driver' => Hasil::where('periode', $periode)->count(),
        ]);
    }
}
