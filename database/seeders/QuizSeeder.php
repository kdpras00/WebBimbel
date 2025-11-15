<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\Mapel;
use Illuminate\Support\Facades\DB;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mapelList = Mapel::with('kelas')->get();

        if ($mapelList->isEmpty()) {
            $this->command->warn('Tidak ada mapel. Silakan jalankan MapelSeeder terlebih dahulu.');
            return;
        }

        foreach ($mapelList as $mapel) {
            // Get pengajar for this mapel
            $pengajar = DB::table('kelas_pengajar')
                ->where('mapel_id', $mapel->id)
                ->first();

            if (!$pengajar) {
                $this->command->warn("Mapel {$mapel->nama} tidak memiliki pengajar. Dilewati.");
                continue;
            }

            // Create quiz based on mapel name
            $quizData = $this->getQuizDataForMapel($mapel->nama, $mapel->kelas->nama ?? '');
            
            // Determine jurusan based on kelas
            $kelasNama = $mapel->kelas->nama ?? '';
            $kelasMatch = preg_match('/\d+/', $kelasNama, $matches);
            $kelasNumber = $kelasMatch && isset($matches[0]) ? (int)$matches[0] : 0;
            
            // Assign jurusan randomly for kelas 10-12, null for kelas 1-6
            $jurusan = null;
            if ($kelasNumber >= 10 && $kelasNumber <= 12) {
                $jurusanOptions = ['IPA', 'IPS', 'Bahasa', 'Agama'];
                // Randomly assign jurusan or null (for all jurusan)
                if (rand(0, 1)) {
                    $jurusan = $jurusanOptions[array_rand($jurusanOptions)];
                }
            }
            
            $quiz = Quiz::create([
                'judul' => $quizData['judul'],
                'deskripsi' => $quizData['deskripsi'],
                'mapel_id' => $mapel->id,
                'pengajar_id' => $pengajar->pengajar_id,
                'durasi' => $quizData['durasi'],
                'is_published' => true,
                'jurusan' => $jurusan,
            ]);

            // Create questions for this quiz
            foreach ($quizData['questions'] as $index => $q) {
                Question::create([
                    'quiz_id' => $quiz->id,
                    'pertanyaan' => $q['pertanyaan'],
                    'tipe' => 'pilihan_ganda',
                    'pilihan' => json_encode($q['pilihan']),
                    'jawaban_benar' => $q['jawaban_benar'],
                    'skor' => $q['skor'],
                    'urutan' => $index + 1,
                ]);
            }

            $this->command->info("Quiz '{$quiz->judul}' dengan " . count($quizData['questions']) . " soal berhasil dibuat untuk mapel {$mapel->nama}.");
        }

        $this->command->info('Semua quiz dummy berhasil dibuat!');
    }

    private function getQuizDataForMapel(string $mapelName, string $kelasName): array
    {
        $mapelName = strtolower($mapelName);
        $kelasInfo = $kelasName ? " untuk {$kelasName}" : "";

        switch ($mapelName) {
            case 'matematika':
                return [
                    'judul' => "Quiz Matematika Dasar{$kelasInfo}",
                    'deskripsi' => "Quiz untuk menguji pemahaman dasar matematika{$kelasInfo}",
                    'durasi' => 60,
                    'questions' => [
                        [
                            'pertanyaan' => 'Berapakah hasil dari 15 + 27?',
                            'pilihan' => ['A' => '40', 'B' => '42', 'C' => '44', 'D' => '45'],
                            'jawaban_benar' => 'B',
                            'skor' => 10,
                        ],
                        [
                            'pertanyaan' => 'Jika x = 5, berapakah nilai dari 2x + 3?',
                            'pilihan' => ['A' => '11', 'B' => '13', 'C' => '15', 'D' => '17'],
                            'jawaban_benar' => 'B',
                            'skor' => 10,
                        ],
                        [
                            'pertanyaan' => 'Berapakah luas persegi dengan sisi 8 cm?',
                            'pilihan' => ['A' => '32 cm²', 'B' => '48 cm²', 'C' => '64 cm²', 'D' => '80 cm²'],
                            'jawaban_benar' => 'C',
                            'skor' => 10,
                        ],
                        [
                            'pertanyaan' => 'Hitunglah hasil dari 144 ÷ 12',
                            'pilihan' => ['A' => '10', 'B' => '11', 'C' => '12', 'D' => '13'],
                            'jawaban_benar' => 'C',
                            'skor' => 10,
                        ],
                        [
                            'pertanyaan' => 'Berapakah keliling lingkaran dengan jari-jari 7 cm? (π = 22/7)',
                            'pilihan' => ['A' => '44 cm', 'B' => '48 cm', 'C' => '50 cm', 'D' => '52 cm'],
                            'jawaban_benar' => 'A',
                            'skor' => 15,
                        ],
                    ],
                ];

            case 'fisika':
                return [
                    'judul' => "Quiz Fisika Dasar{$kelasInfo}",
                    'deskripsi' => "Quiz untuk menguji pemahaman dasar fisika{$kelasInfo}",
                    'durasi' => 60,
                    'questions' => [
                        [
                            'pertanyaan' => 'Apa satuan SI untuk gaya?',
                            'pilihan' => ['A' => 'Joule', 'B' => 'Newton', 'C' => 'Watt', 'D' => 'Pascal'],
                            'jawaban_benar' => 'B',
                            'skor' => 10,
                        ],
                        [
                            'pertanyaan' => 'Jika sebuah benda bergerak dengan kecepatan 20 m/s selama 5 detik, berapakah jarak yang ditempuh?',
                            'pilihan' => ['A' => '80 m', 'B' => '100 m', 'C' => '120 m', 'D' => '150 m'],
                            'jawaban_benar' => 'B',
                            'skor' => 15,
                        ],
                        [
                            'pertanyaan' => 'Hukum Newton pertama menyatakan bahwa...',
                            'pilihan' => ['A' => 'F = ma', 'B' => 'Setiap aksi memiliki reaksi', 'C' => 'Benda diam akan tetap diam', 'D' => 'Energi tidak dapat diciptakan'],
                            'jawaban_benar' => 'C',
                            'skor' => 12,
                        ],
                        [
                            'pertanyaan' => 'Berapakah percepatan gravitasi di permukaan bumi?',
                            'pilihan' => ['A' => '8 m/s²', 'B' => '9.8 m/s²', 'C' => '10 m/s²', 'D' => '12 m/s²'],
                            'jawaban_benar' => 'B',
                            'skor' => 10,
                        ],
                        [
                            'pertanyaan' => 'Apa yang dimaksud dengan energi kinetik?',
                            'pilihan' => ['A' => 'Energi karena posisi', 'B' => 'Energi karena gerak', 'C' => 'Energi karena panas', 'D' => 'Energi karena listrik'],
                            'jawaban_benar' => 'B',
                            'skor' => 10,
                        ],
                    ],
                ];

            case 'kimia':
                return [
                    'judul' => "Quiz Kimia Dasar{$kelasInfo}",
                    'deskripsi' => "Quiz untuk menguji pemahaman dasar kimia{$kelasInfo}",
                    'durasi' => 60,
                    'questions' => [
                        [
                            'pertanyaan' => 'Berapakah nomor atom Hidrogen?',
                            'pilihan' => ['A' => '1', 'B' => '2', 'C' => '3', 'D' => '4'],
                            'jawaban_benar' => 'A',
                            'skor' => 10,
                        ],
                        [
                            'pertanyaan' => 'Rumus kimia untuk air adalah...',
                            'pilihan' => ['A' => 'H₂O', 'B' => 'CO₂', 'C' => 'O₂', 'D' => 'H₂SO₄'],
                            'jawaban_benar' => 'A',
                            'skor' => 10,
                        ],
                        [
                            'pertanyaan' => 'Apa yang dimaksud dengan pH?',
                            'pilihan' => ['A' => 'Tingkat keasaman', 'B' => 'Tingkat kebasaan', 'C' => 'Tingkat konsentrasi', 'D' => 'Tingkat suhu'],
                            'jawaban_benar' => 'A',
                            'skor' => 12,
                        ],
                        [
                            'pertanyaan' => 'Reaksi yang melepaskan panas disebut...',
                            'pilihan' => ['A' => 'Endoterm', 'B' => 'Eksoterm', 'C' => 'Isoterm', 'D' => 'Adiabatik'],
                            'jawaban_benar' => 'B',
                            'skor' => 10,
                        ],
                        [
                            'pertanyaan' => 'Berapakah jumlah elektron maksimal pada kulit pertama (K)?',
                            'pilihan' => ['A' => '2', 'B' => '8', 'C' => '18', 'D' => '32'],
                            'jawaban_benar' => 'A',
                            'skor' => 10,
                        ],
                    ],
                ];

            case 'biologi':
                return [
                    'judul' => "Quiz Biologi Dasar{$kelasInfo}",
                    'deskripsi' => "Quiz untuk menguji pemahaman dasar biologi{$kelasInfo}",
                    'durasi' => 60,
                    'questions' => [
                        [
                            'pertanyaan' => 'Organel sel yang berfungsi sebagai tempat respirasi sel adalah...',
                            'pilihan' => ['A' => 'Mitokondria', 'B' => 'Kloroplas', 'C' => 'Nukleus', 'D' => 'Ribosom'],
                            'jawaban_benar' => 'A',
                            'skor' => 12,
                        ],
                        [
                            'pertanyaan' => 'Proses fotosintesis terjadi di...',
                            'pilihan' => ['A' => 'Mitokondria', 'B' => 'Kloroplas', 'C' => 'Nukleus', 'D' => 'Vakuola'],
                            'jawaban_benar' => 'B',
                            'skor' => 10,
                        ],
                        [
                            'pertanyaan' => 'Sistem peredaran darah manusia termasuk sistem...',
                            'pilihan' => ['A' => 'Pencernaan', 'B' => 'Pernapasan', 'C' => 'Sirkulasi', 'D' => 'Ekskresi'],
                            'jawaban_benar' => 'C',
                            'skor' => 10,
                        ],
                        [
                            'pertanyaan' => 'DNA berada di dalam...',
                            'pilihan' => ['A' => 'Sitoplasma', 'B' => 'Nukleus', 'C' => 'Mitokondria', 'D' => 'Ribosom'],
                            'jawaban_benar' => 'B',
                            'skor' => 10,
                        ],
                        [
                            'pertanyaan' => 'Jantung manusia memiliki berapa ruang?',
                            'pilihan' => ['A' => '2', 'B' => '3', 'C' => '4', 'D' => '5'],
                            'jawaban_benar' => 'C',
                            'skor' => 10,
                        ],
                    ],
                ];

            case 'bahasa indonesia':
                return [
                    'judul' => "Quiz Bahasa Indonesia{$kelasInfo}",
                    'deskripsi' => "Quiz untuk menguji pemahaman bahasa Indonesia{$kelasInfo}",
                    'durasi' => 45,
                    'questions' => [
                        [
                            'pertanyaan' => 'Apa yang dimaksud dengan kalimat efektif?',
                            'pilihan' => ['A' => 'Kalimat yang panjang', 'B' => 'Kalimat yang jelas dan mudah dipahami', 'C' => 'Kalimat yang menggunakan bahasa asing', 'D' => 'Kalimat yang bertele-tele'],
                            'jawaban_benar' => 'B',
                            'skor' => 10,
                        ],
                        [
                            'pertanyaan' => 'Kata "menggunakan" termasuk jenis kata...',
                            'pilihan' => ['A' => 'Kata benda', 'B' => 'Kata kerja', 'C' => 'Kata sifat', 'D' => 'Kata keterangan'],
                            'jawaban_benar' => 'B',
                            'skor' => 10,
                        ],
                        [
                            'pertanyaan' => 'Apa yang dimaksud dengan sinonim?',
                            'pilihan' => ['A' => 'Kata yang berlawanan arti', 'B' => 'Kata yang sama artinya', 'C' => 'Kata yang mirip bunyi', 'D' => 'Kata yang sama bunyi'],
                            'jawaban_benar' => 'B',
                            'skor' => 10,
                        ],
                        [
                            'pertanyaan' => 'Kalimat "Saya pergi ke sekolah" termasuk kalimat...',
                            'pilihan' => ['A' => 'Kalimat pasif', 'B' => 'Kalimat aktif', 'C' => 'Kalimat tanya', 'D' => 'Kalimat perintah'],
                            'jawaban_benar' => 'B',
                            'skor' => 10,
                        ],
                        [
                            'pertanyaan' => 'Apa fungsi tanda koma dalam kalimat?',
                            'pilihan' => ['A' => 'Mengakhiri kalimat', 'B' => 'Memisahkan unsur kalimat', 'C' => 'Menanyakan sesuatu', 'D' => 'Mengekspresikan perasaan'],
                            'jawaban_benar' => 'B',
                            'skor' => 10,
                        ],
                    ],
                ];

            case 'bahasa inggris':
                return [
                    'judul' => "English Quiz{$kelasInfo}",
                    'deskripsi' => "Quiz to test basic English understanding{$kelasInfo}",
                    'durasi' => 45,
                    'questions' => [
                        [
                            'pertanyaan' => 'What is the past tense of "go"?',
                            'pilihan' => ['A' => 'goed', 'B' => 'went', 'C' => 'gone', 'D' => 'going'],
                            'jawaban_benar' => 'B',
                            'skor' => 10,
                        ],
                        [
                            'pertanyaan' => 'Choose the correct sentence:',
                            'pilihan' => ['A' => 'I am go to school', 'B' => 'I go to school', 'C' => 'I goes to school', 'D' => 'I going to school'],
                            'jawaban_benar' => 'B',
                            'skor' => 12,
                        ],
                        [
                            'pertanyaan' => 'What does "beautiful" mean?',
                            'pilihan' => ['A' => 'Cantik', 'B' => 'Jelek', 'C' => 'Besar', 'D' => 'Kecil'],
                            'jawaban_benar' => 'A',
                            'skor' => 10,
                        ],
                        [
                            'pertanyaan' => 'Complete: "I ___ a student."',
                            'pilihan' => ['A' => 'am', 'B' => 'is', 'C' => 'are', 'D' => 'be'],
                            'jawaban_benar' => 'A',
                            'skor' => 10,
                        ],
                        [
                            'pertanyaan' => 'What is the plural form of "child"?',
                            'pilihan' => ['A' => 'childs', 'B' => 'children', 'C' => 'childes', 'D' => 'childrens'],
                            'jawaban_benar' => 'B',
                            'skor' => 10,
                        ],
                    ],
                ];

            default:
                return [
                    'judul' => "Quiz {$mapelName}{$kelasInfo}",
                    'deskripsi' => "Quiz untuk menguji pemahaman {$mapelName}{$kelasInfo}",
                    'durasi' => 60,
                    'questions' => [
                        [
                            'pertanyaan' => 'Pertanyaan contoh untuk quiz ini?',
                            'pilihan' => ['A' => 'Pilihan A', 'B' => 'Pilihan B', 'C' => 'Pilihan C', 'D' => 'Pilihan D'],
                            'jawaban_benar' => 'A',
                            'skor' => 10,
                        ],
                    ],
                ];
        }
    }
}

