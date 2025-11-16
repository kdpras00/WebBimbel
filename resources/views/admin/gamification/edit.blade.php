@extends('layouts.app')

@section('title', 'Edit Aturan Gamifikasi')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Edit Aturan Gamifikasi</h1>
</div>

<div class="rounded-lg shadow border border-gray-200 p-6" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
    <form action="{{ route('admin.gamification.update', $setting->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block mb-2 text-sm font-medium text-black">Nama Aturan</label>
                <input type="text" name="nama_aturan" value="{{ old('nama_aturan', $setting->nama_aturan) }}" required
                       class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-black">Poin</label>
                <input type="number" name="poin" value="{{ old('poin', $setting->poin) }}" required min="0"
                       class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-black">Nilai Min</label>
                <input type="number" name="nilai_min" value="{{ old('nilai_min', $setting->nilai_min) }}" min="0" max="100"
                       class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium text-black">Nilai Max</label>
                <input type="number" name="nilai_max" value="{{ old('nilai_max', $setting->nilai_max) }}" min="0" max="100"
                       class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            </div>
            <div class="md:col-span-2">
                <label class="block mb-2 text-sm font-medium text-black">Keterangan</label>
                <textarea name="keterangan" rows="2"
                          class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">{{ old('keterangan', $setting->keterangan) }}</textarea>
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update</button>
            <a href="{{ route('admin.gamification.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 ml-2">Batal</a>
        </div>
    </form>
</div>
@endsection

