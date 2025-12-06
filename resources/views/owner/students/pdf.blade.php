<!DOCTYPE html>
<html>
<head>
    <title>Laporan Progress Siswa</title>
    <style>
        body { font-family: Arial, sans-serif; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #2563EB; padding-bottom: 20px; }
        .header h1 { color: #2563EB; margin: 0; font-size: 24px; }
        .header p { color: #666; margin: 5px 0 0; }
        
        .student-info { margin-bottom: 30px; background: #f8fafc; padding: 20px; border-radius: 8px; }
        .student-info table { width: 100%; }
        .student-info td { padding: 5px; }
        .label { font-weight: bold; width: 150px; color: #64748b; }
        
        .stats-grid { display: table; width: 100%; margin-bottom: 30px; }
        .stat-card { display: table-cell; width: 25%; text-align: center; padding: 15px; background: #fff; border: 1px solid #e2e8f0; }
        .stat-value { font-size: 24px; font-weight: bold; color: #2563EB; display: block; margin-bottom: 5px; }
        .stat-label { font-size: 12px; color: #64748b; text-transform: uppercase; }

        .results-table { width: 100%; border-collapse: collapse; font-size: 12px; }
        .results-table th { background: #f1f5f9; padding: 10px; text-align: left; border-bottom: 2px solid #e2e8f0; color: #475569; }
        .results-table td { padding: 10px; border-bottom: 1px solid #e2e8f0; }
        .results-table tr:nth-child(even) { background: #f8fafc; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge { padding: 3px 8px; border-radius: 99px; font-size: 10px; font-weight: bold; }
        .badge-green { background: #dcfce7; color: #166534; }
        .badge-red { background: #fee2e2; color: #991b1b; }
        
        .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #94a3b8; border-top: 1px solid #e2e8f0; padding-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>BIMBEL HIKARI</h1>
        <p>Laporan Progress Siswa</p>
        <p>Tanggal Cetak: {{ date('d F Y') }}</p>
    </div>

    <div class="student-info">
        <table>
            <tr>
                <td class="label">Nama Siswa:</td>
                <td><strong>{{ $student->name }}</strong></td>
            </tr>
            <tr>
                <td class="label">Email:</td>
                <td>{{ $student->email }}</td>
            </tr>
            <tr>
                <td class="label">Jurusan:</td>
                <td>{{ $student->jurusan ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <span class="stat-value">{{ $stats['total_quiz'] }}</span>
            <span class="stat-label">Total Quiz</span>
        </div>
        <div class="stat-card">
            <span class="stat-value">{{ number_format($stats['avg_score'], 1) }}</span>
            <span class="stat-label">Rata-rata</span>
        </div>
        <div class="stat-card">
            <span class="stat-value">{{ number_format($stats['highest_score'], 1) }}</span>
            <span class="stat-label">Tertinggi</span>
        </div>
        <div class="stat-card">
            <span class="stat-value">{{ number_format($stats['lowest_score'], 1) }}</span>
            <span class="stat-label">Terendah</span>
        </div>
    </div>

    <h3>Riwayat Quiz Lengkap</h3>
    <table class="results-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Judul Quiz</th>
                <th>Mapel</th>
                <th>Pengajar</th>
                <th class="text-center">Nilai</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($student->quizResults as $index => $result)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $result->created_at->format('d/m/Y') }}</td>
                    <td>{{ $result->quiz->judul }}</td>
                    <td>{{ $result->quiz->mapel->nama }}</td>
                    <td>{{ $result->quiz->pengajar->name }}</td>
                    <td class="text-center" style="font-weight: bold;">{{ number_format($result->nilai, 1) }}</td>
                    <td class="text-center">
                        <span class="badge {{ $result->nilai >= 70 ? 'badge-green' : 'badge-red' }}">
                            {{ $result->nilai >= 70 ? 'LULUS' : 'TIDAK LULUS' }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada data quiz.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dicetak secara otomatis oleh sistem Web Bimbel HIKARI.</p>
    </div>
</body>
</html>
