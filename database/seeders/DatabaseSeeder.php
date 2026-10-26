<?php
namespace Database\Seeders;

use App\Models\{Criteria, Driver, Nilai, User};
use App\Services\SawService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin
        $admin = User::firstOrCreate(['email' => 'admin@blitz.test'], [
            'name'     => 'Admin Blitz',
            'role'     => 'admin',
            'password' => Hash::make('password'),
        ]);
        $admin->update(['role' => 'admin']);

        // 2. Kriteria SPK SAW (4 Benefit + 2 Cost = Total Bobot 1.00 / 100%)
        $c1 = Criteria::updateOrCreate(
            ['kode' => 'C1'],
            ['nama' => 'Ketepatan Waktu', 'satuan' => '%', 'bobot' => 0.20, 'tipe' => 'benefit']
        );
        $c2 = Criteria::updateOrCreate(
            ['kode' => 'C2'],
            ['nama' => 'Jumlah Pengiriman', 'satuan' => 'paket', 'bobot' => 0.25, 'tipe' => 'benefit']
        );
        $c3 = Criteria::updateOrCreate(
            ['kode' => 'C3'],
            ['nama' => 'Absensi', 'satuan' => '%', 'bobot' => 0.15, 'tipe' => 'benefit']
        );
        $c4 = Criteria::updateOrCreate(
            ['kode' => 'C4'],
            ['nama' => 'Rating Pelanggan', 'satuan' => 'skor', 'bobot' => 0.15, 'tipe' => 'benefit']
        );
        $c5 = Criteria::updateOrCreate(
            ['kode' => 'C5'],
            ['nama' => 'Paket Gagal / Pending', 'satuan' => 'paket', 'bobot' => 0.15, 'tipe' => 'cost']
        );
        $c6 = Criteria::updateOrCreate(
            ['kode' => 'C6'],
            ['nama' => 'Komplain Pelanggan', 'satuan' => 'kasus', 'bobot' => 0.10, 'tipe' => 'cost']
        );

        // 3. 15 Data Driver & Penilaian Lengkap untuk 6 Kriteria
        // Format: [Kode, Nama, C1 (TW), C2 (JP), C3 (AB), C4 (Rating), C5 (Gagal), C6 (Komplain), Wilayah, No HP]
        $dummy = [
            ['D001', 'Andi Saputra',     96.0, 480, 100.0, 4.85, 2, 0, 'Hub Jakarta Selatan',   '081298765401'],
            ['D002', 'Budi Santoso',     89.0, 520,  94.0, 4.60, 6, 1, 'Hub Jakarta Barat',     '081298765402'],
            ['D003', 'Citra Dewi',       98.0, 410,  98.0, 4.90, 1, 0, 'Hub Jakarta Pusat',     '081298765403'],
            ['D004', 'Dimas Pratama',    91.0, 465,  90.0, 4.70, 5, 1, 'Hub Jakarta Timur',     '081298765404'],
            ['D005', 'Eka Wijaya',       86.0, 540, 100.0, 4.50, 8, 2, 'Hub Jakarta Utara',     '081298765405'],
            ['D006', 'Fajar Nugraha',    95.0, 495,  96.0, 4.80, 3, 0, 'Hub Tangerang Kota',    '081298765406'],
            ['D007', 'Gilang Ramadhan',  93.0, 450,  92.0, 4.75, 4, 1, 'Hub Tangerang Selatan', '081298765407'],
            ['D008', 'Hendra Gunawan',   88.0, 510,  88.0, 4.55, 7, 2, 'Hub Bekasi Barat',      '081298765408'],
            ['D009', 'Indra Kusuma',     97.0, 430, 100.0, 4.95, 1, 0, 'Hub Bekasi Timur',      '081298765409'],
            ['D010', 'Joko Susilo',      92.0, 475,  94.0, 4.65, 5, 1, 'Hub Depok Margonda',    '081298765410'],
            ['D011', 'Kevin Mahendra',   94.0, 460,  96.0, 4.80, 3, 0, 'Hub Depok Cinere',      '081298765411'],
            ['D012', 'Lutfi Hakim',      87.0, 505,  90.0, 4.45, 9, 2, 'Hub Bogor Kota',        '081298765412'],
            ['D013', 'Muhammad Rizky',   99.0, 440, 100.0, 4.98, 0, 0, 'Hub Bogor Cibinong',    '081298765413'],
            ['D014', 'Nur Hidayat',      90.0, 485,  92.0, 4.70, 4, 1, 'Hub Jakarta Pusat',     '081298765414'],
            ['D015', 'Oki Prasetyo',     93.0, 470,  95.0, 4.75, 3, 0, 'Hub Jakarta Selatan',   '081298765415'],
        ];

        $periode = now()->format('Y-m');

        foreach ($dummy as [$kode, $nama, $tw, $jp, $ab, $rating, $gagal, $komplain, $wilayah, $noHp]) {
            $d = Driver::updateOrCreate(
                ['kode' => $kode],
                ['nama' => $nama, 'wilayah' => $wilayah, 'no_hp' => $noHp]
            );

            // Buat / perbarui akun user untuk Driver
            $email = strtolower($kode) . '@blitz.test';
            User::firstOrCreate(
                ['email' => $email],
                [
                    'name'      => $nama,
                    'role'      => 'driver',
                    'driver_id' => $d->id,
                    'password'  => Hash::make('password'),
                ]
            );

            // Simpan penilaian 6 kriteria
            $scores = [
                [$c1, $tw],
                [$c2, $jp],
                [$c3, $ab],
                [$c4, $rating],
                [$c5, $gagal],
                [$c6, $komplain],
            ];

            foreach ($scores as [$c, $v]) {
                Nilai::updateOrCreate(
                    ['driver_id' => $d->id, 'criteria_id' => $c->id, 'periode' => $periode],
                    ['nilai' => $v]
                );
            }
        }

        // Jalankan perhitungan SAW agar tabel hasil langsung terisi
        app(SawService::class)->hitung($periode);
    }
}
