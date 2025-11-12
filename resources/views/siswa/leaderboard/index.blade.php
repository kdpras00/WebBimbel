@extends('layouts.app')

@section('title', 'Leaderboard')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Leaderboard</h1>
    <p class="mt-2 text-gray-600 dark:text-gray-400">Peringkat siswa berdasarkan total poin</p>
</div>

@if($userRank)
    <div class="mb-6 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm opacity-90">Peringkat Anda</p>
                <p class="text-3xl font-bold">#{{ $userRank }}</p>
            </div>
            @php
                $userPoint = \App\Models\Point::where('user_id', Auth::id())->first();
            @endphp
            @if($userPoint)
                <div class="text-right">
                    <p class="text-sm opacity-90">Total Poin</p>
                    <p class="text-3xl font-bold">{{ $userPoint->total_poin }}</p>
                </div>
            @endif
        </div>
    </div>
@endif

<div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Peringkat</th>
                    <th scope="col" class="px-6 py-3">Nama</th>
                    <th scope="col" class="px-6 py-3">Total Poin</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leaderboard as $index => $point)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 
                        @if($point->user_id == Auth::id()) bg-blue-50 dark:bg-blue-900/20 @endif">
                        <td class="px-6 py-4">
                            @if($index < 3)
                                <span class="text-2xl">
                                    @if($index == 0) 🥇
                                    @elseif($index == 1) 🥈
                                    @else 🥉
                                    @endif
                                </span>
                            @else
                                <span class="font-bold text-gray-900 dark:text-white">#{{ $index + 1 }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                            {{ $point->user->name }}
                            @if($point->user_id == Auth::id())
                                <span class="ml-2 text-xs text-blue-600 dark:text-blue-400">(Anda)</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-bold text-gray-900 dark:text-white">
                            {{ $point->total_poin }} poin
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 text-center text-gray-500">Belum ada data leaderboard</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

