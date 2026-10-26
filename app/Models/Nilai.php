<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model {
    protected $fillable = ['driver_id', 'criteria_id', 'periode', 'nilai'];
    public function driver() { return $this->belongsTo(Driver::class); }
    public function criteria() { return $this->belongsTo(Criteria::class); }
}
