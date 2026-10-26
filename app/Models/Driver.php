<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model {
    protected $fillable = ['kode', 'nama', 'no_hp', 'wilayah'];
    public function nilais() { return $this->hasMany(Nilai::class); }
    public function hasils() { return $this->hasMany(Hasil::class); }
    public function user() { return $this->hasOne(User::class); }
}
