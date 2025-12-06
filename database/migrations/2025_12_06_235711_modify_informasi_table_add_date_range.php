<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('informasis', function (Blueprint $table) {
            $table->date('tanggal_mulai')->nullable()->after('deskripsi');
            $table->date('tanggal_berakhir')->nullable()->after('tanggal_mulai');
        });

        // Optional: Populate new columns with existing data if needed, or just leave them null for now
        // DB::statement("UPDATE informasis SET tanggal_mulai = tanggal, tanggal_berakhir = tanggal");

        Schema::table('informasis', function (Blueprint $table) {
             $table->dropColumn('tanggal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('informasis', function (Blueprint $table) {
            $table->date('tanggal')->after('deskripsi');
            $table->dropColumn(['tanggal_mulai', 'tanggal_berakhir']);
        });
    }
};
