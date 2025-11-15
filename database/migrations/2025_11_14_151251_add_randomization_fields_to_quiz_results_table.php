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
        Schema::table('quiz_results', function (Blueprint $table) {
            $table->json('question_order')->nullable()->after('jawaban'); // Urutan soal yang diacak
            $table->json('option_mapping')->nullable()->after('question_order'); // Mapping pilihan jawaban yang diacak
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_results', function (Blueprint $table) {
            $table->dropColumn(['question_order', 'option_mapping']);
        });
    }
};
