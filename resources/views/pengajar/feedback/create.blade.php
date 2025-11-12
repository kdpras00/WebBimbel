@extends('layouts.app')

@section('title', 'Beri Feedback')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Beri Feedback</h1>
</div>

<div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-6">
    <form action="{{ route('pengajar.feedback.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Siswa</label>
            <select name="siswa_id" id="siswa_id" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">Pilih Siswa</option>
                @foreach($results as $r)
                    <option value="{{ $r->siswa_id }}" 
                            data-result="{{ $r->id }}"
                            {{ $result && $result->siswa_id == $r->siswa_id ? 'selected' : '' }}>
                        {{ $r->siswa->name }} - {{ $r->quiz->judul }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Quiz Result (Opsional)</label>
            <select name="quiz_result_id" id="quiz_result_id"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="">Pilih Quiz Result</option>
                @if($result)
                    <option value="{{ $result->id }}" selected>{{ $result->quiz->judul }} - {{ $result->siswa->name }}</option>
                @endif
            </select>
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Komentar</label>
            <textarea name="komentar" rows="6" required
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                      placeholder="Tulis feedback untuk siswa...">{{ old('komentar') }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
            <a href="{{ route('pengajar.feedback.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>

<script>
document.getElementById('siswa_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const resultId = selectedOption.getAttribute('data-result');
    const quizResultSelect = document.getElementById('quiz_result_id');
    
    if (resultId) {
        quizResultSelect.value = resultId;
    }
});
</script>
@endsection

