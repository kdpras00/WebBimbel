@extends('layouts.app')

@section('title', 'Tambah Mata Pelajaran')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Tambah Mata Pelajaran</h1>
</div>

<div class="rounded-lg shadow border border-gray-200 p-6" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
    <form action="{{ route('admin.mapel.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-black text-black">Nama</label>
            <select id="namaSelect" class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 text-black mb-2">
                <option value="">Pilih Mata Pelajaran</option>
                @php
                    $commonSubjects = ['Matematika', 'Bahasa Indonesia', 'Bahasa Inggris', 'IPA', 'IPS', 'PKN', 'Agama', 'Penjaskes', 'Seni Budaya'];
                    $oldNama = old('nama');
                    $isOther = $oldNama && !in_array($oldNama, $commonSubjects);
                @endphp
                @foreach($commonSubjects as $subject)
                    <option value="{{ $subject }}" {{ $oldNama == $subject ? 'selected' : '' }}>{{ $subject }}</option>
                @endforeach
                <option value="Lainnya" {{ $isOther ? 'selected' : '' }}>Lainnya</option>
            </select>
            
            <input type="text" id="namaInput" name="nama" value="{{ old('nama') }}" 
                   class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 text-black {{ $isOther ? '' : 'hidden' }}"
                   placeholder="Masukkan nama mata pelajaran lainnya" {{ $isOther ? 'required' : '' }}>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const select = document.getElementById('namaSelect');
                    const input = document.getElementById('namaInput');

                    select.addEventListener('change', function() {
                        if (this.value === 'Lainnya') {
                            input.classList.remove('hidden');
                            input.value = '';
                            input.required = true;
                            input.focus();
                        } else {
                            input.classList.add('hidden');
                            input.value = this.value;
                            input.required = true;
                        }
                    });

                    // Initial check
                    if (select.value !== 'Lainnya' && select.value !== '') {
                        input.value = select.value;
                    }
                });
            </script>
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-black text-black">Kelas</label>
            <select name="kelas_id" required
                    class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 text-black">
                <option value="">Pilih Kelas</option>
                @foreach($kelas as $k)
                    <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-black text-black">Deskripsi</label>
            <textarea name="deskripsi" rows="3"
                      class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 text-black">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
            <a href="{{ route('admin.mapel.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>
@endsection

