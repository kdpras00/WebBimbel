@extends('layouts.app')

@section('title', 'Edit Kelas')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Edit Kelas: {{ $kelas->nama }}</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Edit Kelas -->
    <div class="rounded-lg shadow border border-gray-200 p-6" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
        <h2 class="text-xl font-semibold mb-4 text-black">Informasi Kelas</h2>
        <form action="{{ route('admin.kelas.update', $kelas->id) }}" method="POST" id="kelasForm">
            @csrf
            @method('PUT')
            @php
                preg_match('/\d+/', $kelas->nama, $matches);
                $currentKelasNumber = !empty($matches) ? (int)$matches[0] : null;
            @endphp
            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-black">Kelas</label>
                <select name="kelas_number" id="kelasNumber" required
                        class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Pilih Kelas</option>
                    @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ old('kelas_number', $currentKelasNumber) == $i ? 'selected' : '' }}>
                            Kelas {{ $i }}
                        </option>
                    @endfor
                </select>
                <input type="hidden" name="nama" id="namaKelas" value="{{ old('nama', $kelas->nama) }}">
                @error('nama') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="mb-4" id="jurusanField" style="display: {{ $showJurusan ? 'block' : 'none' }};">
                <label class="block mb-2 text-sm font-medium text-black">Jurusan yang Tersedia</label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="jurusan[]" value="IPA" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-black">IPA</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="jurusan[]" value="IPS" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-black">IPS</span>
                    </label>
                </div>
                <p class="mt-1 text-xs text-gray-500">Pilih jurusan yang tersedia untuk kelas 10-12</p>
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-black">Deskripsi</label>
                <textarea name="deskripsi" rows="3"
                          class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ old('deskripsi', $kelas->deskripsi) }}</textarea>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update</button>
                <a href="{{ route('admin.kelas.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Batal</a>
            </div>
        </form>
    </div>

    <!-- Assign Pengajar -->
    <div class="rounded-lg shadow border border-gray-200 p-6" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
        <h2 class="text-xl font-semibold mb-4 text-black">Assign Pengajar ke Mapel</h2>
        <form action="{{ route('admin.kelas.assign-pengajar', $kelas->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-black">Mata Pelajaran</label>
                <select name="mapel_id" required
                        class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Pilih Mata Pelajaran</option>
                    @foreach($kelas->mapel as $mapel)
                        <option value="{{ $mapel->id }}">{{ $mapel->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-black">Pengajar</label>
                <select name="pengajar_id" required
                        class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Pilih Pengajar</option>
                    @foreach($pengajar as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                Assign Pengajar
            </button>
        </form>
    </div>

    <!-- Assign Siswa -->
    <div class="rounded-lg shadow border border-gray-200 p-6" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
        <h2 class="text-xl font-semibold mb-4 text-black">Assign Siswa ke Kelas</h2>
        <form action="{{ route('admin.kelas.assign-siswa', $kelas->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-black">Siswa</label>
                <select name="siswa_id" id="siswaSelect" required
                        class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Pilih Siswa</option>
                    @foreach($siswa as $s)
                        <option value="{{ $s->id }}">{{ $s->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4" id="jurusanSiswaField" style="display: {{ $showJurusan ? 'block' : 'none' }};">
                <label class="block mb-2 text-sm font-medium text-black">Jurusan <span class="text-red-500">*</span></label>
                <select name="jurusan" id="jurusanSiswaSelect" {{ $showJurusan ? 'required' : '' }}
                        class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">Pilih Jurusan</option>
                    @foreach($jurusanOptions as $jurusan)
                        <option value="{{ $jurusan }}">{{ $jurusan }}</option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-500">Hanya untuk kelas 10-12</p>
            </div>

            <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                Assign Siswa
            </button>
        </form>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
    <!-- Daftar Pengajar yang Sudah Di-assign -->
    <div class="rounded-lg shadow border border-gray-200 p-6" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
        <h2 class="text-xl font-semibold mb-4 text-black">Daftar Pengajar per Mata Pelajaran</h2>
        @if($kelas->mapel->count() > 0)
            <div class="space-y-4">
                @foreach($kelas->mapel as $mapel)
                    <div class="border border-gray-200 rounded-lg p-4 bg-white">
                        <h3 class="font-semibold text-black mb-2">{{ $mapel->nama }}</h3>
                        @php
                            $pengajarMapel = DB::table('kelas_pengajar')
                                ->where('kelas_id', $kelas->id)
                                ->where('mapel_id', $mapel->id)
                                ->join('users', 'kelas_pengajar.pengajar_id', '=', 'users.id')
                                ->select('users.id', 'users.name')
                                ->get();
                        @endphp
                        @if($pengajarMapel->count() > 0)
                            <ul class="list-disc list-inside space-y-1">
                                @foreach($pengajarMapel as $p)
                                    <li class="text-black flex items-center justify-between">
                                        <span>{{ $p->name }}</span>
                                        <form action="{{ route('admin.kelas.unassign-pengajar', $kelas->id) }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="mapel_id" value="{{ $mapel->id }}">
                                            <input type="hidden" name="pengajar_id" value="{{ $p->id }}">
                                            <button type="submit" class="text-red-600 hover:underline text-sm" onclick="return confirm('Yakin ingin menghapus pengajar ini?')">
                                                Hapus
                                            </button>
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-gray-500 text-sm">Belum ada pengajar yang di-assign</p>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">Belum ada mata pelajaran untuk kelas ini. Silakan tambahkan mata pelajaran terlebih dahulu.</p>
        @endif
    </div>

    <!-- Daftar Siswa yang Sudah Di-assign -->
    <div class="rounded-lg shadow border border-gray-200 p-6" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
        <h2 class="text-xl font-semibold mb-4 text-black">Daftar Siswa di Kelas</h2>
        @php
            $siswaKelas = DB::table('kelas_siswa')
                ->where('kelas_id', $kelas->id)
                ->join('users', 'kelas_siswa.siswa_id', '=', 'users.id')
                ->select('users.id', 'users.name', 'kelas_siswa.jurusan')
                ->orderBy('users.name')
                ->get();
        @endphp
        @if($siswaKelas->count() > 0)
            <div class="space-y-2">
                @foreach($siswaKelas as $s)
                    <div class="border border-gray-200 rounded-lg p-3 bg-white flex items-center justify-between">
                        <div>
                            <span class="text-black font-medium">{{ $s->name }}</span>
                            @if($s->jurusan)
                                <span class="ml-2 text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded">{{ $s->jurusan }}</span>
                            @endif
                        </div>
                        <form action="{{ route('admin.kelas.unassign-siswa', $kelas->id) }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="siswa_id" value="{{ $s->id }}">
                            <button type="submit" class="text-red-600 hover:underline text-sm" onclick="return confirm('Yakin ingin menghapus siswa ini?')">
                                Hapus
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500">Belum ada siswa yang di-assign ke kelas ini.</p>
        @endif
    </div>
</div>

<script>
document.getElementById('kelasNumber').addEventListener('change', function() {
    const kelasNumber = parseInt(this.value);
    const namaKelas = document.getElementById('namaKelas');
    const jurusanField = document.getElementById('jurusanField');
    
    // Set nama kelas
    if (kelasNumber) {
        namaKelas.value = 'Kelas ' + kelasNumber;
    } else {
        namaKelas.value = '';
    }
    
    // Show/hide jurusan field
    if (kelasNumber >= 10 && kelasNumber <= 12) {
        jurusanField.style.display = 'block';
    } else {
        jurusanField.style.display = 'none';
        // Uncheck all jurusan checkboxes
        document.querySelectorAll('input[name="jurusan[]"]').forEach(cb => cb.checked = false);
    }
});

// Trigger on load
if (document.getElementById('kelasNumber').value) {
    document.getElementById('kelasNumber').dispatchEvent(new Event('change'));
}
</script>
@endsection

