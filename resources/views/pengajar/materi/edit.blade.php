@extends('layouts.app')

@section('title', 'Edit Materi')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-black">Edit Materi</h1>
</div>

<div class="rounded-lg shadow border border-gray-200 p-6" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
    <form action="{{ route('pengajar.materi.update', $materi->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-black">Judul</label>
            <input type="text" name="judul" value="{{ old('judul', $materi->judul) }}" required
                   class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-black">Mata Pelajaran</label>
            <select name="mapel_id" id="mapel_id" required
                    class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                @foreach($mapel as $m)
                    <option value="{{ $m->id }}" 
                            data-kelas="{{ $m->kelas->nama }}"
                            {{ $materi->mapel_id == $m->id ? 'selected' : '' }}>
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
                    <option value="{{ $jurusan }}" {{ old('jurusan', $materi->jurusan) == $jurusan ? 'selected' : '' }}>
                        {{ $jurusan }}
                    </option>
                @endforeach
            </select>
            <p class="mt-1 text-sm text-gray-500">Pilih jurusan untuk kelas 10-12. Field ini hanya muncul untuk kelas 10, 11, dan 12.</p>
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-black">Tipe</label>
            <select name="tipe" id="tipe" required
                    class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                <option value="teks" {{ $materi->tipe == 'teks' ? 'selected' : '' }}>Teks</option>
                <option value="pdf" {{ $materi->tipe == 'pdf' ? 'selected' : '' }}>PDF</option>
                <option value="video" {{ $materi->tipe == 'video' ? 'selected' : '' }}>Video</option>
            </select>
        </div>

        <div class="mb-4" id="fileInput">
            <label class="block mb-2 text-sm font-medium text-black" id="fileLabel">File</label>
            @if($materi->file_path)
                <p class="mb-2 text-sm text-gray-600">File saat ini: {{ basename($materi->file_path) }}</p>
            @endif
            <input type="file" name="file_path" id="fileInputField"
                   class="block w-full text-sm text-black border border-gray-300 rounded-lg cursor-pointer bg-white focus:outline-none">
            <p class="mt-1 text-sm text-gray-500">Kosongkan jika tidak ingin mengubah file</p>
        </div>

        <div class="mb-4" id="textInput">
            <label class="block mb-2 text-sm font-medium text-black">Konten Teks</label>
            <textarea name="konten" rows="10"
                      class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ old('konten', $materi->konten) }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-black">Deskripsi</label>
            <textarea name="deskripsi" rows="3"
                      class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ old('deskripsi', $materi->deskripsi) }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update</button>
            <a href="{{ route('pengajar.materi.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>

<script>
document.getElementById('tipe').addEventListener('change', function() {
    const tipe = this.value;
    const fileInput = document.getElementById('fileInput');
    const textInput = document.getElementById('textInput');
    const fileInputField = document.getElementById('fileInputField');
    const fileLabel = document.getElementById('fileLabel');
    
    if (tipe === 'teks') {
        fileInput.style.display = 'none';
        textInput.style.display = 'block';
    } else if (tipe === 'pdf') {
        fileInput.style.display = 'block';
        textInput.style.display = 'none';
        fileInputField.setAttribute('accept', '.pdf');
        fileLabel.innerHTML = 'File PDF';
    } else if (tipe === 'video') {
        fileInput.style.display = 'block';
        textInput.style.display = 'none';
        fileInputField.setAttribute('accept', '.mp4,.avi,.mov');
        fileLabel.innerHTML = 'File Video';
    }
});

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

// Initialize on load
function initializeFileInput() {
    const tipe = document.getElementById('tipe').value;
    const fileInputField = document.getElementById('fileInputField');
    const fileLabel = document.getElementById('fileLabel');
    
    if (tipe === 'pdf') {
        fileInputField.setAttribute('accept', '.pdf');
        fileLabel.innerHTML = 'File PDF';
    } else if (tipe === 'video') {
        fileInputField.setAttribute('accept', '.mp4,.avi,.mov');
        fileLabel.innerHTML = 'File Video';
    }
}

// Trigger on load
document.getElementById('tipe').dispatchEvent(new Event('change'));
toggleJurusanField();
initializeFileInput();
</script>
@endsection

