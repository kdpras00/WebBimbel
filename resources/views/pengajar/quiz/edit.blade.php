@extends('layouts.app')

@section('title', 'Edit Quiz')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-black">Edit Quiz: {{ $quiz->judul }}</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Quiz Info -->
    <div class="lg:col-span-2">
        <div class="rounded-lg shadow border border-gray-200 p-6 mb-6 style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
            <h2 class="text-xl font-semibold mb-4 text-black">Informasi Quiz</h2>
            <form action="{{ route('pengajar.quiz.update', $quiz->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-black">Judul</label>
                    <input type="text" name="judul" value="{{ old('judul', $quiz->judul) }}" required
                           class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-black">Deskripsi</label>
                    <textarea name="deskripsi" rows="3"
                              class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ old('deskripsi', $quiz->deskripsi) }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-black">Durasi (menit)</label>
                    <input type="number" name="durasi" value="{{ old('durasi', $quiz->durasi) }}" min="1"
                           class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>

                <div class="mb-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="is_published" id="is_published" value="1" {{ $quiz->is_published ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                        <label for="is_published" class="ml-2 text-sm font-medium text-black">
                            Publish Quiz
                        </label>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update</button>
                </div>
            </form>
        </div>

        <!-- Questions List -->
        <div class="rounded-lg shadow border border-gray-200 p-6" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
            <h2 class="text-xl font-semibold mb-4 text-black">Daftar Soal</h2>
            @forelse($quiz->questions as $index => $question)
                <div class="mb-4 p-4 border border-gray-200 rounded-lg">
                    <div class="flex justify-between items-start mb-2">
                        <div class="flex-1">
                            <h3 class="font-medium text-black">
                                Soal {{ $index + 1 }}. {{ $question->pertanyaan }}
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">
                                Tipe: {{ $question->tipe == 'pilihan_ganda' ? 'Pilihan Ganda' : 'Essay' }} | 
                                Skor: {{ $question->skor }} | 
                                Jawaban: {{ $question->jawaban_benar }}
                            </p>
                        </div>
                        <form action="{{ route('pengajar.quiz.questions.destroy', [$quiz->id, $question->id]) }}" method="POST" class="ml-4">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:underline text-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">Belum ada soal</p>
            @endforelse
        </div>
    </div>

    <!-- Add Question Form -->
    <div class="lg:col-span-1">
        <div class="rounded-lg shadow border border-gray-200 p-6 sticky top-6" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
            <h2 class="text-xl font-semibold mb-4 text-black">Tambah Soal</h2>
            @if($errors->has('pilihan'))
                <div class="mb-4 p-3 bg-red-50/20 border border-red-200 rounded-lg">
                    <p class="text-sm text-red-800">{{ $errors->first('pilihan') }}</p>
                </div>
            @endif
            <form action="{{ route('pengajar.quiz.questions.store', $quiz->id) }}" method="POST" id="questionForm">
                @csrf
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-black">Pertanyaan</label>
                    <textarea name="pertanyaan" rows="3" required
                              class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"></textarea>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-black">Tipe</label>
                    <select name="tipe" id="questionType" required
                            class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="pilihan_ganda">Pilihan Ganda</option>
                        <option value="essay">Essay</option>
                    </select>
                </div>

                <div class="mb-4" id="pilihanContainer">
                    <label class="block mb-2 text-sm font-medium text-black">Pilihan Jawaban</label>
                    <div class="space-y-2">
                        <input type="text" name="pilihan[A]" id="pilihanA" placeholder="A. ..." required
                               class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pilihan-input">
                        <input type="text" name="pilihan[B]" id="pilihanB" placeholder="B. ..." required
                               class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pilihan-input">
                        <input type="text" name="pilihan[C]" id="pilihanC" placeholder="C. ..." required
                               class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pilihan-input">
                        <input type="text" name="pilihan[D]" id="pilihanD" placeholder="D. ..." required
                               class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 pilihan-input">
                    </div>
                    <p class="mt-2 text-xs text-red-600 hidden" id="errorMessage">Semua pilihan jawaban harus berbeda!</p>
                </div>

                <div class="mb-4" id="jawabanBenarContainer">
                    <label class="block mb-2 text-sm font-medium text-black">Jawaban Benar</label>
                    <select name="jawaban_benar" id="jawabanBenar" required
                            class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-black">Skor</label>
                    <input type="number" name="skor" value="10" min="1" required
                           class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-black">Urutan</label>
                    <input type="number" name="urutan" value="{{ $quiz->questions->count() + 1 }}" min="1"
                           class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                </div>

                <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Tambah Soal
                </button>
            </form>
        </div>
    </div>
</div>

<div class="mt-6">
    <a href="{{ route('pengajar.quiz.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
        Kembali ke Daftar Quiz
    </a>
</div>

<script>
// Function to toggle between select and input for jawaban benar
function toggleJawabanBenar(tipe) {
    const jawabanBenarContainer = document.getElementById('jawabanBenarContainer');
    const currentElement = document.getElementById('jawabanBenar');
    
    if (tipe === 'pilihan_ganda') {
        // Jika sudah select, pastikan opsi lengkap
        if (currentElement && currentElement.tagName === 'SELECT') {
            if (currentElement.options.length < 4) {
                currentElement.innerHTML = '<option value="A">A</option><option value="B">B</option><option value="C">C</option><option value="D">D</option>';
            }
        } else {
            // Jika input, ganti dengan select
            const select = document.createElement('select');
            select.id = 'jawabanBenar';
            select.name = 'jawaban_benar';
            select.required = true;
            select.className = 'bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5';
            select.innerHTML = '<option value="A">A</option><option value="B">B</option><option value="C">C</option><option value="D">D</option>';
            
            if (currentElement) {
                currentElement.replaceWith(select);
            } else {
                jawabanBenarContainer.querySelector('label').after(select);
            }
        }
    } else {
        // Jika sudah input, tidak perlu ubah
        if (currentElement && currentElement.tagName === 'INPUT') {
            return; // Already input, no need to change
        } else {
            // Jika select, ganti dengan input
            const input = document.createElement('input');
            input.type = 'text';
            input.id = 'jawabanBenar';
            input.name = 'jawaban_benar';
            input.required = true;
            input.placeholder = 'Masukkan jawaban benar';
            input.className = 'bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5';
            
            if (currentElement) {
                currentElement.replaceWith(input);
            } else {
                jawabanBenarContainer.querySelector('label').after(input);
            }
        }
    }
}

document.getElementById('questionType').addEventListener('change', function() {
    const tipe = this.value;
    const pilihanContainer = document.getElementById('pilihanContainer');
    
    if (tipe === 'pilihan_ganda') {
        pilihanContainer.style.display = 'block';
        toggleJawabanBenar('pilihan_ganda');
        // Re-setup validation listeners after DOM update
        setTimeout(setupValidationListeners, 50);
    } else {
        pilihanContainer.style.display = 'none';
        toggleJawabanBenar('essay');
    }
});

// Validasi pilihan jawaban tidak boleh sama
function validatePilihan() {
    const pilihanA = document.getElementById('pilihanA').value.trim();
    const pilihanB = document.getElementById('pilihanB').value.trim();
    const pilihanC = document.getElementById('pilihanC').value.trim();
    const pilihanD = document.getElementById('pilihanD').value.trim();
    const errorMessage = document.getElementById('errorMessage');
    const inputs = document.querySelectorAll('.pilihan-input');
    
    // Reset border color
    inputs.forEach(input => {
        input.classList.remove('border-red-500');
    });
    
    // Check for duplicates
    const values = [pilihanA, pilihanB, pilihanC, pilihanD];
    const duplicates = [];
    
    for (let i = 0; i < values.length; i++) {
        if (values[i] === '') continue;
        for (let j = i + 1; j < values.length; j++) {
            if (values[i] === values[j] && values[i] !== '') {
                duplicates.push(i, j);
            }
        }
    }
    
    if (duplicates.length > 0) {
        errorMessage.classList.remove('hidden');
        const labels = ['A', 'B', 'C', 'D'];
        duplicates.forEach(index => {
            document.getElementById(`pilihan${labels[index]}`).classList.add('border-red-500');
        });
        return false;
    } else {
        errorMessage.classList.add('hidden');
        return true;
    }
}

// Function to setup validation listeners
function setupValidationListeners() {
    // Remove old listeners by cloning nodes (this removes all event listeners)
    const oldInputs = document.querySelectorAll('.pilihan-input');
    oldInputs.forEach(input => {
        const newInput = input.cloneNode(true);
        input.parentNode.replaceChild(newInput, input);
    });
    
    // Add new listeners to fresh nodes
    document.querySelectorAll('.pilihan-input').forEach(input => {
        input.addEventListener('input', validatePilihan);
        input.addEventListener('blur', validatePilihan);
    });
}

// Setup initial listeners
setupValidationListeners();

// Validate before form submit
document.getElementById('questionForm').addEventListener('submit', function(e) {
    const questionType = document.getElementById('questionType').value;
    
    if (questionType === 'pilihan_ganda') {
        // Check if pilihan inputs exist
        const pilihanInputs = document.querySelectorAll('.pilihan-input');
        if (pilihanInputs.length > 0) {
            if (!validatePilihan()) {
                e.preventDefault();
                alert('Semua pilihan jawaban harus berbeda!');
                return false;
            }
        }
    }
});
</script>
@endsection

