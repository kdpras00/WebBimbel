@extends('layouts.app')

@section('title', 'Edit Feedback')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Feedback</h1>
</div>

<div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-6">
    <form action="{{ route('pengajar.feedback.update', $feedback->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Siswa</label>
            <select name="siswa_id" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @foreach($results as $r)
                    <option value="{{ $r->siswa_id }}" 
                            {{ $feedback->siswa_id == $r->siswa_id ? 'selected' : '' }}>
                        {{ $r->siswa->name }} - {{ $r->quiz->judul }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Quiz Result (Opsional)</label>
            <select name="quiz_result_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">Pilih Quiz Result</option>
                @foreach($results as $r)
                    <option value="{{ $r->id }}" {{ $feedback->quiz_result_id == $r->id ? 'selected' : '' }}>
                        {{ $r->quiz->judul }} - {{ $r->siswa->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Komentar</label>
            <textarea name="komentar" rows="6" required
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">{{ old('komentar', $feedback->komentar) }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update</button>
            <a href="{{ route('pengajar.feedback.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>
@endsection

