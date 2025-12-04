<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify users table to add 'pemilik' role
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'pengajar', 'siswa', 'wali', 'pemilik') DEFAULT 'siswa'");

        // Add kkm column to mapel table
        Schema::table('mapel', function (Blueprint $table) {
            $table->integer('kkm')->default(70)->after('deskripsi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert users table role
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'pengajar', 'siswa', 'wali') DEFAULT 'siswa'");

        // Drop kkm column from mapel table
        Schema::table('mapel', function (Blueprint $table) {
            $table->dropColumn('kkm');
        });
    }
};
