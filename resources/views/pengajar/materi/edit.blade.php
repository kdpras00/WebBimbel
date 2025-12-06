@extends('layouts.app')

@section('title', 'Edit Materi')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Edit Materi</h1>
</div>

<div class="rounded-lg shadow border border-gray-200 p-6" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
    @if($errors->any())
        <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-center mb-2">
                <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-red-800 font-semibold">Terjadi Kesalahan!</h3>
            </div>
            <ul class="list-disc list-inside text-sm text-red-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
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
            <p class="mt-1 text-sm text-gray-500">
                <span class="font-semibold text-red-600">Maksimal 2 MB</span> - Kosongkan jika tidak ingin mengubah file
            </p>
            <div id="fileInfo"></div>
        </div>

        <div class="mb-4" id="textInput">
            <label class="block mb-2 text-sm font-medium text-black">Konten Teks</label>
            <!-- Create the editor container -->
            <div id="editor" class="bg-white" style="height: 300px;"></div>
            <input type="hidden" name="konten" id="konten">
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-black">Deskripsi</label>
            <textarea name="deskripsi" rows="3"
                      class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ old('deskripsi', $materi->deskripsi) }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit" id="submitBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <span id="submitText">Update</span>
                <span id="submitLoader" class="hidden">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Mengupload...
                </span>
            </button>
            <a href="{{ route('pengajar.materi.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>

<script>
const tipeSelect = document.getElementById('tipe');
if (tipeSelect) {
    tipeSelect.addEventListener('change', function() {
        const tipe = this.value;
        const fileInput = document.getElementById('fileInput');
        const textInput = document.getElementById('textInput');
        const fileInputField = document.getElementById('fileInputField');
        const fileLabel = document.getElementById('fileLabel');
        
        if (!fileInput || !textInput || !fileInputField || !fileLabel) return;
        
        if (tipe === 'teks') {
            fileInput.style.display = 'none';
            textInput.style.display = 'block';
            fileInputField.removeAttribute('required');
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
}

// Show/hide jurusan field based on kelas
function toggleJurusanField() {
    const mapelSelect = document.getElementById('mapel_id');
    if (!mapelSelect) return;
    
    const selectedIndex = mapelSelect.selectedIndex;
    const selectedOption = mapelSelect.options[selectedIndex];
    const kelasNama = selectedOption && selectedOption.getAttribute('data-kelas') ? selectedOption.getAttribute('data-kelas') : '';
    const jurusanField = document.getElementById('jurusanField');
    const jurusanSelect = document.getElementById('jurusanSelect');
    
    // Extract kelas number from nama (e.g., "Kelas 10" -> 10)
    const kelasMatch = kelasNama && typeof kelasNama === 'string' ? kelasNama.match(/\d+/) : null;
    const kelasNumber = kelasMatch ? parseInt(kelasMatch[0]) : 0;
    
    // Show jurusan field only for kelas 10, 11, 12
    if (kelasNumber >= 10 && kelasNumber <= 12) {
        if (jurusanField) jurusanField.style.display = 'block';
        if (jurusanSelect) {
            jurusanSelect.setAttribute('required', 'required');
        }
    } else {
        if (jurusanField) jurusanField.style.display = 'none';
        // Reset jurusan value for kelas 1-9
        if (jurusanSelect) {
            jurusanSelect.value = '';
            jurusanSelect.removeAttribute('required');
        }
    }
}

const mapelSelect = document.getElementById('mapel_id');
if (mapelSelect) {
    mapelSelect.addEventListener('change', toggleJurusanField);
}

// Initialize on load
function initializeFileInput() {
    const tipeSelect = document.getElementById('tipe');
    const fileInputField = document.getElementById('fileInputField');
    const fileLabel = document.getElementById('fileLabel');
    
    if (!tipeSelect || !fileInputField || !fileLabel) return;
    
    const tipe = tipeSelect.value;
    
    if (tipe === 'pdf') {
        fileInputField.setAttribute('accept', '.pdf');
        fileLabel.innerHTML = 'File PDF';
    } else if (tipe === 'video') {
        fileInputField.setAttribute('accept', '.mp4,.avi,.mov');
        fileLabel.innerHTML = 'File Video';
    }
}

// Trigger on load - wait for DOM to be fully ready
document.addEventListener('DOMContentLoaded', function() {
    const tipeSelect = document.getElementById('tipe');
    if (tipeSelect) {
        tipeSelect.dispatchEvent(new Event('change'));
    }
    toggleJurusanField();
    initializeFileInput();
});

// File size validation - Improved version
function validateFileSize(input) {
    if (!input || !input.files || input.files.length === 0) {
        return true;
    }
    
    const file = input.files[0];
    if (!file) {
        return true;
    }
    
    const fileSizeMB = file.size / (1024 * 1024);
    const maxSizeMB = 10;
    
    if (fileSizeMB > maxSizeMB) {
        Swal.fire({
            icon: 'error',
            title: 'File Terlalu Besar!',
            html: `<div class="text-left">
                <p class="mb-2">Ukuran file: <strong>${fileSizeMB.toFixed(2)} MB</strong></p>
                <p class="mb-2">Batas maksimal: <strong>${maxSizeMB} MB</strong></p>
                <p class="text-sm text-gray-600 mt-3">Silakan pilih file yang lebih kecil atau kompres file Anda terlebih dahulu.</p>
            </div>`,
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'OK'
        });
        input.value = '';
        
        // Remove file info if exists
        const fileInfo = document.getElementById('fileInfo');
        if (fileInfo) {
            fileInfo.remove();
        }
        
        return false;
    }
    
    // Show file info
    let fileInfo = document.getElementById('fileInfo');
    if (!fileInfo) {
        fileInfo = document.createElement('p');
        fileInfo.id = 'fileInfo';
        fileInfo.className = 'mt-2 text-sm';
        input.parentNode.appendChild(fileInfo);
    }
    
    if (fileSizeMB < maxSizeMB) {
        fileInfo.className = 'mt-2 text-sm text-green-600';
        fileInfo.textContent = `✓ File: ${file.name} (${fileSizeMB.toFixed(2)} MB)`;
    } else {
        fileInfo.className = 'mt-2 text-sm text-red-600';
        fileInfo.textContent = `✗ File terlalu besar: ${fileSizeMB.toFixed(2)} MB`;
    }
    
    return true;
}

const fileInputField = document.getElementById('fileInputField');
if (fileInputField) {
    fileInputField.addEventListener('change', function(e) {
        validateFileSize(e.target);
    });
}

// Helper function to reset loading indicator
function resetLoadingIndicator() {
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitLoader = document.getElementById('submitLoader');
    if (submitBtn && submitText && submitLoader) {
        submitBtn.disabled = false;
        submitText.classList.remove('hidden');
        submitLoader.classList.add('hidden');
    }
}

// Form submit handler with better error handling
const form = document.querySelector('form');
if (form) {
    let submitTimeout = null;
    
    form.addEventListener('submit', function(e) {
        const fileInput = document.getElementById('fileInputField');
        const submitBtn = document.getElementById('submitBtn');
        const submitText = document.getElementById('submitText');
        const submitLoader = document.getElementById('submitLoader');
        const tipeSelect = document.getElementById('tipe');
        
        // Validate file size before submit
        if (fileInput && fileInput.files.length > 0) {
            if (!validateFileSize(fileInput)) {
                e.preventDefault();
                return false;
            }
        }
        
        // Show loading indicator
        if (submitBtn && submitText && submitLoader) {
            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            submitLoader.classList.remove('hidden');
        }
        
        // Reset loading indicator after timeout (in case of error or no response)
        if (submitTimeout) clearTimeout(submitTimeout);
        submitTimeout = setTimeout(function() {
            resetLoadingIndicator();
            Swal.fire({
                icon: 'warning',
                title: 'Upload Gagal!',
                html: 'Upload memakan waktu terlalu lama atau terjadi error.<br><br>' +
                      'Kemungkinan penyebab:<br>' +
                      '1. Ukuran file terlalu besar (maksimal 2 MB)<br>' +
                      '2. Konfigurasi PHP belum diubah<br>' +
                      '3. Koneksi internet lambat<br><br>' +
                      'Pastikan <b>post_max_size</b> dan <b>upload_max_filesize</b> di php.ini XAMPP minimal 12M, lalu restart Apache.',
                confirmButtonColor: '#dc2626'
            });
        }, 30000); // 30 seconds timeout
    });
    
    // Reset loading indicator when page becomes visible again (handles back button, etc)
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            resetLoadingIndicator();
            if (submitTimeout) {
                clearTimeout(submitTimeout);
                submitTimeout = null;
            }
        }
    });
    
    // Reset loading indicator on page unload
    window.addEventListener('beforeunload', function() {
        if (submitTimeout) {
            clearTimeout(submitTimeout);
        }
    });
}
</script>
</script>

@push('styles')
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
    .ql-toolbar.ql-snow {
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
        border-color: #d1d5db;
        background-color: #f9fafb;
    }
    .ql-container.ql-snow {
        border-bottom-left-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
        border-color: #d1d5db;
        background-color: white;
        font-family: 'Poppins', sans-serif;
        font-size: 1rem;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var quill = new Quill('#editor', {
            theme: 'snow',
            placeholder: 'Tulis materi lengkap di sini...',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline', 'strike'],
                    ['blockquote', 'code-block'],
                    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                    [{ 'script': 'sub'}, { 'script': 'super' }],
                    [{ 'indent': '-1'}, { 'indent': '+1' }],
                    [{ 'color': [] }, { 'background': [] }],
                    [{ 'align': [] }],
                    ['link', 'image'],
                    ['clean']
                ]
            }
        });

        // Set initial content safely
        var oldContent = {!! json_encode(old('konten', $materi->konten)) !!};
        if (oldContent) {
            quill.root.innerHTML = oldContent;
        }

        // Update hidden input on submit
        var form = document.querySelector('form');
        form.addEventListener('submit', function() {
            var konten = document.querySelector('input[name=konten]');
            // Only update if using text mode
            if (document.getElementById('tipe').value === 'teks') {
                konten.value = quill.root.innerHTML;
            }
        });
    });
</script>
@endpush
@endsection

