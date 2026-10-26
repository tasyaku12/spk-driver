<?php
namespace App\Http\Controllers;

use App\Models\Criteria;
use Illuminate\Http\Request;

class CriteriaController extends Controller
{
    private const PESAN = 'Hasil perankingan lama perlu dihitung ulang di menu Perhitungan SAW.';

    public function index()
    {
        $criterias = Criteria::orderBy('kode')->get();
        return view('criterias.index', [
            'criterias'  => $criterias,
            'totalBobot' => round($criterias->sum('bobot'), 4),
        ]);
    }

    private function rules(?int $id = null): array
    {
        return [
            'kode'   => 'required|max:10|unique:criterias,kode' . ($id ? ",$id" : ''),
            'nama'   => 'required|max:255',
            'satuan' => 'nullable|max:20',
            'bobot'  => 'required|numeric|min:0|max:1',
            'tipe'   => 'required|in:benefit,cost',
        ];
    }

    public function store(Request $r)
    {
        Criteria::create($r->validate($this->rules()));
        return back()->with('ok', 'Kriteria ditambahkan. Isi nilai kriteria ini untuk tiap driver di menu Data Penilaian. ' . self::PESAN);
    }

    public function update(Request $r, Criteria $criteria)
    {
        $criteria->update($r->validate($this->rules($criteria->id)));
        return back()->with('ok', 'Kriteria diperbarui. ' . self::PESAN);
    }

    public function destroy(Criteria $criteria)
    {
        $criteria->delete();   // nilai terkait ikut terhapus (cascade)
        return back()->with('ok', 'Kriteria dihapus. ' . self::PESAN);
    }

    /** Skalakan semua bobot agar totalnya tepat 1. */
    public function normalisasi()
    {
        $criterias = Criteria::all();
        $total = $criterias->sum('bobot');
        if ($total <= 0) return back()->with('warn', 'Total bobot 0, tidak bisa dinormalisasi.');
        foreach ($criterias as $c) $c->update(['bobot' => round($c->bobot / $total, 4)]);
        return back()->with('ok', 'Bobot dinormalisasi (total mendekati 1). ' . self::PESAN);
    }
}
