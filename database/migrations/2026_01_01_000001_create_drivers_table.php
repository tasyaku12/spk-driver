<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('drivers', function (Blueprint $t) {
            $t->id();
            $t->string('kode', 20)->unique();
            $t->string('nama');
            $t->string('no_hp', 20)->nullable();
            $t->string('wilayah')->nullable();
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('drivers'); }
};
