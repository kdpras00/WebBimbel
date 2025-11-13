@extends('layouts.app')

@section('title', 'Edit Mata Pelajaran')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-black">Edit Mata Pelajaran</h1>
</div>

<div class="rounded-lg shadow border border-gray-200 p-6" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
    <form action="{{ route('admin.mapel.update', $mapel->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-black text-black">Nama</label>
            <input type="text" name="nama" value="{{ old('nama', $mapel->nama) }}" required
                   class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 text-black">
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-black text-black">Kelas</label>
            <select name="kelas_id" required
                    class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 text-black">
                @foreach($kelas as $k)
                    <option value="{{ $k->id }}" {{ $mapel->kelas_id == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-black text-black">Deskripsi</label>
            <textarea name="deskripsi" rows="3"
                      class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 text-black">{{ old('deskripsi', $mapel->deskripsi) }}</textarea>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update</button>
            <a href="{{ route('admin.mapel.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>
@endsection

