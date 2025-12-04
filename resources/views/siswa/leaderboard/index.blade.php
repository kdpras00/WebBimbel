@extends('layouts.app')

@section('title', 'Leaderboard')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-white">Leaderboard</h1>
    <p class="mt-2 text-gray-100">Peringkat siswa berdasarkan total poin</p>
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

<div class="rounded-lg shadow border border-gray-200" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-black">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
                <tr>
                    <th scope="col" class="px-6 py-3 text-center">Peringkat</th>
                    <th scope="col" class="px-6 py-3">Nama</th>
                    <th scope="col" class="px-6 py-3">Total Poin</th>
                </tr>
            </thead>
            <tbody>
                @forelse($leaderboard as $index => $point)
                    <tr class="bg-white border-b border-gray-200 
                        @if($point->user_id == Auth::id()) bg-blue-50 @endif">
                        <td class="px-6 py-4 text-center">
                            @php
                                $badge = $point->badge;
                                $emoji = match($badge) {
                                    'Diamond' => '💎',
                                    'Gold' => '🥇',
                                    'Silver' => '🥈',
                                    'Bronze' => '🥉',
                                    default => '🥉'
                                };
                            @endphp
                            <div class="flex flex-col items-center justify-center">
                                <span class="text-3xl" title="{{ $badge }}">{{ $emoji }}</span>
                                <span class="text-xs font-bold text-gray-500 mt-1">#{{ $index + 1 }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 font-medium text-black">
                            {{ $point->user->name }}
                            @if($point->user_id == Auth::id())
                                <span class="ml-2 text-xs text-blue-600">(Anda)</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-bold text-black">
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

