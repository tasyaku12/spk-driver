<?php
namespace App\Support;

class Periode
{
    private const BULAN = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];

    /** "2026-10" => "Oktober 2026" */
    public static function label(?string $p): string
    {
        if (!$p || !preg_match('/^(\d{4})-(\d{2})$/', $p, $m)) return '-';
        return self::BULAN[(int) $m[2]] . ' ' . $m[1];
    }
}
