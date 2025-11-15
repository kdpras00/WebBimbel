@extends('layouts.app')

@section('title', 'Buat Quiz')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Buat Quiz</h1>
</div>

<div class="rounded-lg shadow border border-gray-200 p-6" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
    <form action="{{ route('pengajar.quiz.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-black">Judul</label>
            <input type="text" name="judul" value="{{ old('judul') }}" required
                   class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-black">Mata Pelajaran</label>
            <select name="mapel_id" id="mapel_id" required
                    class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                <option value="">Pilih Mata Pelajaran</option>
                @foreach($mapel as $m)
                    <option value="{{ $m->id }}" 
                            data-kelas="{{ $m->kelas->nama }}"
                            {{ old('mapel_id') == $m->id ? 'selected' : '' }}>
                        {{ $m->nama }} - {{ $m->kelas->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4" id="jurusanField" style="display: none;">
            <label class="block mb-2 text-sm font-medium text-black">Jurusan <span class="text-red-500">*</span></label>
            <select name="jurusan" id="jurusanSelect"
                    class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                <option value="">Pilih Jurusan</option>
                @foreach($jurusanOptions as $jurusan)
                    <option value="{{ $jurusan }}" {{ old('jurusan') == $jurusan ? 'selected' : '' }}>
                        {{ $jurusan }}
                    </option>
                @endforeach
            </select>
            <p class="mt-1 text-sm text-gray-500">Pilih jurusan untuk kelas 10-12. Field ini hanya muncul untuk kelas 10, 11, dan 12.</p>
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-black">Deskripsi</label>
            <textarea name="deskripsi" rows="3"
                      class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-black">Durasi (menit)</label>
            <input type="number" name="durasi" value="{{ old('durasi') }}" min="1"
                   class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        </div>

        <div class="mb-4">
            <div class="flex items-center">
                <input type="checkbox" name="is_published" id="is_published" value="1"
                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 focus:ring-2">
                <label for="is_published" class="ml-2 text-sm font-medium text-black">
                    Publish Quiz (tampilkan ke siswa)
                </label>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Buat Quiz</button>
            <a href="{{ route('pengajar.quiz.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>

<script>
// Show/hide jurusan field based on kelas
function toggleJurusanField() {
    const selectedOption = document.getElementById('mapel_id').options[document.getElementById('mapel_id').selectedIndex];
    const kelasNama = selectedOption ? selectedOption.getAttribute('data-kelas') : '';
    const jurusanField = document.getElementById('jurusanField');
    const jurusanSelect = document.getElementById('jurusanSelect');
    
    // Extract kelas number from nama (e.g., "Kelas 10" -> 10)
    const kelasMatch = kelasNama.match(/\d+/);
    const kelasNumber = kelasMatch ? parseInt(kelasMatch[0]) : 0;
    
    // Show jurusan field only for kelas 10, 11, 12
    if (kelasNumber >= 10 && kelasNumber <= 12) {
        jurusanField.style.display = 'block';
        if (jurusanSelect) {
            jurusanSelect.setAttribute('required', 'required');
        }
    } else {
        jurusanField.style.display = 'none';
        // Reset jurusan value for kelas 1-9
        if (jurusanSelect) {
            jurusanSelect.value = '';
            jurusanSelect.removeAttribute('required');
        }
    }
}

document.getElementById('mapel_id').addEventListener('change', toggleJurusanField);

// Trigger on load
toggleJurusanField();
</script>
@endsection

