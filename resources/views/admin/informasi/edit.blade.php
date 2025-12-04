@extends('layouts.app')

@section('title', 'Edit Informasi')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Edit Informasi</h1>
    <p class="mt-2 text-blue-100">Perbarui data pengumuman</p>
</div>

<div class="bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">
    <div class="p-6 lg:p-8">
        <form action="{{ route('admin.informasi.update', $informasi->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Left Column: Main Inputs -->
                <div class="lg:col-span-2 space-y-6">
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-slate-700">Judul Informasi</label>
                        <input type="text" name="judul" value="{{ $informasi->judul }}" required
                               class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all text-slate-700 placeholder-slate-400">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-semibold text-slate-700">Deskripsi Lengkap</label>
                        <textarea name="deskripsi" rows="8" required
                                  class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all text-slate-700 placeholder-slate-400">{{ $informasi->deskripsi }}</textarea>
                    </div>
                </div>

                <!-- Right Column: Meta & Actions -->
                <div class="space-y-6">
                    <div class="bg-slate-50 p-6 rounded-xl border border-slate-100">
                        <h3 class="font-semibold text-slate-800 mb-4">Pengaturan Publikasi</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <label class="block mb-2 text-sm font-medium text-slate-700">Tanggal Publikasi</label>
                                <input type="date" name="tanggal" value="{{ $informasi->tanggal->format('Y-m-d') }}" required
                                       class="w-full px-4 py-2.5 rounded-lg border border-slate-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all text-slate-700">
                            </div>

                            <div class="flex items-center p-3 bg-white rounded-lg border border-slate-200">
                                <input id="is_active" type="checkbox" name="is_active" value="1" {{ $informasi->is_active ? 'checked' : '' }}
                                       class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500">
                                <label for="is_active" class="ml-3 text-sm font-medium text-slate-700 cursor-pointer select-none">
                                    Publikasikan Sekarang
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3">
                        <button type="submit" class="w-full px-5 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-semibold shadow-lg shadow-blue-600/20 transition-all transform hover:scale-[1.02]">
                            Simpan Perubahan
                        </button>
                        <a href="{{ route('admin.informasi.index') }}" class="w-full px-5 py-3 bg-white text-slate-700 border border-slate-200 rounded-xl hover:bg-slate-50 font-medium text-center transition-all">
                            Batal
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
