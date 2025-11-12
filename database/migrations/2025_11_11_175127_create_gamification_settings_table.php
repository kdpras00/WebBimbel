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
        Schema::create('gamification_settings', function (Blueprint $table) {
            $table->id();
            $table->string('nama_aturan');
            $table->integer('nilai_min')->nullable();
            $table->integer('nilai_max')->nullable();
            $table->integer('poin');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gamification_settings');
    }
};
