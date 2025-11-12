<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\Question;

class MatematikaQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $quiz = Quiz::where('judul', 'LIKE', '%MATEMATIKA MUDAH%')->first();
        
        if (!$quiz) {
            $this->command->warn('Quiz tidak ditemukan!');
            return;
        }

        $questions = [
            [
                'pertanyaan' => 'Jika f(x) = 2x² + 3x - 5, berapakah nilai f(3)?',
                'pilihan' => ['A' => '22', 'B' => '18', 'C' => '20', 'D' => '24'],
                'jawaban_benar' => 'A',
                'skor' => 10,
                'urutan' => 1
            ],
            [
                'pertanyaan' => 'Hitunglah hasil dari ∫(3x² + 2x) dx dari 0 sampai 2',
                'pilihan' => ['A' => '10', 'B' => '12', 'C' => '14', 'D' => '16'],
                'jawaban_benar' => 'B',
                'skor' => 15,
                'urutan' => 2
            ],
            [
                'pertanyaan' => 'Jika log₂(x) = 5, maka nilai x adalah?',
                'pilihan' => ['A' => '16', 'B' => '32', 'C' => '64', 'D' => '128'],
                'jawaban_benar' => 'B',
                'skor' => 10,
                'urutan' => 3
            ],
            [
                'pertanyaan' => 'Tentukan limit dari (x² - 4) / (x - 2) ketika x mendekati 2',
                'pilihan' => ['A' => '2', 'B' => '4', 'C' => '6', 'D' => '8'],
                'jawaban_benar' => 'B',
                'skor' => 12,
                'urutan' => 4
            ],
            [
                'pertanyaan' => 'Jika sin(30°) = 0.5, berapakah cos(60°)?',
                'pilihan' => ['A' => '0.5', 'B' => '0.707', 'C' => '0.866', 'D' => '1'],
                'jawaban_benar' => 'A',
                'skor' => 10,
                'urutan' => 5
            ],
            [
                'pertanyaan' => 'Selesaikan persamaan kuadrat: x² - 7x + 12 = 0',
                'pilihan' => ['A' => 'x = 3 atau x = 4', 'B' => 'x = 2 atau x = 6', 'C' => 'x = 1 atau x = 12', 'D' => 'x = -3 atau x = -4'],
                'jawaban_benar' => 'A',
                'skor' => 10,
                'urutan' => 6
            ],
            [
                'pertanyaan' => 'Hitunglah determinan dari matriks [[3, 2], [1, 4]]',
                'pilihan' => ['A' => '8', 'B' => '10', 'C' => '12', 'D' => '14'],
                'jawaban_benar' => 'B',
                'skor' => 12,
                'urutan' => 7
            ],
            [
                'pertanyaan' => 'Jika deret aritmatika memiliki suku pertama 5 dan beda 3, berapakah suku ke-10?',
                'pilihan' => ['A' => '28', 'B' => '32', 'C' => '35', 'D' => '38'],
                'jawaban_benar' => 'B',
                'skor' => 10,
                'urutan' => 8
            ],
            [
                'pertanyaan' => 'Tentukan turunan pertama dari f(x) = x³ - 4x² + 5x - 2',
                'pilihan' => ['A' => '3x² - 8x + 5', 'B' => '3x² - 4x + 5', 'C' => 'x² - 8x + 5', 'D' => '3x² - 8x + 2'],
                'jawaban_benar' => 'A',
                'skor' => 12,
                'urutan' => 9
            ],
            [
                'pertanyaan' => 'Jika sebuah lingkaran memiliki jari-jari 7 cm, berapakah luasnya? (π = 22/7)',
                'pilihan' => ['A' => '154 cm²', 'B' => '154.5 cm²', 'C' => '155 cm²', 'D' => '156 cm²'],
                'jawaban_benar' => 'A',
                'skor' => 10,
                'urutan' => 10
            ],
            [
                'pertanyaan' => 'Hitunglah nilai dari 2⁵ × 2³',
                'pilihan' => ['A' => '2⁸', 'B' => '2¹⁵', 'C' => '4⁸', 'D' => '8⁸'],
                'jawaban_benar' => 'A',
                'skor' => 10,
                'urutan' => 11
            ],
            [
                'pertanyaan' => 'Jika cos(θ) = 0.6 dan θ berada di kuadran I, berapakah sin(θ)?',
                'pilihan' => ['A' => '0.4', 'B' => '0.6', 'C' => '0.8', 'D' => '1.0'],
                'jawaban_benar' => 'C',
                'skor' => 12,
                'urutan' => 12
            ],
            [
                'pertanyaan' => 'Selesaikan sistem persamaan: 2x + 3y = 12 dan x - y = 1',
                'pilihan' => ['A' => 'x = 3, y = 2', 'B' => 'x = 4, y = 3', 'C' => 'x = 5, y = 4', 'D' => 'x = 2, y = 1'],
                'jawaban_benar' => 'A',
                'skor' => 12,
                'urutan' => 13
            ],
            [
                'pertanyaan' => 'Hitunglah hasil dari √(144) + √(81)',
                'pilihan' => ['A' => '19', 'B' => '20', 'C' => '21', 'D' => '22'],
                'jawaban_benar' => 'C',
                'skor' => 10,
                'urutan' => 14
            ],
            [
                'pertanyaan' => 'Jika f(x) = eˣ, berapakah turunan pertamanya?',
                'pilihan' => ['A' => 'eˣ', 'B' => 'xeˣ', 'C' => 'eˣ/x', 'D' => 'ln(x)'],
                'jawaban_benar' => 'A',
                'skor' => 12,
                'urutan' => 15
            ],
            [
                'pertanyaan' => 'Tentukan nilai maksimum dari fungsi f(x) = -x² + 4x + 5',
                'pilihan' => ['A' => '7', 'B' => '8', 'C' => '9', 'D' => '10'],
                'jawaban_benar' => 'C',
                'skor' => 12,
                'urutan' => 16
            ],
            [
                'pertanyaan' => 'Hitunglah hasil dari (a + b)² jika a = 5 dan b = 3',
                'pilihan' => ['A' => '64', 'B' => '68', 'C' => '72', 'D' => '76'],
                'jawaban_benar' => 'A',
                'skor' => 10,
                'urutan' => 17
            ],
            [
                'pertanyaan' => 'Jika sebuah segitiga memiliki sisi 5, 12, dan 13, jenis segitiga apakah ini?',
                'pilihan' => ['A' => 'Segitiga Sama Sisi', 'B' => 'Segitiga Sama Kaki', 'C' => 'Segitiga Siku-siku', 'D' => 'Segitiga Sembarang'],
                'jawaban_benar' => 'C',
                'skor' => 10,
                'urutan' => 18
            ],
            [
                'pertanyaan' => 'Hitunglah hasil dari log₁₀(1000)',
                'pilihan' => ['A' => '1', 'B' => '2', 'C' => '3', 'D' => '4'],
                'jawaban_benar' => 'C',
                'skor' => 10,
                'urutan' => 19
            ],
            [
                'pertanyaan' => 'Tentukan nilai dari sin²(30°) + cos²(30°)',
                'pilihan' => ['A' => '0.5', 'B' => '0.75', 'C' => '1', 'D' => '1.25'],
                'jawaban_benar' => 'C',
                'skor' => 10,
                'urutan' => 20
            ],
            [
                'pertanyaan' => 'Jika deret geometri memiliki suku pertama 2 dan rasio 3, berapakah suku ke-5?',
                'pilihan' => ['A' => '144', 'B' => '162', 'C' => '180', 'D' => '216'],
                'jawaban_benar' => 'B',
                'skor' => 12,
                'urutan' => 21
            ],
            [
                'pertanyaan' => 'Hitunglah integral dari ∫(6x + 4) dx',
                'pilihan' => ['A' => '3x² + 4x + C', 'B' => '3x² + 2x + C', 'C' => '6x² + 4x + C', 'D' => '6x² + 2x + C'],
                'jawaban_benar' => 'A',
                'skor' => 12,
                'urutan' => 22
            ],
            [
                'pertanyaan' => 'Jika tan(45°) = 1, berapakah nilai dari cot(45°)?',
                'pilihan' => ['A' => '0', 'B' => '0.5', 'C' => '1', 'D' => '2'],
                'jawaban_benar' => 'C',
                'skor' => 10,
                'urutan' => 23
            ],
            [
                'pertanyaan' => 'Selesaikan persamaan: 3x - 7 = 2x + 5',
                'pilihan' => ['A' => 'x = 10', 'B' => 'x = 12', 'C' => 'x = 14', 'D' => 'x = 16'],
                'jawaban_benar' => 'B',
                'skor' => 10,
                'urutan' => 24
            ],
            [
                'pertanyaan' => 'Hitunglah volume sebuah kubus dengan panjang sisi 6 cm',
                'pilihan' => ['A' => '180 cm³', 'B' => '216 cm³', 'C' => '252 cm³', 'D' => '288 cm³'],
                'jawaban_benar' => 'B',
                'skor' => 10,
                'urutan' => 25
            ],
            [
                'pertanyaan' => 'Tentukan nilai dari lim(x→0) (sin x / x)',
                'pilihan' => ['A' => '0', 'B' => '0.5', 'C' => '1', 'D' => 'Tidak ada'],
                'jawaban_benar' => 'C',
                'skor' => 15,
                'urutan' => 26
            ],
            [
                'pertanyaan' => 'Jika sebuah persegi panjang memiliki panjang 8 cm dan lebar 5 cm, berapakah kelilingnya?',
                'pilihan' => ['A' => '24 cm', 'B' => '26 cm', 'C' => '28 cm', 'D' => '30 cm'],
                'jawaban_benar' => 'B',
                'skor' => 10,
                'urutan' => 27
            ],
            [
                'pertanyaan' => 'Hitunglah hasil dari (2³)²',
                'pilihan' => ['A' => '2⁵', 'B' => '2⁶', 'C' => '4³', 'D' => '8²'],
                'jawaban_benar' => 'B',
                'skor' => 10,
                'urutan' => 28
            ],
            [
                'pertanyaan' => 'Jika f(x) = ln(x), berapakah turunan pertamanya?',
                'pilihan' => ['A' => '1/x', 'B' => 'x', 'C' => 'ln(x)', 'D' => 'eˣ'],
                'jawaban_benar' => 'A',
                'skor' => 12,
                'urutan' => 29
            ],
            [
                'pertanyaan' => 'Tentukan nilai dari kombinasi C(5,2)',
                'pilihan' => ['A' => '8', 'B' => '10', 'C' => '12', 'D' => '15'],
                'jawaban_benar' => 'B',
                'skor' => 12,
                'urutan' => 30
            ],
        ];

        foreach ($questions as $q) {
            Question::create([
                'quiz_id' => $quiz->id,
                'pertanyaan' => $q['pertanyaan'],
                'tipe' => 'pilihan_ganda',
                'pilihan' => json_encode($q['pilihan']),
                'jawaban_benar' => $q['jawaban_benar'],
                'skor' => $q['skor'],
                'urutan' => $q['urutan'],
            ]);
        }

        $this->command->info('30 soal matematika berhasil ditambahkan!');
    }
}
