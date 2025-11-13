@extends('layouts.app')

@section('title', 'Kelola Quiz')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-3xl font-bold text-white">Kelola Quiz</h1>
    <a href="{{ route('pengajar.quiz.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
        Buat Quiz
    </a>
</div>

<div class="rounded-lg shadow border border-gray-200" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-black">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                <tr>
                    <th scope="col" class="px-6 py-3">Judul</th>
                    <th scope="col" class="px-6 py-3">Mata Pelajaran</th>
                    <th scope="col" class="px-6 py-3">Jumlah Soal</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($quizzes as $quiz)
                    <tr class="bg-white border-b border-gray-200">
                        <td class="px-6 py-4 font-medium text-black">{{ $quiz->judul }}</td>
                        <td class="px-6 py-4 text-black">{{ $quiz->mapel->nama }} ({{ $quiz->mapel->kelas->nama }})</td>
                        <td class="px-6 py-4 text-black">{{ $quiz->questions_count }} soal</td>
                        <td class="px-6 py-4">
                            @if($quiz->is_published)
                                <span class="px-2 py-1 text-xs font-medium rounded bg-green-100 text-green-800">
                                    Published
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium rounded bg-gray-100 text-gray-800">
                                    Draft
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('pengajar.quiz.edit', $quiz->id) }}" class="text-blue-600 hover:underline mr-3">Edit</a>
                            <form action="{{ route('pengajar.quiz.destroy', $quiz->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada quiz</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">
        {{ $quizzes->links() }}
    </div>
</div>
@endsection

