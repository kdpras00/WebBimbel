@extends('layouts.app')

@section('title', 'Tambah Kelas')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Tambah Kelas</h1>
</div>

<div class="rounded-lg shadow border border-gray-200 p-6" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
    <form action="{{ route('admin.kelas.store') }}" method="POST" id="kelasForm">
        @csrf
        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-black">Kelas</label>
            <select name="kelas_number" id="kelasNumber" required
                    class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                <option value="">Pilih Kelas</option>
                @for($i = 1; $i <= 12; $i++)
                    <option value="{{ $i }}" {{ old('kelas_number') == $i ? 'selected' : '' }}>
                        Kelas {{ $i }}
                    </option>
                @endfor
            </select>
            <input type="hidden" name="nama" id="namaKelas" value="{{ old('nama') }}">
            @error('nama') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4" id="jurusanField" style="display: none;">
            <label class="block mb-2 text-sm font-medium text-black">Jurusan yang Tersedia</label>
            <div class="space-y-2">
                <label class="flex items-center">
                    <input type="radio" name="jurusan" value="IPA" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-black">IPA</span>
                </label>
                <label class="flex items-center">
                    <input type="radio" name="jurusan" value="IPS" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500">
                    <span class="ml-2 text-sm text-black">IPS</span>
                </label>
            </div>
            <p class="mt-1 text-xs text-gray-500">Pilih jurusan yang tersedia untuk kelas 10-12</p>
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-black">Deskripsi</label>
            <textarea name="deskripsi" rows="3"
                      class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
            <a href="{{ route('admin.kelas.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>

<script>
document.getElementById('kelasNumber').addEventListener('change', function() {
    const kelasNumber = parseInt(this.value);
    const namaKelas = document.getElementById('namaKelas');
    const jurusanField = document.getElementById('jurusanField');
    
    // Set nama kelas
    if (kelasNumber) {
        let name = 'Kelas ' + kelasNumber;
        namaKelas.value = name;
    } else {
        namaKelas.value = '';
    }
    
    // Show/hide jurusan field
    if (kelasNumber >= 10 && kelasNumber <= 12) {
        jurusanField.style.display = 'block';
    } else {
        jurusanField.style.display = 'none';
        // Uncheck all jurusan radios
        document.querySelectorAll('input[name="jurusan"]').forEach(rb => rb.checked = false);
    }
});




// Trigger on load if old value exists
if (document.getElementById('kelasNumber').value) {
    document.getElementById('kelasNumber').dispatchEvent(new Event('change'));
}
</script>
@endsection

