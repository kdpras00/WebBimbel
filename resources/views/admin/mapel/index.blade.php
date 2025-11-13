@extends('layouts.app')

@section('title', 'Kelola Mata Pelajaran')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-3xl font-bold text-white">Kelola Mata Pelajaran</h1>
    <a href="{{ route('admin.mapel.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
        Tambah Mapel
    </a>
</div>

<div class="rounded-lg shadow border border-gray-200" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-black">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                <tr>
                    <th scope="col" class="px-6 py-3">Nama</th>
                    <th scope="col" class="px-6 py-3">Kelas</th>
                    <th scope="col" class="px-6 py-3">Deskripsi</th>
                    <th scope="col" class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($mapel as $m)
                    <tr class="bg-white border-b border-gray-200">
                        <td class="px-6 py-4 font-medium text-black">{{ $m->nama }}</td>
                        <td class="px-6 py-4 text-black">{{ $m->kelas->nama }}</td>
                        <td class="px-6 py-4 text-black">{{ $m->deskripsi }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.mapel.edit', $m->id) }}" class="text-blue-600 hover:underline mr-3">Edit</a>
                            <form action="{{ route('admin.mapel.destroy', $m->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">
        {{ $mapel->links('vendor.pagination.custom') }}
    </div>
</div>
@endsection

