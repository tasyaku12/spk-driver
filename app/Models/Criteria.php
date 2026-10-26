<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Criteria extends Model {
    protected $fillable = ['kode', 'nama', 'satuan', 'bobot', 'tipe'];
}
