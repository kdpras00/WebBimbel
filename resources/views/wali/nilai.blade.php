@extends('layouts.app')

@section('title', 'Nilai Anak')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Nilai Anak</h1>
    <p class="mt-2 text-gray-600 dark:text-gray-400">Lihat hasil belajar dan nilai anak Anda</p>
</div>

@if($anak->count() > 0)
    <!-- Filter by Child -->
    <div class="mb-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-4">
        <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Filter berdasarkan anak:</label>
        <select id="filterChild" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full md:w-64 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <option value="all">Semua Anak</option>
            @foreach($anak as $child)
                <option value="{{ $child->id }}">{{ $child->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Results Table -->
    <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Daftar Nilai</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Anak</th>
                        <th scope="col" class="px-6 py-3">Quiz</th>
                        <th scope="col" class="px-6 py-3">Mata Pelajaran</th>
                        <th scope="col" class="px-6 py-3">Nilai</th>
                        <th scope="col" class="px-6 py-3">Jawaban Benar</th>
                        <th scope="col" class="px-6 py-3">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($results as $result)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700" data-child="{{ $result->siswa_id }}">
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $result->siswa->name }}</td>
                            <td class="px-6 py-4">{{ $result->quiz->judul }}</td>
                            <td class="px-6 py-4">{{ $result->quiz->mapel->nama }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium rounded 
                                    @if($result->nilai >= 80) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                    @elseif($result->nilai >= 60) bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                    @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                    @endif">
                                    {{ $result->nilai }}
                                </span>
                            </td>
                            <td class="px-6 py-4">{{ $result->jawaban_benar }}/{{ $result->total_soal }}</td>
                            <td class="px-6 py-4">{{ $result->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada hasil quiz</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $results->links() }}
        </div>
    </div>
@else
    <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-12 text-center">
        <p class="text-gray-500 dark:text-gray-400">Belum ada data anak yang terdaftar</p>
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
</script>
@endsection

