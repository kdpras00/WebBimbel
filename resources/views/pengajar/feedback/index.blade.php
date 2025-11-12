@extends('layouts.app')

@section('title', 'Feedback')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Feedback</h1>
    <a href="{{ route('pengajar.feedback.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
        Beri Feedback
    </a>
</div>

<div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Siswa</th>
                    <th scope="col" class="px-6 py-3">Quiz</th>
                    <th scope="col" class="px-6 py-3">Komentar</th>
                    <th scope="col" class="px-6 py-3">Tanggal</th>
                    <th scope="col" class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($feedback as $f)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $f->siswa->name }}</td>
                        <td class="px-6 py-4">
                            @if($f->quizResult)
                                {{ $f->quizResult->quiz->judul }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ Str::limit($f->komentar, 50) }}</td>
                        <td class="px-6 py-4">{{ $f->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('pengajar.feedback.edit', $f->id) }}" class="text-blue-600 hover:underline mr-3">Edit</a>
                            <form action="{{ route('pengajar.feedback.destroy', $f->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada feedback</td>
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

