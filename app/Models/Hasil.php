<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Hasil extends Model {
    protected $fillable = ['driver_id', 'periode', 'nilai_preferensi', 'ranking'];
    public function driver() { return $this->belongsTo(Driver::class); }
}
