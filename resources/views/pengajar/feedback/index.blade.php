@extends('layouts.app')

@section('title', 'Feedback')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-3xl font-bold text-white">Feedback</h1>
    <a href="{{ route('pengajar.feedback.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
        Beri Feedback
    </a>
</div>

<div class="rounded-lg shadow border border-gray-200" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-black">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                <tr>
                    <th scope="col" class="px-6 py-3">Siswa</th>
                    <th scope="col" class="px-6 py-3">Quiz</th>
                    <th scope="col" class="px-6 py-3">Hasil Pengerjaan</th>
                    <th scope="col" class="px-6 py-3">Komentar</th>
                    <th scope="col" class="px-6 py-3">Tanggal</th>
                    <th scope="col" class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($feedback as $f)
                    <tr class="bg-white border-b">
                        <td class="px-6 py-4 font-medium text-black">{{ $f->siswa->name }}</td>
                        <td class="px-6 py-4">
                            @if($f->quizResult)
                                {{ $f->quizResult->quiz->judul }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($f->quizResult)
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-black">Nilai: {{ $f->quizResult->nilai }}</span>
                                    <span class="text-xs text-gray-600">{{ $f->quizResult->jawaban_benar }}/{{ $f->quizResult->total_soal }} benar</span>
                                    <span class="text-xs text-gray-500">{{ $f->quizResult->created_at->format('d M Y H:i') }}</span>
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-black">{{ Str::limit($f->komentar, 50) }}</td>
                        <td class="px-6 py-4 text-black">{{ $f->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('pengajar.feedback.edit', $f->id) }}" class="text-blue-600 hover:underline mr-3">Edit</a>
                            @if($f->quizResult)
                                <a href="{{ route('pengajar.results.show', $f->quizResult->id) }}" class="text-green-600 hover:underline mr-3">Lihat Hasil</a>
                            @endif
                            <form action="{{ route('pengajar.feedback.destroy', $f->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline" onclick="confirmDelete(event, 'Yakin ingin menghapus feedback ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Belum ada feedback</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">
        {{ $feedback->links() }}
    </div>
</div>
@endsection

