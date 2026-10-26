<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('hasils', function (Blueprint $t) {
            $t->id();
            $t->foreignId('driver_id')->constrained()->cascadeOnDelete();
            $t->string('periode', 7);
            $t->decimal('nilai_preferensi', 8, 4);
            $t->unsignedInteger('ranking');
            $t->timestamps();
            $t->unique(['driver_id', 'periode']);
        });
    }
    public function down(): void { Schema::dropIfExists('hasils'); }
};
