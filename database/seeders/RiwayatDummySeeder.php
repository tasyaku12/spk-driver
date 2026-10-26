<?php
namespace Database\Seeders;

use App\Models\{Criteria, Driver, Nilai};
use App\Services\SawService;
use Illuminate\Database\Seeder;

/** Menambah data dummy 3 bulan sebelumnya agar grafik tren & riwayat terisi. */
class RiwayatDummySeeder extends Seeder
{
    public function run(SawService $saw): void
    {
        mt_srand(42);
        $criterias = Criteria::all();
        $drivers   = Driver::all();
        foreach ([1, 2, 3] as $mundur) {
            $periode = now()->subMonthsNoOverflow($mundur)->format('Y-m');
            foreach ($drivers as $d) {
                foreach ($criterias as $c) {
                    $nilai = $c->satuan === '%' ? mt_rand(80, 100) : mt_rand(350, 560);
                    Nilai::updateOrCreate(
                        ['driver_id' => $d->id, 'criteria_id' => $c->id, 'periode' => $periode],
                        ['nilai' => $nilai]
                    );
                }
            }
            $saw->hitung($periode);
        }
        $saw->hitung(now()->format('Y-m'));
    }
}
