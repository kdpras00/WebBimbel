<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Nilai Belajar Anak</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }
        .header {
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        .info-section {
            margin-bottom: 25px;
            padding: 15px;
            background: #f8f9fa;
            border-left: 4px solid #2563eb;
        }
        .info-section p {
            margin: 5px 0;
        }
        .child-section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }
        .child-header {
            background: #e3f2fd;
            padding: 15px;
            border-left: 4px solid #2196f3;
            margin-bottom: 15px;
        }
        .child-header h2 {
            font-size: 18px;
            color: #1976d2;
            margin-bottom: 10px;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .stat-box {
            display: table-cell;
            width: 33.33%;
            padding: 10px;
            text-align: center;
            background: white;
            border: 1px solid #ddd;
        }
        .stat-box:not(:last-child) {
            border-right: none;
        }
        .stat-label {
            font-size: 10px;
            color: #666;
            margin-bottom: 5px;
        }
        .stat-value {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: white;
        }
        thead {
            background: #2563eb;
            color: white;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        th {
            font-weight: bold;
            font-size: 11px;
        }
        td {
            font-size: 10px;
        }
        tbody tr:nth-child(even) {
            background: #f9fafb;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-green {
            background: #d1fae5;
            color: #065f46;
        }
        .badge-yellow {
            background: #fef3c7;
            color: #92400e;
        }
        .badge-red {
            background: #fee2e2;
            color: #991b1b;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .no-data {
            text-align: center;
            padding: 30px;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Nilai Belajar Anak</h1>
        <p>Dicetak pada: {{ date('d F Y H:i:s') }}</p>
    </div>

    <div class="info-section">
        <p><strong>Nama Wali Murid:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Total Anak:</strong> {{ $anak->count() }}</p>
        <p><strong>Total Hasil Quiz:</strong> {{ $results->count() }}</p>
    </div>

    @if($anak->count() > 0)
        @foreach($anak as $child)
            @php
                $progress = $progressData[$child->id] ?? ['avg_score' => 0, 'total_quiz' => 0, 'best_score' => 0];
                $childResults = $results->where('siswa_id', $child->id);
            @endphp
            
            <div class="child-section">
                <div class="child-header">
                    <h2>{{ $child->name }}</h2>
                    <div class="stats-grid">
                        <div class="stat-box">
                            <div class="stat-label">Rata-rata Nilai</div>
                            <div class="stat-value">{{ number_format($progress['avg_score'], 1) }}</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-label">Total Quiz</div>
                            <div class="stat-value">{{ $progress['total_quiz'] }}</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-label">Nilai Tertinggi</div>
                            <div class="stat-value">{{ $progress['best_score'] }}</div>
                        </div>
                    </div>
                </div>

                @if($childResults->count() > 0)
                    <table>
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Quiz</th>
                                <th>Mata Pelajaran</th>
                                <th>Nilai</th>
                                <th>Jawaban Benar</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($childResults as $index => $result)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $result->quiz->judul }}</td>
                                    <td>{{ $result->quiz->mapel->nama }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($result->nilai >= 80) badge-green
                                            @elseif($result->nilai >= 60) badge-yellow
                                            @else badge-red
                                            @endif">
                                            {{ $result->nilai }}
                                        </span>
                                    </td>
                                    <td>{{ $result->jawaban_benar }}/{{ $result->total_soal }}</td>
                                    <td>{{ $result->created_at->format('d M Y H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="no-data">
                        <p>Belum ada hasil quiz untuk {{ $child->name }}</p>
                    </div>
                @endif
            </div>
        @endforeach
    @else
        <div class="no-data">
            <p>Belum ada data anak yang terdaftar</p>
        </div>
    @endif

    <div class="footer">
        <p>Dokumen ini dibuat secara otomatis oleh sistem WebBimbel</p>
        <p>© {{ date('Y') }} WebBimbel - Laporan Nilai Belajar</p>
    </div>
</body>
</html>

