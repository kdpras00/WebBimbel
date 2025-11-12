@extends('layouts.app')

@section('title', 'Kelola Gamifikasi')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Kelola Gamifikasi</h1>
    <button type="button" onclick="document.getElementById('addForm').classList.toggle('hidden')" 
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
        Tambah Aturan
    </button>
</div>

<!-- Add Form -->
<div id="addForm" class="hidden mb-6 bg-white border border-gray-200 rounded-lg shadow @dark:bg-gray-800 dark:border-gray-700 p-6">
    <h2 class="text-xl font-bold mb-4">Tambah Aturan Poin</h2>
    <form action="{{ route('admin.gamification.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Aturan</label>
                <input type="text" name="nama_aturan" required
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Poin</label>
                <input type="number" name="poin" required min="0"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nilai Min</label>
                <input type="number" name="nilai_min" min="0" max="100"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nilai Max</label>
                <input type="number" name="nilai_max" min="0" max="100"
                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
            </div>
            <div class="md:col-span-2">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Keterangan</label>
                <textarea name="keterangan" rows="2"
                          class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"></textarea>
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
        </div>
    </form>
</div>

<!-- Settings List -->
<div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Nama Aturan</th>
                    <th scope="col" class="px-6 py-3">Nilai Min</th>
                    <th scope="col" class="px-6 py-3">Nilai Max</th>
                    <th scope="col" class="px-6 py-3">Poin</th>
                    <th scope="col" class="px-6 py-3">Keterangan</th>
                    <th scope="col" class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($settings as $setting)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $setting->nama_aturan }}</td>
                        <td class="px-6 py-4">{{ $setting->nilai_min ?? '-' }}</td>
                        <td class="px-6 py-4">{{ $setting->nilai_max ?? '-' }}</td>
                        <td class="px-6 py-4 font-bold">{{ $setting->poin }}</td>
                        <td class="px-6 py-4">{{ $setting->keterangan }}</td>
                        <td class="px-6 py-4">
                            <button onclick="editSetting({{ $setting->id }})" class="text-blue-600 hover:underline mr-3">Edit</button>
                            <form action="{{ route('admin.gamification.destroy', $setting->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Modal (simplified - bisa dibuat lebih baik dengan modal) -->
<script>
function editSetting(id) {
    // Implementasi edit bisa menggunakan modal atau redirect ke halaman edit
    window.location.href = '/admin/gamification/' + id + '/edit';
}
</script>
@endsection

