@extends('layouts.app')

@section('title', 'Nilai Anak')

@section('content')
<div class="space-y-8" x-data="{ activeIndex: 0, dropdownOpen: false }">
    <!-- Page Header & Child Selector -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
        <div>
            <h1 class="text-3xl font-bold text-white">Nilai Anak</h1>
            <p class="mt-2 text-blue-100">Lihat hasil belajar dan nilai detail anak Anda</p>
        </div>
        
        <div class="flex items-center gap-4">
             @if(count($gradesData) > 0)
                <!-- Custom Dropdown Selector -->
                <div class="relative min-w-[250px] z-20">
                    <button @click="dropdownOpen = !dropdownOpen" 
                            @click.away="dropdownOpen = false"
                            class="w-full bg-white text-slate-700 font-semibold py-3 px-5 rounded-xl shadow-lg flex items-center justify-between hover:bg-slate-50 transition-all border border-slate-100">
                        <div class="flex items-center gap-3">
                            <span class="text-sm text-slate-400 font-normal">Pilih Anak:</span>
                            <span x-text="'{{ $gradesData[0]['anak']->name }}'"></span>
                        </div>
                        <svg class="w-5 h-5 text-slate-400 transition-transform duration-300" 
                             :class="dropdownOpen ? 'rotate-180' : ''" 
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
    
                    <!-- Dropdown List -->
                    <div x-show="dropdownOpen" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                         class="absolute right-0 mt-2 w-full bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden"
                         style="display: none;">
                        @foreach($gradesData as $index => $data)
                            <button @click="activeIndex = {{ $index }}; dropdownOpen = false" 
                                    class="w-full text-left px-5 py-3 hover:bg-blue-50 transition-colors flex items-center gap-3 group border-b border-slate-50 last:border-0">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold transition-colors"
                                     :class="activeIndex === {{ $index }} ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-500 group-hover:bg-blue-200 group-hover:text-blue-700'">
                                    {{ substr($data['anak']->name, 0, 1) }}
                                </div>
                                <span class="font-medium text-slate-700 group-hover:text-blue-700" :class="activeIndex === {{ $index }} ? 'text-blue-600' : ''">
                                    {{ $data['anak']->name }}
                                </span>
                                <svg x-show="activeIndex === {{ $index }}" class="w-5 h-5 text-blue-600 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Download PDF Button -->
            <a href="{{ route('wali.nilai.download-pdf') }}" target="_blank" class="flex items-center gap-2 bg-red-600 text-white px-5 py-3 rounded-xl hover:bg-red-700 transition-colors shadow-lg hover:shadow-red-500/30 font-semibold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                </svg>
                Download PDF
            </a>
        </div>
    </div>

    <!-- Content Area -->
    <div class="min-h-[400px]">
        @forelse($gradesData as $index => $data)
            <div x-show="activeIndex === {{ $index }}" 
                 x-effect="if(activeIndex === {{ $index }}) { $el.closest('[x-data]').querySelector('span[x-text]').innerText = '{{ $data['anak']->name }}' }"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 style="display: none;">
                 
                <!-- Child Overview Card -->
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 mb-8 flex items-center justify-between relative overflow-hidden group">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-blue-500"></div>
                    <div class="flex items-center gap-5 pl-4">
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center text-2xl font-bold shadow-lg shadow-blue-200">
                            {{ substr($data['anak']->name, 0, 1) }}
                        </div>
                        <div>
                             <h2 class="text-2xl font-bold text-slate-800">{{ $data['anak']->name }}</h2>
                             <p class="text-slate-500 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                {{ $data['anak']->email }}
                             </p>
                        </div>
                    </div>
                </div>

                @if($data['results']->count() > 0)
                    <!-- Statistics Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        @php
                            $avgScore = $data['stats']['avg_score'];
                            $totalQuiz = $data['stats']['total_quiz'];
                            $bestScore = $data['stats']['best_score'];
                        @endphp
                        
                        <!-- Rata-rata -->
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300 group hover:-translate-y-1">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-medium text-slate-500 mb-1">Rata-rata Nilai</p>
                                    <h3 class="text-3xl font-bold {{ $avgScore >= 80 ? 'text-green-600' : ($avgScore >= 70 ? 'text-yellow-600' : 'text-red-600') }}">
                                        {{ number_format($avgScore, 1) }}
                                    </h3>
                                </div>
                                <div class="p-3 bg-blue-50 rounded-xl text-blue-600 group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Total Quiz -->
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300 group hover:-translate-y-1">
                             <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-medium text-slate-500 mb-1">Total Quiz</p>
                                    <h3 class="text-3xl font-bold text-slate-800">{{ $totalQuiz }}</h3>
                                </div>
                                 <div class="p-3 bg-purple-50 rounded-xl text-purple-600 group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Nilai Tertinggi -->
                        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300 group hover:-translate-y-1">
                             <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-medium text-slate-500 mb-1">Nilai Tertinggi</p>
                                    <h3 class="text-3xl font-bold text-green-600">{{ $bestScore }}</h3>
                                </div>
                                <div class="p-3 bg-green-50 rounded-xl text-green-600 group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Grades Table -->
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-12">
                        <div class="p-6 border-b border-slate-100">
                            <h3 class="text-lg font-bold text-slate-800">Daftar Nilai Lengkap</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full min-w-[1000px] text-sm text-left text-slate-600">
                                 <thead class="text-xs text-slate-700 uppercase bg-slate-50 border-b border-slate-200">
                                    <tr>
                                        <th scope="col" class="px-6 py-4 font-semibold">Quiz</th>
                                        <th scope="col" class="px-6 py-4 font-semibold">Mapel</th>
                                        <th scope="col" class="px-6 py-4 font-semibold text-center">Nilai</th>
                                        <th scope="col" class="px-6 py-4 font-semibold">Jawaban Benar</th>
                                        <th scope="col" class="px-6 py-4 font-semibold">Tanggal</th>
                                        <th scope="col" class="px-6 py-4 font-semibold text-center">Aksi</th>
                                    </tr>
                                 </thead>
                                 <tbody class="divide-y divide-slate-200">
                                    @foreach($data['results'] as $result)
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="px-6 py-4 font-medium text-slate-900">{{ $result->quiz->judul }}</td>
                                            <td class="px-6 py-4">
                                                <span class="px-2.5 py-1 text-xs font-semibold rounded-lg bg-indigo-50 text-indigo-600 border border-indigo-100">
                                                    {{ $result->quiz->mapel->nama }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <span class="text-lg font-bold {{ $result->nilai >= ($result->quiz->mapel->kkm ?? 70) ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ $result->nilai }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="font-medium text-slate-700">{{ $result->jawaban_benar }}</span>
                                                <span class="text-slate-400">/</span>
                                                <span class="text-slate-500">{{ $result->total_soal }}</span>
                                            </td>
                                            <td class="px-6 py-4 text-slate-500">{{ $result->created_at->format('d M Y, H:i') }}</td>
                                            <td class="px-6 py-4 text-center">
                                                @php
                                                    $quiz = $result->quiz;
                                                    $pengajar = $quiz->pengajar ?? null;
                                                    $allFeedback = $result->feedback->sortByDesc('created_at');
                                                    $feedbackJson = $pengajar ? $allFeedback->map(function($f) {
                                                        return [
                                                            'pengajar' => $f->pengajar->name ?? 'Pengajar',
                                                            'komentar' => $f->komentar,
                                                            'tanggal' => $f->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') . ' WIB'
                                                        ];
                                                    })->values() : [];
                                                @endphp

                                                @if($pengajar)
                                                    <button onclick="showPengajarInfo({{ $pengajar->id }}, '{{ $pengajar->name }}', '{{ $pengajar->email }}', {{ $result->id }}, {{ json_encode($feedbackJson) }})"
                                                            class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors border border-blue-100">
                                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        Info Pengajar
                                                    </button>
                                                @else
                                                    <span class="text-xs text-slate-400 italic">Tidak ada info</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                 </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <!-- Empty State for Specific Child -->
                    <div class="bg-white rounded-2xl p-12 text-center border border-slate-200 mb-12">
                         <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-100 mb-6 transition-transform">
                            <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">Belum Ada Hasil Quiz</h3>
                        <p class="text-slate-500 max-w-sm mx-auto">
                            {{ $data['anak']->name }} belum memiliki nilai dari quiz apapun.
                        </p>
                    </div>
                @endif
            </div>
        @empty
            <!-- Global Empty State (No Children Linked) -->
            <div class="bg-white rounded-2xl p-12 text-center border border-slate-200">
                <svg class="w-16 h-16 text-slate-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <h3 class="text-lg font-bold text-slate-800">Belum Ada Anak Terdaftar</h3>
                <p class="text-slate-500 mt-2">Silakan hubungi administrator untuk menghubungkan akun anak Anda.</p>
            </div>
        @endforelse
    </div>
</div>

<script>
    function showPengajarInfo(id, name, email, resultId, feedbackData) {
        if (!Array.isArray(feedbackData)) {
            feedbackData = [];
        }

        let htmlContent = '<div class="text-left">';
        htmlContent += '<div class="mb-4 pb-4 border-b border-gray-100">';
        htmlContent += '<p class="mb-2 text-slate-700"><strong>Nama:</strong> ' + escapeHtml(name) + '</p>';

        htmlContent += '</div>';

        if (feedbackData.length > 0) {
            htmlContent += '<div class="mt-4">';
            htmlContent += '<h3 class="text-md font-bold text-slate-800 mb-3 flex items-center gap-2"><svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg> Feedback Pengajar</h3>';
            htmlContent += '<div class="space-y-3 max-h-60 overflow-y-auto pr-2 custom-scrollbar">';

            feedbackData.forEach(function(feedback) {
                htmlContent += '<div class="bg-blue-50/50 border border-blue-100 rounded-xl p-4">';
                htmlContent += '<div class="flex items-start justify-between mb-2">';
                htmlContent += '<span class="text-xs font-bold text-blue-700 px-2 py-0.5 bg-blue-100 rounded-md">' + escapeHtml(feedback.pengajar) + '</span>';
                htmlContent += '<span class="text-xs text-slate-400">' + escapeHtml(feedback.tanggal) + '</span>';
                htmlContent += '</div>';
                htmlContent += '<p class="text-sm text-slate-700 whitespace-pre-wrap leading-relaxed">' + escapeHtml(feedback.komentar) + '</p>';
                htmlContent += '</div>';
            });

            htmlContent += '</div></div>';
        } else {
            htmlContent += '<div class="mt-4 p-4 bg-slate-50 rounded-xl text-center border border-slate-100">';
            htmlContent += '<p class="text-sm text-slate-500 italic">Belum ada feedback dari pengajar.</p>';
            htmlContent += '</div>';
        }

        htmlContent += '<p class="mt-6 text-xs text-slate-400 text-center">Informasi ini bersifat rahasia antara Wali Murid dan Pengajar.</p>';
        htmlContent += '</div>';

        Swal.fire({
            title: 'Info Pengajar',
            html: htmlContent,
            showCloseButton: true,
            showConfirmButton: false,
            width: '600px',
            customClass: {
                popup: 'rounded-2xl',
                title: 'text-xl font-bold text-slate-800'
            }
        });
    }

    function escapeHtml(text) {
        if (!text) return '';
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
    }
</script>
@endsection