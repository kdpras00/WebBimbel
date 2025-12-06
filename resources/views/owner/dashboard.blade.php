@extends('layouts.app')

@section('title', 'Dashboard Pemilik')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-white">Dashboard Pemilik</h1>
            <p class="mt-2 text-blue-100">Ringkasan statistik Bimbel HIKARI</p>
        </div>
        <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm px-4 py-2 rounded-xl border border-white/20 text-white">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            <span class="font-medium">{{ now()->format('d F Y') }}</span>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Siswa -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300 group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Total Siswa</p>
                    <h3 class="text-3xl font-bold text-slate-800 group-hover:text-blue-600 transition-colors">{{ $stats['total_siswa'] }}</h3>
                </div>
                <div class="p-3 bg-blue-50 rounded-xl group-hover:bg-blue-100 transition-colors">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Pengajar -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300 group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Total Pengajar</p>
                    <h3 class="text-3xl font-bold text-slate-800 group-hover:text-purple-600 transition-colors">{{ $stats['total_pengajar'] }}</h3>
                </div>
                <div class="p-3 bg-purple-50 rounded-xl group-hover:bg-purple-100 transition-colors">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Kelas -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300 group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Total Kelas</p>
                    <h3 class="text-3xl font-bold text-slate-800 group-hover:text-green-600 transition-colors">{{ $stats['total_kelas'] }}</h3>
                </div>
                <div class="p-3 bg-green-50 rounded-xl group-hover:bg-green-100 transition-colors">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Mapel -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300 group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Total Mapel</p>
                    <h3 class="text-3xl font-bold text-slate-800 group-hover:text-orange-600 transition-colors">{{ $stats['total_mapel'] }}</h3>
                </div>
                <div class="p-3 bg-orange-50 rounded-xl group-hover:bg-orange-100 transition-colors">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Rata-rata Global -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300 group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Rata-rata Global</p>
                    <h3 class="text-3xl font-bold text-slate-800 group-hover:text-teal-600 transition-colors">{{ $stats['rata_rata_global'] }}</h3>
                </div>
                <div class="p-3 bg-teal-50 rounded-xl group-hover:bg-teal-100 transition-colors">
                    <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Persentase Kelulusan -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-all duration-300 group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Kelulusan</p>
                    <h3 class="text-3xl font-bold text-slate-800 group-hover:text-rose-600 transition-colors">{{ $stats['persentase_kelulusan'] }}%</h3>
                </div>
                <div class="p-3 bg-rose-50 rounded-xl group-hover:bg-rose-100 transition-colors">
                    <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Student Growth Chart -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 overflow-hidden">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Pertumbuhan Siswa Baru</h3>
            <div id="studentGrowthChart" class="-ml-2"></div>
        </div>

        <!-- Quiz Activity Chart -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Aktivitas Quiz (30 Hari Terakhir)</h3>
            <div class="overflow-x-auto pb-2" id="quizChartContainer">
                <div id="quizActivityChart" class="-ml-2" style="min-width: 800px;"></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Teacher Performance Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-800">Performa Pengajar</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-600">
                    <thead class="text-xs text-slate-700 uppercase bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th scope="col" class="px-6 py-3">Nama</th>
                            <th scope="col" class="px-6 py-3 text-center">Quiz</th>
                            <th scope="col" class="px-6 py-3 text-center">Rata-rata Siswa</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($teacher_stats as $teacher)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-slate-900">{{ $teacher->name }}</td>
                                <td class="px-6 py-4 text-center">{{ $teacher->total_quiz }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-1 rounded-full text-xs font-bold 
                                        {{ $teacher->avg_score >= 80 ? 'bg-green-100 text-green-700' : 
                                           ($teacher->avg_score >= 70 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                        {{ number_format($teacher->avg_score, 1) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-8 text-center text-slate-500">Belum ada data pengajar</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Students Table -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-800">Siswa Berprestasi</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-600">
                    <thead class="text-xs text-slate-700 uppercase bg-slate-50 border-b border-slate-200">
                        <tr>
                            <th scope="col" class="px-6 py-3">Nama</th>
                            <th scope="col" class="px-6 py-3 text-center">Rata-rata Nilai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($top_students as $student)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 font-medium text-slate-900">{{ $student->siswa->name }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                                        {{ number_format($student->avg_nilai, 1) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="px-6 py-8 text-center text-slate-500">Belum ada data siswa</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Student Growth Chart
        const growthOptions = {
            series: [{
                name: 'Siswa Baru',
                data: @json($growth_data)
            }],
            chart: {
                type: 'area',
                height: 350,
                toolbar: { show: false },
                fontFamily: 'Inter, sans-serif',
                parentHeightOffset: 0
            },
            grid: {
                padding: {
                    top: 0,
                    right: 0,
                    bottom: 0,
                    left: 10
                }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2 },
            xaxis: {
                categories: @json($months),
                axisBorder: { show: false },
                axisTicks: { show: false }
            },
            yaxis: {
                show: true,
                tickAmount: 5
            },
            theme: {
                monochrome: {
                    enabled: true,
                    color: '#2563EB', // Blue-600
                    shadeTo: 'light',
                    shadeIntensity: 0.65
                }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.9,
                    stops: [0, 90, 100]
                }
            },
        };

        const growthChart = new ApexCharts(document.querySelector("#studentGrowthChart"), growthOptions);
        growthChart.render();

        const activityOptions = {
            series: [{
                name: 'Pengerjaan Quiz',
                data: @json($activity_data)
            }],
            chart: {
                type: 'area',
                height: 350,
                toolbar: { show: false }, // Disable toolbar, using native scroll
                fontFamily: 'Inter, sans-serif',
                parentHeightOffset: 0,
                zoom: { enabled: false }
            },
            grid: {
                padding: {
                    top: 0,
                    right: 20, // Add padding right for scroll end visibility
                    bottom: 0,
                    left: 10
                }
            },
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 2 },
            xaxis: {
                categories: @json($days),
                axisBorder: { show: false },
                axisTicks: { show: false },
                tooltip: { enabled: false }
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.9,
                    stops: [0, 90, 100]
                },
                colors: ['#4F46E5']
            },
            theme: {
                monochrome: {
                    enabled: true,
                    color: '#4F46E5',
                    shadeTo: 'light',
                    shadeIntensity: 0.65
                }
            }
        };

        const activityChart = new ApexCharts(document.querySelector("#quizActivityChart"), activityOptions);
        activityChart.render();

        // Scroll to the end of the chart container to show latest data
        // Using setTimeout to ensure chart is fully rendered and container has width
        setTimeout(() => {
            const chartContainer = document.getElementById('quizChartContainer');
            if (chartContainer) {
                chartContainer.scrollLeft = chartContainer.scrollWidth;
            }
        }, 500);
    });
</script>
@endpush
