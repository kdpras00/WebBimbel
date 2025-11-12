@extends('layouts.app')

@section('title', 'Edit Materi')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Materi</h1>
</div>

<div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-6">
    <form action="{{ route('pengajar.materi.update', $materi->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Judul</label>
            <input type="text" name="judul" value="{{ old('judul', $materi->judul) }}" required
                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Mata Pelajaran</label>
            <select name="mapel_id" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                @foreach($mapel as $m)
                    <option value="{{ $m->id }}" {{ $materi->mapel_id == $m->id ? 'selected' : '' }}>
                        {{ $m->nama }} - {{ $m->kelas->nama }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipe</label>
            <select name="tipe" id="tipe" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option value="teks" {{ $materi->tipe == 'teks' ? 'selected' : '' }}>Teks</option>
                <option value="pdf" {{ $materi->tipe == 'pdf' ? 'selected' : '' }}>PDF</option>
                <option value="video" {{ $materi->tipe == 'video' ? 'selected' : '' }}>Video</option>
            </select>
        </div>

        <div class="mb-4" id="fileInput">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">File (PDF/Video)</label>
            @if($materi->file_path)
                <p class="mb-2 text-sm text-gray-600 dark:text-gray-400">File saat ini: {{ basename($materi->file_path) }}</p>
            @endif
            <input type="file" name="file_path" 
                   accept=".pdf,.mp4,.avi,.mov"
                   class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600">
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">Kosongkan jika tidak ingin mengubah file</p>
        </div>

        <div class="mb-4" id="textInput">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Konten Teks</label>
            <textarea name="konten" rows="10"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">{{ old('konten', $materi->konten) }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi</label>
            <textarea name="deskripsi" rows="3"
                      class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">{{ old('deskripsi', $materi->deskripsi) }}</textarea>
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
    
    if (tipe === 'teks') {
        fileInput.style.display = 'none';
        textInput.style.display = 'block';
    } else {
        fileInput.style.display = 'block';
        textInput.style.display = 'none';
    }
});

// Trigger on load
document.getElementById('tipe').dispatchEvent(new Event('change'));
</script>
@endsection

