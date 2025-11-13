@extends('layouts.app')

@section('title', 'Nilai Anak')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Nilai Anak</h1>
    <p class="mt-2 text-gray-100">Lihat hasil belajar dan nilai anak Anda</p>
</div>

@if($anak->count() > 0)
    <!-- Filter by Child -->
    <div class="mb-6 rounded-lg shadow border border-gray-200 p-4" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
        <label class="block mb-2 text-sm font-medium text-black">Filter berdasarkan anak:</label>
        <select id="filterChild" class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full md:w-64 p-2.5">
            <option value="all">Semua Anak</option>
            @foreach($anak as $child)
                <option value="{{ $child->id }}">{{ $child->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Results Table -->
    <div class="rounded-lg shadow border border-gray-200" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-black">Daftar Nilai</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-black">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
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
                        <tr class="bg-white border-b border-gray-200" data-child="{{ $result->siswa_id }}">
                            <td class="px-6 py-4 font-medium text-black">{{ $result->siswa->name }}</td>
                            <td class="px-6 py-4 text-black">{{ $result->quiz->judul }}</td>
                            <td class="px-6 py-4 text-black">{{ $result->quiz->mapel->nama }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium rounded 
                                    @if($result->nilai >= 80) bg-green-100 text-green-800
                                    @elseif($result->nilai >= 60) bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $result->nilai }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-black">{{ $result->jawaban_benar }}/{{ $result->total_soal }}</td>
                            <td class="px-6 py-4 text-black">{{ $result->created_at->format('d M Y H:i') }}</td>
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
    <div class="rounded-lg shadow border border-gray-200 p-12 text-center style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
        <p class="text-gray-600">Belum ada data anak yang terdaftar</p>
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

