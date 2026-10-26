<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('nilais', function (Blueprint $t) {
            $t->id();
            $t->foreignId('driver_id')->constrained()->cascadeOnDelete();
            $t->foreignId('criteria_id')->constrained()->cascadeOnDelete();
            $t->string('periode', 7);              // format Y-m, contoh 2026-10
            $t->decimal('nilai', 10, 2);
            $t->timestamps();
            $t->unique(['driver_id', 'criteria_id', 'periode']);
        });
    }
    public function down(): void { Schema::dropIfExists('nilais'); }
};
