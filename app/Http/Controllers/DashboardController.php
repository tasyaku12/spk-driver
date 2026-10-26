<?php
namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\Driver;
use App\Models\Hasil;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $r)
    {
        $periods = Hasil::select('periode')->distinct()->orderBy('periode', 'desc')->pluck('periode');
        $periode = $r->query('periode', $periods->first());
        $hasil   = $periode
            ? Hasil::with('driver')->where('periode', $periode)->orderBy('ranking')->get()
            : collect();

        // tren skor 5 driver teratas pada maksimal 6 periode terakhir
        $trendPeriods = $periods->sort()->values()->take(-6)->values();
        $top5 = $hasil->take(5);
        $trendRows = Hasil::whereIn('driver_id', $top5->pluck('driver_id'))
            ->whereIn('periode', $trendPeriods)->get()->groupBy('driver_id');
        $trend = $top5->map(fn ($h) => [
            'label' => $h->driver->nama,
            'data'  => $trendPeriods->map(function ($p) use ($trendRows, $h) {
                $row = $trendRows->get($h->driver_id, collect())->firstWhere('periode', $p);
                return $row ? (float) $row->nilai_preferensi : null;
            })->all(),
        ])->values();

        return view('dashboard', [
            'periods'      => $periods,
            'periode'      => $periode,
            'hasil'        => $hasil,
            'jmlDriver'    => Driver::count(),
            'jmlCriteria'  => Criteria::count(),
            'rataSkor'     => $hasil->avg('nilai_preferensi'),
            'trendLabels'  => $trendPeriods->all(),
            'trend'        => $trend,
        ]);
    }
}
