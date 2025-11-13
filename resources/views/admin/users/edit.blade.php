@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-black">Edit User</h1>
</div>

<div class="rounded-lg shadow border border-gray-200 p-6" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-black text-black">Nama</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                   class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 text-black">
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-black text-black">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required
                   class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 text-black">
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-black text-black">Password (kosongkan jika tidak diubah)</label>
            <input type="password" name="password"
                   class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 text-black">
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-black text-black">Role</label>
            <select name="role" required
                    class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 text-black">
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="pengajar" {{ $user->role == 'pengajar' ? 'selected' : '' }}>Pengajar</option>
                <option value="siswa" {{ $user->role == 'siswa' ? 'selected' : '' }}>Siswa</option>
                <option value="wali" {{ $user->role == 'wali' ? 'selected' : '' }}>Wali</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block mb-2 text-sm font-medium text-black text-black">Wali (jika siswa)</label>
            <select name="wali_id"
                    class="bg-white border border-gray-300 text-black text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 text-black">
                <option value="">Pilih Wali</option>
                @foreach($walis as $wali)
                    <option value="{{ $wali->id }}" {{ $user->wali_id == $wali->id ? 'selected' : '' }}>{{ $wali->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update</button>
            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>
@endsection

