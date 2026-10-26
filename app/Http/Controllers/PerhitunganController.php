<?php
namespace App\Http\Controllers;

use App\Services\SawService;
use Illuminate\Http\Request;

class PerhitunganController extends Controller
{
    public function index(Request $r, SawService $saw)
    {
        $periode = $r->query('periode', now()->format('Y-m'));
        $q = trim((string) $r->query('q'));
        $hasil = $saw->hitung($periode);

        if ($q !== '' && !empty($hasil['drivers'])) {
            $filteredDriverIds = $hasil['drivers']->filter(function ($d) use ($q) {
                return stripos($d->nama, $q) !== false
                    || stripos($d->kode, $q) !== false
                    || stripos((string) $d->wilayah, $q) !== false;
            })->pluck('id')->all();

            $hasil['matrixDisplay'] = array_intersect_key($hasil['matrix'], array_flip($filteredDriverIds));
            $hasil['normDisplay']   = array_intersect_key($hasil['norm'], array_flip($filteredDriverIds));
            $hasil['prefDisplay']   = array_intersect_key($hasil['pref'], array_flip($filteredDriverIds));
        } else {
            $hasil['matrixDisplay'] = $hasil['matrix'];
            $hasil['normDisplay']   = $hasil['norm'];
            $hasil['prefDisplay']   = $hasil['pref'];
        }

        return view('perhitungan.index', [
            'periode' => $periode,
            'hasil'   => $hasil,
            'q'       => $q,
        ]);
    }
}
