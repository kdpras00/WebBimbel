@extends('layouts.app')

@section('title', 'Beri Feedback')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Beri Feedback</h1>
</div>

<div class="rounded-lg shadow border border-gray-200 p-6" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
    <form action="{{ route('pengajar.feedback.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
            <div>
                <label class="block mb-2 text-sm font-medium text-black">Siswa</label>
                <select name="siswa_id" id="siswa_id" required
                    class="appearance-none bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 transition-all duration-150 !bg-white !text-black !border-gray-300 !dark:bg-white !dark:text-black !dark:border-gray-300">
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

            <div>
                <label class="block mb-2 text-sm font-medium text-black">Quiz Result <span class="text-gray-400 text-xs">(Opsional)</span></label>
                <select name="quiz_result_id" id="quiz_result_id"
                    class="appearance-none bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 transition-all duration-150 !bg-white !text-black !border-gray-300 !dark:bg-white !dark:text-black !dark:border-gray-300">
                    <option value="">Pilih Quiz Result</option>
                    @foreach($results as $r)
                        <option value="{{ $r->id }}" 
                                data-siswa="{{ $r->siswa_id }}"
                                {{ $result && $result->id == $r->id ? 'selected' : '' }}>
                            {{ $r->quiz->judul }} - {{ $r->siswa->name }} (Nilai: {{ $r->nilai }}) - {{ $r->created_at->format('d M Y') }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-black">Komentar</label>
            <textarea name="komentar" rows="6" required
                class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                placeholder="Tulis feedback untuk siswa...">{{ old('komentar') }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
            <a href="{{ route('pengajar.feedback.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>

<script>
// Force the select dropdown's option background to white on open using a simple user-select CSS reset
const selects = document.querySelectorAll('select');
selects.forEach(sel => {
    // Remove dark mode classes if present and force light styles
    sel.classList.remove('dark:bg-gray-800', 'dark:text-white', 'dark:border-gray-700');
    sel.classList.add('!bg-white', '!text-black', '!border-gray-300');
    sel.addEventListener('focus', function() {
        this.classList.add('ring-blue-200');
    });
    sel.addEventListener('blur', function() {
        this.classList.remove('ring-blue-200');
    });
});
// Sync quiz_result_id with siswa select and filter quiz results
document.getElementById('siswa_id').addEventListener('change', function() {
    const selectedSiswaId = this.value;
    const quizResultSelect = document.getElementById('quiz_result_id');
    const allOptions = quizResultSelect.querySelectorAll('option');
    
    // Show all options first
    allOptions.forEach(option => {
        if (option.value !== '') {
            option.style.display = '';
        }
    });
    
    // Filter quiz results based on selected siswa
    if (selectedSiswaId) {
        allOptions.forEach(option => {
            if (option.value !== '') {
                const optionSiswaId = option.getAttribute('data-siswa');
                if (optionSiswaId !== selectedSiswaId) {
                    option.style.display = 'none';
                }
            }
        });
        
        // Auto-select first visible quiz result if available
        const visibleOptions = Array.from(allOptions).filter(opt => 
            opt.value !== '' && opt.style.display !== 'none'
        );
        if (visibleOptions.length > 0) {
            quizResultSelect.value = visibleOptions[0].value;
        } else {
            quizResultSelect.value = '';
        }
    } else {
        quizResultSelect.value = '';
    }
});

// Initialize on load
document.addEventListener('DOMContentLoaded', function() {
    const siswaSelect = document.getElementById('siswa_id');
    if (siswaSelect && siswaSelect.value) {
        siswaSelect.dispatchEvent(new Event('change'));
    }
});
</script>
@endsection

