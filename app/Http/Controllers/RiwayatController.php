<?php
namespace App\Http\Controllers;

use App\Models\Hasil;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q'));

        $periods = Hasil::select('periode')->distinct()->orderBy('periode', 'desc')->limit(12)
            ->pluck('periode')->reverse()->values();
        $hasil    = Hasil::with('driver')->whereIn('periode', $periods)->get();
        $byDriver = $hasil->groupBy('driver_id');

        $champions = $periods->map(fn ($p) => $hasil->where('periode', $p)->sortBy('ranking')->first())->filter()->values();

        // urutkan driver berdasarkan rata-rata skor tertinggi
        $driverRows = $byDriver->map(fn ($rows) => [
            'driver' => $rows->first()->driver,
            'avg'    => $rows->avg('nilai_preferensi'),
            'byP'    => $rows->keyBy('periode'),
        ])->sortByDesc('avg')->values();

        // Datasets for Chart: top 8 drivers by average score
        $datasets = $driverRows->take(8)->map(fn ($d) => [
            'label' => $d['driver']->nama ?? 'Driver',
            'data'  => $periods->map(fn ($p) => isset($d['byP'][$p]) ? (float) $d['byP'][$p]->nilai_preferensi : null)->all(),
        ])->values();

        // Filter rows for table display if search query is provided
        $displayRows = $driverRows;
        if ($q !== '') {
            $displayRows = $driverRows->filter(function ($row) use ($q) {
                $driver = $row['driver'];
                if (!$driver) return false;
                $haystack = strtolower(($driver->nama ?? '') . ' ' . ($driver->kode ?? '') . ' ' . ($driver->wilayah ?? ''));
                return str_contains($haystack, strtolower($q));
            })->values();
        }

        // Summary statistics
        $stats = [
            'total_periods'   => $periods->count(),
            'total_drivers'   => $driverRows->count(),
            'top_driver'      => $driverRows->first()['driver']->nama ?? '-',
            'top_avg'         => $driverRows->first() ? number_format($driverRows->first()['avg'], 4) : '-',
            'latest_champion' => $champions->last()?->driver->nama ?? '-',
            'latest_period'   => $periods->last() ? \App\Support\Periode::label($periods->last()) : '-',
        ];

        return view('riwayat.index', [
            'periods'       => $periods,
            'champions'     => $champions,
            'driverRows'    => $displayRows,
            'allDriverRows' => $driverRows,
            'datasets'      => $datasets,
            'stats'         => $stats,
            'q'             => $q,
        ]);
    }
}

