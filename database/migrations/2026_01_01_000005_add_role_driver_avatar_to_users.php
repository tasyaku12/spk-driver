<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambahkan kolom role dan driver_id ke tabel users
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role', 20)->default('admin')->after('password');
            });
        }
        if (!Schema::hasColumn('users', 'driver_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('driver_id')->nullable()->after('role');
                $table->foreign('driver_id')->references('id')->on('drivers')->nullOnDelete();
            });
        }
        if (!Schema::hasColumn('users', 'avatar')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('avatar')->nullable()->after('driver_id');
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['driver_id']);
            $table->dropColumn(['role', 'driver_id', 'avatar']);
        });
    }
};
