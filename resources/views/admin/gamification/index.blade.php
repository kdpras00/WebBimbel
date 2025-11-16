@extends('layouts.app')

@section('title', 'Kelola Gamifikasi')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-3xl font-bold text-white">Kelola Gamifikasi</h1>
    <button type="button" onclick="document.getElementById('addForm').classList.toggle('hidden')" 
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
        Tambah Aturan
    </button>
</div>

<!-- Add Form -->
<div id="addForm" class="hidden mb-6 rounded-lg shadow border border-gray-200 p-6" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
    <h2 class="text-xl font-bold mb-4 text-black">Tambah Aturan Poin</h2>
    <form action="{{ route('admin.gamification.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block mb-2 text-sm font-medium text-black">Nama Aturan</label>
                <input type="text" name="nama_aturan" required
                       class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-black">Poin</label>
                <input type="number" name="poin" required min="0"
                       class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-black">Nilai Min</label>
                <input type="number" name="nilai_min" min="0" max="100"
                       class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-black">Nilai Max</label>
                <input type="number" name="nilai_max" min="0" max="100"
                       class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>
            <div class="md:col-span-2">
                <label class="block mb-2 text-sm font-medium text-black">Keterangan</label>
                <textarea name="keterangan" rows="2"
                          class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"></textarea>
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Simpan</button>
        </div>
    </form>
</div>

<!-- Settings List -->
<div class="rounded-lg shadow border border-gray-200" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-black">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
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
                    <tr class="bg-white border-b border-gray-200">
                        <td class="px-6 py-4 font-medium text-black">{{ $setting->nama_aturan }}</td>
                        <td class="px-6 py-4 text-black">{{ $setting->nilai_min ?? '-' }}</td>
                        <td class="px-6 py-4 text-black">{{ $setting->nilai_max ?? '-' }}</td>
                        <td class="px-6 py-4 font-bold text-black">{{ $setting->poin }}</td>
                        <td class="px-6 py-4 text-black">{{ $setting->keterangan }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.gamification.edit', $setting->id) }}" class="text-blue-600 hover:underline mr-3">Edit</a>
                            <form action="{{ route('admin.gamification.destroy', $setting->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline" onclick="confirmDelete(event, 'Yakin ingin menghapus aturan {{ $setting->nama_aturan }}?')">Hapus</button>
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

@endsection

