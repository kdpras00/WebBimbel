@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Tambah User</h1>
</div>

<div class="rounded-lg shadow border border-gray-200 p-6 overflow-visible" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-black">Nama</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-black">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-black">Password</label>
            <input type="password" name="password" required
                   class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-black">Role</label>
            <select name="role" required
                    class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                <option value="">Pilih Role</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="pengajar" {{ old('role') == 'pengajar' ? 'selected' : '' }}>Pengajar</option>
                <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                <option value="wali" {{ old('role') == 'wali' ? 'selected' : '' }}>Wali</option>
            </select>
            @error('role') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="mb-4" id="waliField" style="display: none;">
            <label class="block mb-2 text-sm font-medium text-black">Wali (jika siswa)</label>
            <select name="wali_id"
                    class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                <option value="">Pilih Wali</option>
                @foreach($walis as $wali)
                    <option value="{{ $wali->id }}" {{ old('wali_id') == $wali->id ? 'selected' : '' }}>{{ $wali->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4" id="siswaField" style="display: none;">
            <label class="block mb-2 text-sm font-medium text-black">Siswa (jika wali)</label>
            <select name="siswa_id"
                    class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                <option value="">Pilih Siswa</option>
                @php
                    $siswas = \App\Models\User::where('role', 'siswa')->get();
                @endphp
                @foreach($siswas as $siswa)
                    <option value="{{ $siswa->id }}" {{ old('siswa_id') == $siswa->id ? 'selected' : '' }}>{{ $siswa->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4" id="kelasField" style="display: none;">
            <label class="block mb-2 text-sm font-medium text-black">Kelas</label>
            <select name="kelas_id" id="kelasSelect"
                    class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                <option value="">Pilih Kelas</option>
                @foreach($kelas as $k)
                    <option value="{{ $k->id }}" data-kelas="{{ $k->nama }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                        {{ $k->nama }}
                    </option>
                @endforeach
            </select>
            @error('kelas_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
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
            <p class="mt-1 text-xs text-gray-500">Hanya untuk kelas 10-12</p>
            @error('jurusan') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>

<script>
// Show/hide fields based on role
function toggleSiswaFields() {
    const role = document.querySelector('select[name="role"]').value;
    const waliField = document.getElementById('waliField');
    const siswaField = document.getElementById('siswaField');
    const kelasField = document.getElementById('kelasField');
    const jurusanField = document.getElementById('jurusanField');
    
    if (role === 'siswa') {
        waliField.style.display = 'block';
        kelasField.style.display = 'block';
        siswaField.style.display = 'none';
    } else if (role === 'wali') {
        siswaField.style.display = 'block';
        waliField.style.display = 'none';
        kelasField.style.display = 'none';
        jurusanField.style.display = 'none';
    } else {
        waliField.style.display = 'none';
        siswaField.style.display = 'none';
        kelasField.style.display = 'none';
        jurusanField.style.display = 'none';
        // Reset values
        document.querySelector('select[name="wali_id"]').value = '';
        if (document.querySelector('select[name="siswa_id"]')) {
            document.querySelector('select[name="siswa_id"]').value = '';
        }
        document.getElementById('kelasSelect').value = '';
        document.getElementById('jurusanSelect').value = '';
    }
}

// Show/hide jurusan field based on kelas
function toggleJurusanField() {
    const kelasSelect = document.getElementById('kelasSelect');
    const selectedOption = kelasSelect.options[kelasSelect.selectedIndex];
    const kelasNama = selectedOption ? selectedOption.getAttribute('data-kelas') : '';
    const jurusanField = document.getElementById('jurusanField');
    const jurusanSelect = document.getElementById('jurusanSelect');
    
    // Extract kelas number from nama (e.g., "Kelas 10" -> 10)
    const kelasMatch = kelasNama.match(/\d+/);
    const kelasNumber = kelasMatch ? parseInt(kelasMatch[0]) : 0;
    
    // Show jurusan field only for kelas 10, 11, 12
    if (kelasNumber >= 10 && kelasNumber <= 12) {
        jurusanField.style.display = 'block';
        jurusanSelect.setAttribute('required', 'required');
    } else {
        jurusanField.style.display = 'none';
        jurusanSelect.value = '';
        jurusanSelect.removeAttribute('required');
    }
}

document.querySelector('select[name="role"]').addEventListener('change', toggleSiswaFields);
document.getElementById('kelasSelect').addEventListener('change', toggleJurusanField);

// Trigger on load
toggleSiswaFields();
toggleJurusanField();
</script>
@endsection

