@extends('layouts.app')

@section('title', 'Kelola Materi')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h1 class="text-3xl font-bold text-white">Kelola Materi</h1>
    <a href="{{ route('pengajar.materi.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
        Tambah Materi
    </a>
</div>

<div class="rounded-lg shadow border border-gray-200" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-black">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                <tr>
                    <th scope="col" class="px-6 py-3">Judul</th>
                    <th scope="col" class="px-6 py-3">Mata Pelajaran</th>
                    <th scope="col" class="px-6 py-3">Tipe</th>
                    <th scope="col" class="px-6 py-3">Tanggal</th>
                    <th scope="col" class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($materi as $m)
                    <tr class="bg-white border-b border-gray-200">
                        <td class="px-6 py-4 font-medium text-black">{{ $m->judul }}</td>
                        <td class="px-6 py-4 text-black">{{ $m->mapel->nama }} ({{ $m->mapel->kelas->nama }})</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs font-medium rounded bg-blue-100 text-blue-800">
                                {{ strtoupper($m->tipe) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">{{ $m->created_at->format('d M Y') }}</td>
                        <td class="px-6 py-4">
                            {{-- <a href="{{ route('pengajar.materi.show', $m->id) }}" class="text-green-600 hover:underline mr-3">Detail</a> --}}
                            <a href="{{ route('pengajar.materi.edit', $m->id) }}" class="text-blue-600 hover:underline mr-3">Edit</a>
                            <form action="{{ route('pengajar.materi.destroy', $m->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline" onclick="confirmDelete(event, 'Yakin ingin menghapus materi ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada materi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-4">
        {{ $materi->links() }}
    </div>
</div>
@endsection

