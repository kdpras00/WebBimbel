@extends('layouts.app')

@section('title', 'Feedback dari Pengajar')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Feedback dari Pengajar</h1>
    <p class="mt-2 text-gray-100">Lihat feedback yang diberikan pengajar untuk anak Anda</p>
</div>

@if($feedback->count() > 0)
    <div class="rounded-lg shadow border border-gray-200" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-black">Daftar Feedback</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-black">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3">Anak</th>
                        <th scope="col" class="px-6 py-3">Pengajar</th>
                        <th scope="col" class="px-6 py-3">Quiz</th>
                        <th scope="col" class="px-6 py-3">Komentar</th>
                        <th scope="col" class="px-6 py-3">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($feedback as $f)
                        <tr class="bg-white border-b border-gray-200">
                            <td class="px-6 py-4 font-medium text-black">{{ $f->siswa->name }}</td>
                            <td class="px-6 py-4 text-black">{{ $f->pengajar->name }}</td>
                            <td class="px-6 py-4 text-black">
                                @if($f->quizResult && $f->quizResult->quiz)
                                    <div class="flex flex-col">
                                        <span class="font-medium">{{ $f->quizResult->quiz->judul }}</span>
                                        @if($f->quizResult->quiz->mapel)
                                            <span class="text-xs text-gray-600">{{ $f->quizResult->quiz->mapel->nama }}</span>
                                        @endif
                                        <span class="text-xs text-gray-500">Nilai: {{ $f->quizResult->nilai }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-400">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-black">{{ $f->komentar }}</td>
                            <td class="px-6 py-4 text-black">{{ $f->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-4">
            {{ $feedback->links() }}
        </div>
    </div>
@else
    <div class="rounded-lg shadow border border-gray-200 p-12 text-center" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
        </svg>
        <p class="mt-4 text-gray-600">Belum ada feedback dari pengajar</p>
    </div>
@endif
@endsection

