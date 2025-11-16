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
        @page {
            margin: 1.5cm;
            size: A4 portrait;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .header {
            background: #2563eb;
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 25px;
            page-break-after: avoid;
        }
        .header h1 {
            font-size: 22px;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .header p {
            font-size: 12px;
            opacity: 0.95;
        }
        .info-section {
            margin-bottom: 20px;
            padding: 12px;
            background: #f8f9fa;
            border-left: 4px solid #2563eb;
        }
        .info-section p {
            margin: 3px 0;
            font-size: 11px;
        }
        .child-section {
            margin-bottom: 35px;
            page-break-inside: avoid;
        }
        .child-header {
            background: #e3f2fd;
            padding: 12px;
            border-left: 4px solid #2196f3;
            margin-bottom: 12px;
            page-break-after: avoid;
        }
        .child-header h2 {
            font-size: 16px;
            color: #1976d2;
            margin-bottom: 8px;
            font-weight: bold;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 0;
            border-collapse: separate;
            border-spacing: 0;
        }
        .stat-box {
            display: table-cell;
            width: 33.33%;
            padding: 8px;
            text-align: center;
            background: white;
            border: 1px solid #ddd;
            vertical-align: middle;
        }
        .stat-box:not(:last-child) {
            border-right: none;
        }
        .stat-label {
            font-size: 9px;
            color: #666;
            margin-bottom: 4px;
            font-weight: normal;
        }
        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: white;
            page-break-inside: auto;
        }
        thead {
            background: #2563eb;
            color: white;
            display: table-header-group;
        }
        tbody {
            display: table-row-group;
        }
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        th, td {
            padding: 8px 6px;
            text-align: left;
            border: 1px solid #ddd;
            word-wrap: break-word;
            vertical-align: top;
        }
        th {
            font-weight: bold;
            font-size: 10px;
            text-align: center;
        }
        td {
            font-size: 10px;
        }
        tbody tr:nth-child(even) {
            background: #f9fafb;
        }
        .badge {
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            display: inline-block;
            text-align: center;
            min-width: 35px;
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
            margin-top: 25px;
            padding-top: 12px;
            border-top: 2px solid #ddd;
            text-align: center;
            font-size: 9px;
            color: #666;
            page-break-inside: avoid;
        }
        .no-data {
            text-align: center;
            padding: 25px;
            color: #999;
            font-style: italic;
            font-size: 11px;
        }
        /* Prevent table overflow */
        table {
            table-layout: fixed;
        }
        th:nth-child(1), td:nth-child(1) {
            width: 5%;
            text-align: center;
        }
        th:nth-child(2), td:nth-child(2) {
            width: 30%;
        }
        th:nth-child(3), td:nth-child(3) {
            width: 18%;
        }
        th:nth-child(4), td:nth-child(4) {
            width: 10%;
            text-align: center;
        }
        th:nth-child(5), td:nth-child(5) {
            width: 15%;
            text-align: center;
        }
        th:nth-child(6), td:nth-child(6) {
            width: 22%;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Nilai Belajar Anak</h1>
        <p>Dicetak pada: {{ date('d F Y H:i:s') }}</p>
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
                                    <td>{{ $result->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</td>
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

