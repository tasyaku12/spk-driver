<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('criterias', function (Blueprint $t) {
            $t->id();
            $t->string('kode', 10)->unique();
            $t->string('nama');
            $t->string('satuan', 20)->nullable();
            $t->decimal('bobot', 5, 4);            // 0 - 1
            $t->enum('tipe', ['benefit', 'cost'])->default('benefit');
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('criterias'); }
};
