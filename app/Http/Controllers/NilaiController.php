<?php
namespace App\Http\Controllers;

use App\Exports\ArrayExport;
use App\Imports\NilaiImport;
use App\Models\Criteria;
use App\Models\Driver;
use App\Models\Nilai;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class NilaiController extends Controller
{
    private function nilaiMap(string $periode)
    {
        return Nilai::where('periode', $periode)->get()
            ->mapWithKeys(fn ($n) => [$n->driver_id . '-' . $n->criteria_id => $n->nilai]);
    }

    public function index(Request $r)
    {
        $periode = $r->query('periode', now()->format('Y-m'));
        $q = trim((string) $r->query('q'));
        $filter = $r->query('filter');
        $focus  = $r->query('focus');

        $criterias = Criteria::orderBy('kode')->get();
        $nilaiMap  = $this->nilaiMap($periode);

        $allDrivers = Driver::when($q, function ($query) use ($q) {
            $query->where(function ($w) use ($q) {
                $w->where('nama', 'like', "%$q%")
                  ->orWhere('kode', 'like', "%$q%")
                  ->orWhere('wilayah', 'like', "%$q%");
            });
        })->orderBy('kode')->get();

        $totalDriverCount = $allDrivers->count();
        $unfilledCount = $allDrivers->filter(function ($d) use ($criterias, $nilaiMap) {
            return !$criterias->every(fn ($c) => isset($nilaiMap[$d->id . '-' . $c->id]));
        })->count();

        // Jika filter=unfilled, tampilkan driver yang belum lengkap nilainya
        $drivers = ($filter === 'unfilled')
            ? $allDrivers->filter(function ($d) use ($criterias, $nilaiMap) {
                return !$criterias->every(fn ($c) => isset($nilaiMap[$d->id . '-' . $c->id]));
            })->values()
            : $allDrivers;

        return view('nilai.index', [
            'periode'          => $periode,
            'drivers'          => $drivers,
            'totalDriverCount' => $totalDriverCount,
            'unfilledCount'    => $unfilledCount,
            'criterias'        => $criterias,
            'nilai'            => $nilaiMap,
            'q'                => $q,
            'filter'           => $filter,
            'focus'            => $focus,
        ]);
    }

    public function store(Request $r)
    {
        $r->validate([
            'periode'   => 'required|date_format:Y-m',
            'nilai'     => 'required|array',
            'nilai.*.*' => 'nullable|numeric|min:0',
        ]);
        foreach ($r->nilai as $driverId => $perCriteria) {
            foreach ($perCriteria as $criteriaId => $val) {
                if ($val === null || $val === '') continue;
                Nilai::updateOrCreate(
                    ['driver_id' => $driverId, 'criteria_id' => $criteriaId, 'periode' => $r->periode],
                    ['nilai' => $val]
                );
            }
        }
        return redirect()->route('nilai.index', ['periode' => $r->periode])->with('ok', 'Data penilaian disimpan.');
    }

    /** Unduh template Excel (sudah berisi kode driver & nilai yang ada). */
    public function template(Request $r)
    {
        $periode   = $r->query('periode', now()->format('Y-m'));
        $criterias = Criteria::orderBy('kode')->get();
        $map       = $this->nilaiMap($periode);

        $rows = Driver::orderBy('kode')->get()->map(function ($d) use ($criterias, $map) {
            $row = [$d->kode];
            foreach ($criterias as $c) $row[] = $map[$d->id . '-' . $c->id] ?? '';
            return $row;
        })->all();

        $headings = array_merge(['kode_driver'], $criterias->pluck('kode')->all());
        return Excel::download(new ArrayExport($headings, $rows), "template-penilaian-$periode.xlsx");
    }

    public function import(Request $r)
    {
        $r->validate([
            'periode' => 'required|date_format:Y-m',
            'file'    => 'required|file|mimes:xlsx,xls,csv,txt|max:5120',
        ]);
        $imp = new NilaiImport($r->periode);
        Excel::import($imp, $r->file('file'));

        $msg = "Import selesai: {$imp->berhasil} driver diproses.";
        if ($imp->gagal) $msg .= ' Kode driver tidak dikenal: ' . implode(', ', array_unique($imp->gagal)) . '.';
        return redirect()->route('nilai.index', ['periode' => $r->periode])->with($imp->gagal ? 'warn' : 'ok', $msg);
    }
}
