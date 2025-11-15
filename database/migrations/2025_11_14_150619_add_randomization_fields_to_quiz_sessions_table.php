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
        Schema::table('quiz_sessions', function (Blueprint $table) {
            $table->json('question_order')->nullable()->after('status'); // Urutan soal yang diacak [question_id1, question_id2, ...]
            $table->json('option_mapping')->nullable()->after('question_order'); // Mapping pilihan jawaban yang diacak {question_id: {original: shuffled}}
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quiz_sessions', function (Blueprint $table) {
            $table->dropColumn(['question_order', 'option_mapping']);
        });
    }
};
