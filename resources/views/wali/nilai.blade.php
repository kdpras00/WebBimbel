@extends('layouts.app')

@section('title', 'Nilai Anak')

@section('content')
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold text-white mb-2">Nilai Anak</h1>
            <p class="text-gray-100 text-lg">Lihat hasil belajar dan nilai anak Anda</p>
        </div>
        <div>
            <a href="{{ route('wali.nilai.download-pdf') }}" 
               target="_blank"
               class="inline-flex items-center px-6 py-3 bg-red-600 text-white font-semibold rounded-lg shadow-lg hover:bg-red-700 hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="text-white font-semibold">Download PDF</span>
            </a>
        </div>
    </div>
</div>

@if($anak->count() > 0)

<div class="mb-6 rounded-xl shadow-lg bg-white p-5 border border-gray-100">
    <label class="block mb-3 text-sm font-semibold text-white flex items-center">
        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
        </svg>
        Filter berdasarkan anak:
    </label>
    <select id="filterChild" class="bg-white border-2 border-gray-200 text-gray-800 text-sm rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 block w-full md:w-64 p-3 transition-all duration-200 hover:border-blue-300">
        <option value="all">Semua Anak</option>
        @foreach($anak as $child)
        <option value="{{ $child->id }}">{{ $child->name }}</option>
        @endforeach
    </select>
</div>

<!-- Progress Cards -->
<div class="space-y-6 mb-6">
    @foreach($anak as $index => $child)
    @php
    $progress = $progressData[$child->id] ?? ['avg_score' => 0, 'total_quiz' => 0, 'best_score' => 0];
    @endphp
    <div class="group relative overflow-hidden rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 bg-white border border-gray-200">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-black">{{ $child->name }}</h3>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-6">
                <div class="text-center bg-gray-50 rounded-xl p-5 border border-gray-200">
                    <p class="text-sm font-medium text-gray-600 mb-2">Rata-rata</p>
                    <p class="text-3xl font-bold text-black">{{ number_format($progress['avg_score'], 1) }}</p>
                </div>
                <div class="text-center bg-gray-50 rounded-xl p-5 border border-gray-200">
                    <p class="text-sm font-medium text-gray-600 mb-2">Total Quiz</p>
                    <p class="text-3xl font-bold text-black">{{ $progress['total_quiz'] }}</p>
                </div>
                <div class="text-center bg-gray-50 rounded-xl p-5 border border-gray-200">
                    <p class="text-sm font-medium text-gray-600 mb-2">Tertinggi</p>
                    <p class="text-3xl font-bold text-black">{{ $progress['best_score'] }}</p>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Filter by Child -->

<!-- Results Table -->
<div class="rounded-xl shadow-lg bg-white border border-gray-100 overflow-hidden">
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 p-6">
        <h2 class="text-2xl font-bold text-white flex items-center">
            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Daftar Nilai
        </h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                <tr>
                    <th scope="col" class="px-6 py-4 font-semibold">Anak</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Quiz</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Mata Pelajaran</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Nilai</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Jawaban Benar</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Tanggal</th>
                    <th scope="col" class="px-6 py-4 font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($results as $result)
                <tr class="hover:bg-blue-50 transition-colors duration-150" data-child="{{ $result->siswa_id }}" data-result-id="{{ $result->id }}">
                    <td class="px-6 py-4 font-medium text-gray-900">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                <span class="text-blue-600 text-xs font-bold">{{ substr($result->siswa->name, 0, 1) }}</span>
                            </div>
                            {{ $result->siswa->name }}
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-700">{{ $result->quiz->judul }}</td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 text-xs font-medium rounded-full bg-indigo-100 text-indigo-800">
                            {{ $result->quiz->mapel->nama }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1.5 text-sm font-bold rounded-lg 
                                    @if($result->nilai >= 80) bg-green-100 text-green-800 border border-green-200
                                    @elseif($result->nilai >= 60) bg-yellow-100 text-yellow-800 border border-yellow-200
                                    @else bg-red-100 text-red-800 border border-red-200
                                    @endif">
                            {{ $result->nilai }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-700">
                        <span class="font-medium">{{ $result->jawaban_benar }}</span>
                        <span class="text-gray-400">/</span>
                        <span class="text-gray-600">{{ $result->total_soal }}</span>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $result->created_at->format('d M Y H:i') }}</td>
                    <td class="px-6 py-4">
                        @php
                        $quiz = $result->quiz;
                        $pengajar = $quiz->pengajar ?? null;
                        $allFeedback = $result->feedback->sortByDesc('created_at');
                        @endphp
                        @if($pengajar)
                        @php
                        $feedbackJson = $allFeedback->map(function($f) {
                        return [
                        'pengajar' => $f->pengajar->name ?? 'Pengajar',
                        'komentar' => $f->komentar,
                        'tanggal' => $f->created_at->format('d M Y H:i')
                        ];
                        })->values();
                        @endphp
                        <button
                            onclick="showPengajarInfo({{ $pengajar->id }}, {{ json_encode($pengajar->name) }}, {{ json_encode($pengajar->email) }}, {{ $result->id }}, {{ json_encode($feedbackJson) }})"
                            class="px-4 py-2 text-xs font-semibold bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 flex items-center space-x-1"
                            style="background: linear-gradient(to right, #2563eb, #1d4ed8);"
                            onmouseover="this.style.background='linear-gradient(to right, #2563eb, #1d4ed8)'"
                            onmouseout="this.style.background='linear-gradient(to right, #2563eb, #1d4ed8)'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Info Pengajar</span>
                        </button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-gray-500 text-lg font-medium">Belum ada hasil quiz</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
        {{ $results->links() }}
    </div>
</div>
@else
<div class="rounded-xl shadow-lg bg-white border border-gray-100 p-12 text-center">
    <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
    </svg>
    <p class="text-gray-600 text-lg font-medium">Belum ada data anak yang terdaftar</p>
</div>
@endif

<script>
    document.getElementById('filterChild').addEventListener('change', function() {
        const selectedChild = this.value;
        const rows = document.querySelectorAll('tbody tr[data-child]');

        rows.forEach(row => {
            if (selectedChild === 'all' || row.getAttribute('data-child') === selectedChild) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    function showPengajarInfo(id, name, email, resultId, feedbackData) {
        // Pastikan feedbackData adalah array
        if (!Array.isArray(feedbackData)) {
            feedbackData = [];
        }

        let htmlContent = '<div class="text-left">';
        htmlContent += '<div class="mb-4 pb-4 border-b border-gray-200">';
        htmlContent += '<p class="mb-2"><strong>Nama:</strong> ' + escapeHtml(name) + '</p>';
        htmlContent += '<p class="mb-2"><strong>Email:</strong> ' + escapeHtml(email) + '</p>';
        htmlContent += '</div>';

        // Tampilkan feedback jika ada
        if (feedbackData && feedbackData.length > 0) {
            htmlContent += '<div class="mt-4">';
            htmlContent += '<h3 class="text-lg font-semibold text-gray-800 mb-3">Feedback dari Pengajar</h3>';
            htmlContent += '<div class="space-y-4 max-h-96 overflow-y-auto">';

            feedbackData.forEach(function(feedback, index) {
                htmlContent += '<div class="bg-blue-50 border border-blue-200 rounded-lg p-4' + (index < feedbackData.length - 1 ? ' mb-3' : '') + '">';
                htmlContent += '<div class="flex items-start justify-between mb-2">';
                htmlContent += '<p class="text-sm font-semibold text-gray-700">' + escapeHtml(feedback.pengajar || 'Pengajar') + '</p>';
                htmlContent += '<p class="text-xs text-gray-500">' + escapeHtml(feedback.tanggal || '') + '</p>';
                htmlContent += '</div>';
                htmlContent += '<p class="text-sm text-gray-800 whitespace-pre-wrap">' + escapeHtml(feedback.komentar || '') + '</p>';
                htmlContent += '</div>';
            });

            htmlContent += '</div>';
            htmlContent += '</div>';
        } else {
            htmlContent += '<div class="mt-4">';
            htmlContent += '<p class="text-sm text-gray-600 italic">Belum ada feedback dari pengajar untuk hasil quiz ini.</p>';
            htmlContent += '</div>';
        }

        htmlContent += '<p class="mt-4 text-sm text-gray-600">Informasi ini diberikan untuk komunikasi dengan pengajar terkait hasil belajar anak Anda.</p>';
        htmlContent += '</div>';

        Swal.fire({
            title: 'Informasi Pengajar',
            html: htmlContent,
            icon: 'info',
            width: '700px',
            confirmButtonColor: '#2563eb',
            confirmButtonText: 'Tutup'
        });
    }

    // Helper function untuk escape HTML
    function escapeHtml(text) {
        if (!text) return '';
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return String(text).replace(/[&<>"']/g, function(m) {
            return map[m];
        });
    }
</script>
@endsection