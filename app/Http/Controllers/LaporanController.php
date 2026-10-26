<?php
namespace App\Http\Controllers;

use App\Exports\ArrayExport;
use App\Models\Criteria;
use App\Models\Hasil;
use App\Models\Nilai;
use App\Support\Periode;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanController extends Controller
{
    private function data(string $periode): array
    {
        return [
            'periode'   => $periode,
            'criterias' => Criteria::orderBy('kode')->get(),
            'hasil'     => Hasil::with('driver')->where('periode', $periode)->orderBy('ranking')->get(),
            'nilai'     => Nilai::where('periode', $periode)->get()
                ->mapWithKeys(fn ($n) => [$n->driver_id . '-' . $n->criteria_id => $n->nilai]),
        ];
    }

    public function pdf(Request $r)
    {
        $periode = $r->query('periode', now()->format('Y-m'));
        $data = $this->data($periode);
        if ($data['hasil']->isEmpty()) return back()->with('warn', 'Belum ada hasil perhitungan untuk periode ini.');
        return Pdf::loadView('laporan.pdf', $data)->setPaper('a4')->download("laporan-driver-terbaik-$periode.pdf");
    }

    public function excel(Request $r)
    {
        $periode = $r->query('periode', now()->format('Y-m'));
        $data = $this->data($periode);
        if ($data['hasil']->isEmpty()) return back()->with('warn', 'Belum ada hasil perhitungan untuk periode ini.');

        $headings = array_merge(['Ranking', 'Kode', 'Nama Driver'], $data['criterias']->pluck('nama')->all(), ['Nilai Preferensi']);
        $rows = $data['hasil']->map(function ($h) use ($data) {
            $row = [$h->ranking, $h->driver->kode, $h->driver->nama];
            foreach ($data['criterias'] as $c) $row[] = (float) ($data['nilai'][$h->driver_id . '-' . $c->id] ?? 0);
            $row[] = (float) $h->nilai_preferensi;
            return $row;
        })->all();
        return Excel::download(new ArrayExport($headings, $rows), "ranking-driver-$periode.xlsx");
    }
}
