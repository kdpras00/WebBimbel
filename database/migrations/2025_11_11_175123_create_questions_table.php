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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained('quiz')->onDelete('cascade');
            $table->text('pertanyaan');
            $table->enum('tipe', ['pilihan_ganda', 'essay'])->default('pilihan_ganda');
            $table->json('pilihan')->nullable(); // For multiple choice: ["A", "B", "C", "D"]
            $table->string('jawaban_benar'); // For multiple choice: "A", for essay: answer key
            $table->integer('skor')->default(10);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
