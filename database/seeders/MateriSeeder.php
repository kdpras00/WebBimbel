<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Materi;
use App\Models\Mapel;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class MateriSeeder extends Seeder
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

        // Get pengajar
        $pengajar = User::where('role', 'pengajar')->get();

        if ($pengajar->isEmpty()) {
            $this->command->warn('Tidak ada pengajar. Silakan jalankan UserSeeder terlebih dahulu.');
            return;
        }

        // Create materi directory if not exists
        $materiPath = public_path('materi');
        if (!file_exists($materiPath)) {
            mkdir($materiPath, 0755, true);
        }

        $pengajarIndex = 0;
        $pengajarCount = $pengajar->count();

        foreach ($mapelList as $mapel) {
            $pengajarTerpilih = $pengajar[$pengajarIndex % $pengajarCount];
            $pengajarIndex++;

            // Get kelas name safely
            $kelasNama = $mapel->kelas ? $mapel->kelas->nama : '';
            
            // Get materi data based on mapel
            $materiData = $this->getMateriDataForMapel($mapel->nama, $kelasNama);

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

            foreach ($materiData as $materi) {
                $filePath = null;

                // Handle file creation for PDF and Video
                if (in_array($materi['tipe'], ['pdf', 'video'])) {
                    // Only attempt to download if 'file_url' exists and is not null
                    if (isset($materi['file_url']) && $materi['file_url']) {
                        // Try to download from URL
                        $filePath = $this->downloadFile($materi['file_url'], $materi['tipe'], $mapel->nama);
                    } else {
                        // Create placeholder file directly
                        $filePath = $this->createPlaceholderFile($materi['tipe'], $mapel->nama, $materi['judul']);
                    }
                }

                Materi::create([
                    'judul' => $materi['judul'],
                    'deskripsi' => $materi['deskripsi'],
                    'tipe' => $materi['tipe'],
                    'file_path' => $filePath,
                    'konten' => $materi['konten'] ?? null,
                    'mapel_id' => $mapel->id,
                    'pengajar_id' => $pengajarTerpilih->id,
                    'jurusan' => $jurusan,
                ]);
            }

            $this->command->info("Materi untuk {$mapel->nama} ({$kelasNama}) berhasil dibuat.");
        }

        $this->command->info('Semua materi dummy berhasil dibuat!');
    }

    private function downloadFile(string $url, string $type, string $mapelName): ?string
    {
        $extension = $type === 'pdf' ? 'pdf' : 'mp4';
        $fileName = strtolower(str_replace(' ', '_', $mapelName)) . '_' . uniqid() . '.' . $extension;
        $filePath = 'materi/' . $fileName;
        $fullPath = public_path($filePath);

        try {
            $response = Http::timeout(30)->get($url);

            if ($response->successful() && $response->body()) {
                file_put_contents($fullPath, $response->body());
                $this->command->info("  ✓ File berhasil didownload: {$filePath}");
                return $filePath;
            }
        } catch (\Exception $e) {
            $this->command->warn("  ⚠ Gagal download file dari {$url}: " . $e->getMessage());
        }

        // Fallback: create placeholder file if download fails
        return $this->createPlaceholderFile($type, $mapelName, 'Sample File');
    }

    private function createPlaceholderFile(string $type, string $mapelName, string $title): string
    {
        $extension = $type === 'pdf' ? 'pdf' : 'mp4';
        $fileName = strtolower(str_replace(' ', '_', $mapelName)) . '_' . uniqid() . '.' . $extension;
        $filePath = 'materi/' . $fileName;
        $fullPath = public_path($filePath);

        if ($type === 'pdf') {
            // Create a minimal valid PDF placeholder
            $pdfContent = "%PDF-1.4\n1 0 obj\n<<\n/Type /Catalog\n/Pages 2 0 R\n>>\nendobj\n2 0 obj\n<<\n/Type /Pages\n/Kids [3 0 R]\n/Count 1\n>>\nendobj\n3 0 obj\n<<\n/Type /Page\n/Parent 2 0 R\n/MediaBox [0 0 612 792]\n/Contents 4 0 R\n>>\nendobj\n4 0 obj\n<<\n/Length 44\n>>\nstream\nBT\n/F1 12 Tf\n100 700 Td\n(Sample PDF) Tj\nET\nendstream\nendobj\nxref\n0 5\n0000000000 65535 f\n0000000009 00000 n\n0000000058 00000 n\n0000000115 00000 n\n0000000271 00000 n\ntrailer\n<<\n/Size 5\n/Root 1 0 R\n>>\nstartxref\n365\n%%EOF";
            file_put_contents($fullPath, $pdfContent);
            $this->command->info("  ✓ Placeholder PDF dibuat: {$filePath}");
        } else {
            // For video, create a small text file as placeholder
            $videoPlaceholder = "Video placeholder file\nThis is a placeholder for video content: {$title}\nIn production, replace this with actual video file.";
            file_put_contents($fullPath, $videoPlaceholder);
            $this->command->info("  ✓ Placeholder video dibuat: {$filePath}");
        }

        return $filePath;
    }

    private function getMateriDataForMapel(string $mapelName, string $kelasName): array
    {
        $mapelName = strtolower($mapelName);
        $kelasInfo = $kelasName ? " untuk {$kelasName}" : "";

        switch ($mapelName) {
            case 'matematika':
                return [
                    [
                        'judul' => "Pengenalan Aljabar{$kelasInfo}",
                        'deskripsi' => "Materi pengenalan dasar aljabar{$kelasInfo}",
                        'tipe' => 'pdf',
                        'file_url' => null,
                        'konten' => null,
                    ],
                    [
                        'judul' => "Video Pembelajaran Geometri{$kelasInfo}",
                        'deskripsi' => "Video pembelajaran tentang geometri dasar{$kelasInfo}",
                        'tipe' => 'video',
                        'file_url' => null,
                        'konten' => null,
                    ],
                    [
                        'judul' => "Materi Teks: Trigonometri Dasar{$kelasInfo}",
                        'deskripsi' => "Penjelasan lengkap tentang trigonometri dasar{$kelasInfo}",
                        'tipe' => 'teks',
                        'konten' => "Trigonometri adalah cabang matematika yang mempelajari hubungan antara sudut dan sisi dalam segitiga. Konsep dasar trigonometri meliputi sin, cos, dan tan yang merupakan perbandingan sisi-sisi dalam segitiga siku-siku.\n\n1. Sinus (sin): Perbandingan antara sisi depan dengan sisi miring\n2. Cosinus (cos): Perbandingan antara sisi samping dengan sisi miring\n3. Tangen (tan): Perbandingan antara sisi depan dengan sisi samping\n\nTrigonometri sangat berguna dalam berbagai bidang seperti fisika, teknik, dan navigasi.",
                    ],
                ];

            case 'fisika':
                return [
                    [
                        'judul' => "Hukum Newton{$kelasInfo}",
                        'deskripsi' => "Materi lengkap tentang hukum Newton{$kelasInfo}",
                        'tipe' => 'pdf',
                        'file_url' => null,
                        'konten' => null,
                    ],
                    [
                        'judul' => "Video: Gerak Lurus Beraturan{$kelasInfo}",
                        'deskripsi' => "Video penjelasan tentang gerak lurus beraturan{$kelasInfo}",
                        'tipe' => 'video',
                        'file_url' => null,
                        'konten' => null,
                    ],
                    [
                        'judul' => "Materi Teks: Energi dan Usaha{$kelasInfo}",
                        'deskripsi' => "Penjelasan tentang konsep energi dan usaha{$kelasInfo}",
                        'tipe' => 'teks',
                        'konten' => "Energi adalah kemampuan untuk melakukan usaha. Terdapat berbagai bentuk energi:\n\n1. Energi Kinetik: Energi yang dimiliki benda karena geraknya\n   EK = ½ mv²\n\n2. Energi Potensial: Energi yang dimiliki benda karena posisinya\n   EP = mgh\n\n3. Energi Mekanik: Jumlah energi kinetik dan potensial\n   EM = EK + EP\n\nUsaha adalah hasil kali gaya dengan perpindahan:\nW = F × s",
                    ],
                ];

            case 'kimia':
                return [
                    [
                        'judul' => "Struktur Atom{$kelasInfo}",
                        'deskripsi' => "Materi tentang struktur atom dan tabel periodik{$kelasInfo}",
                        'tipe' => 'pdf',
                        'file_url' => null,
                        'konten' => null,
                    ],
                    [
                        'judul' => "Video: Reaksi Kimia{$kelasInfo}",
                        'deskripsi' => "Video demonstrasi reaksi kimia{$kelasInfo}",
                        'tipe' => 'video',
                        'file_url' => null,
                        'konten' => null,
                    ],
                    [
                        'judul' => "Materi Teks: Ikatan Kimia{$kelasInfo}",
                        'deskripsi' => "Penjelasan tentang berbagai jenis ikatan kimia{$kelasInfo}",
                        'tipe' => 'teks',
                        'konten' => "Ikatan kimia adalah gaya yang mengikat atom-atom dalam molekul atau senyawa. Terdapat beberapa jenis ikatan:\n\n1. Ikatan Ionik: Terjadi antara ion positif dan negatif melalui transfer elektron\n   Contoh: NaCl (Natrium Klorida)\n\n2. Ikatan Kovalen: Terjadi karena pemakaian bersama pasangan elektron\n   Contoh: H₂O (Air), CO₂ (Karbon Dioksida)\n\n3. Ikatan Hidrogen: Ikatan khusus yang terjadi pada molekul yang mengandung H dengan F, O, atau N\n\n4. Ikatan Logam: Ikatan yang terjadi pada logam, elektron bebas bergerak",
                    ],
                ];

            case 'biologi':
                return [
                    [
                        'judul' => "Sistem Pencernaan{$kelasInfo}",
                        'deskripsi' => "Materi lengkap tentang sistem pencernaan manusia{$kelasInfo}",
                        'tipe' => 'pdf',
                        'file_url' => null,
                        'konten' => null,
                    ],
                    [
                        'judul' => "Video: Sel dan Organel{$kelasInfo}",
                        'deskripsi' => "Video pembelajaran tentang struktur sel{$kelasInfo}",
                        'tipe' => 'video',
                        'file_url' => null,
                        'konten' => null,
                    ],
                    [
                        'judul' => "Materi Teks: Fotosintesis{$kelasInfo}",
                        'deskripsi' => "Penjelasan proses fotosintesis pada tumbuhan{$kelasInfo}",
                        'tipe' => 'teks',
                        'konten' => "Fotosintesis adalah proses pembuatan makanan oleh tumbuhan dengan bantuan sinar matahari. Proses ini terjadi di kloroplas.\n\nPersamaan reaksi fotosintesis:\n6CO₂ + 6H₂O + energi cahaya → C₆H₁₂O₆ + 6O₂\n\nTahapan fotosintesis:\n1. Reaksi Terang: Terjadi di grana, memerlukan cahaya, menghasilkan ATP dan NADPH\n2. Reaksi Gelap (Siklus Calvin): Terjadi di stroma, tidak memerlukan cahaya langsung, menggunakan ATP dan NADPH untuk membentuk glukosa\n\nFaktor yang mempengaruhi fotosintesis:\n- Intensitas cahaya\n- Konsentrasi CO₂\n- Suhu\n- Ketersediaan air",
                    ],
                ];

            case 'bahasa indonesia':
                return [
                    [
                        'judul' => "Tata Bahasa Indonesia{$kelasInfo}",
                        'deskripsi' => "Materi tentang tata bahasa Indonesia{$kelasInfo}",
                        'tipe' => 'pdf',
                        'file_url' => null,
                        'konten' => null,
                    ],
                    [
                        'judul' => "Video: Menulis Esai{$kelasInfo}",
                        'deskripsi' => "Video tutorial menulis esai yang baik{$kelasInfo}",
                        'tipe' => 'video',
                        'file_url' => null,
                        'konten' => null,
                    ],
                    [
                        'judul' => "Materi Teks: Kalimat Efektif{$kelasInfo}",
                        'deskripsi' => "Penjelasan tentang kalimat efektif dalam bahasa Indonesia{$kelasInfo}",
                        'tipe' => 'teks',
                        'konten' => "Kalimat efektif adalah kalimat yang dapat mengungkapkan gagasan penutur/penulis secara tepat sehingga pembaca/pendengar dapat memahami pikiran tersebut dengan mudah, jelas, dan lengkap seperti yang dimaksud penutur/penulis.\n\nCiri-ciri kalimat efektif:\n1. Kesepadanan: Memiliki subjek dan predikat yang jelas\n2. Keparalelan: Menggunakan bentuk kata yang sejajar\n3. Ketegasan: Menempatkan kata yang dianggap penting di awal kalimat\n4. Kehematan: Menghindari penggunaan kata yang tidak perlu\n5. Kevariasian: Menggunakan variasi struktur kalimat\n6. Kelogisan: Ide kalimat dapat diterima oleh akal sehat",
                    ],
                ];

            case 'bahasa inggris':
                return [
                    [
                        'judul' => "English Grammar{$kelasInfo}",
                        'deskripsi' => "Complete English grammar guide{$kelasInfo}",
                        'tipe' => 'pdf',
                        'file_url' => null,
                        'konten' => null,
                    ],
                    [
                        'judul' => "Video: English Conversation{$kelasInfo}",
                        'deskripsi' => "Video pembelajaran percakapan bahasa Inggris{$kelasInfo}",
                        'tipe' => 'video',
                        'file_url' => null,
                        'konten' => null,
                    ],
                    [
                        'judul' => "Materi Teks: Tenses in English{$kelasInfo}",
                        'deskripsi' => "Penjelasan tentang tenses dalam bahasa Inggris{$kelasInfo}",
                        'tipe' => 'teks',
                        'konten' => "Tenses adalah bentuk kata kerja yang menunjukkan waktu terjadinya suatu peristiwa. Berikut adalah tenses utama dalam bahasa Inggris:\n\n1. Present Simple: I go to school every day.\n   Digunakan untuk kebiasaan dan fakta umum\n\n2. Present Continuous: I am studying now.\n   Digunakan untuk aktivitas yang sedang berlangsung\n\n3. Past Simple: I went to school yesterday.\n   Digunakan untuk kejadian di masa lalu yang sudah selesai\n\n4. Past Continuous: I was studying when you called.\n   Digunakan untuk aktivitas yang sedang berlangsung di masa lalu\n\n5. Future Simple: I will go to school tomorrow.\n   Digunakan untuk rencana di masa depan\n\n6. Present Perfect: I have finished my homework.\n   Digunakan untuk kejadian yang sudah selesai tapi masih relevan",
                    ],
                ];

            default:
                return [
                    [
                        'judul' => "Materi {$mapelName}{$kelasInfo}",
                        'deskripsi' => "Materi pembelajaran {$mapelName}{$kelasInfo}",
                        'tipe' => 'teks',
                        'konten' => "Ini adalah materi contoh untuk {$mapelName}{$kelasInfo}.",
                    ],
                ];
        }
    }
}
