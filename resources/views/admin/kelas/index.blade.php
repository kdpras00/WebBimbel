@extends('layouts.app')

@section('title', 'Kelola Kelas')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Kelola Kelas</h1>
    <a href="{{ route('admin.kelas.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
        Tambah Kelas
    </a>
</div>

<div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Nama</th>
                    <th scope="col" class="px-6 py-3">Deskripsi</th>
                    <th scope="col" class="px-6 py-3">Siswa</th>
                    <th scope="col" class="px-6 py-3">Pengajar</th>
                    <th scope="col" class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($kelas as $k)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $k->nama }}</td>
                        <td class="px-6 py-4">{{ $k->deskripsi }}</td>
                        <td class="px-6 py-4">{{ $k->siswa_count }}</td>
                        <td class="px-6 py-4">{{ $k->pengajar_count }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.kelas.edit', $k->id) }}" class="text-blue-600 hover:underline mr-3">Edit</a>
                            <form action="{{ route('admin.kelas.destroy', $k->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">
        {{ $kelas->links() }}
    </div>
</div>
@endsection

