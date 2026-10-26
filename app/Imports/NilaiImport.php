<?php
namespace App\Imports;

use App\Models\Criteria;
use App\Models\Driver;
use App\Models\Nilai;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class NilaiImport implements ToCollection, WithHeadingRow
{
    public int $berhasil = 0;
    public array $gagal = [];

    public function __construct(private string $periode) {}

    public function collection(Collection $rows)
    {
        $criterias = Criteria::all();
        $drivers   = Driver::pluck('id', 'kode');

        foreach ($rows as $row) {
            $kode = trim((string) ($row['kode_driver'] ?? ''));
            if ($kode === '') continue;
            if (!$drivers->has($kode)) { $this->gagal[] = $kode; continue; }

            foreach ($criterias as $c) {
                $val = $row[Str::slug($c->kode, '_')] ?? null;
                if ($val === null || $val === '') continue;
                $val = str_replace(',', '.', (string) $val);
                if (!is_numeric($val)) continue;
                Nilai::updateOrCreate(
                    ['driver_id' => $drivers[$kode], 'criteria_id' => $c->id, 'periode' => $this->periode],
                    ['nilai' => $val]
                );
            }
            $this->berhasil++;
        }
    }
}
