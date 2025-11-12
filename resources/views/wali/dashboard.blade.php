@extends('layouts.app')

@section('title', 'Dashboard Wali')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Dashboard Wali</h1>
    <p class="mt-2 text-gray-600 dark:text-gray-400">Selamat datang, {{ Auth::user()->name }}!</p>
</div>

@if(count($stats) > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        @foreach($stats as $stat)
            <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ $stat['anak']->name }}</h3>
                
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Total Poin</span>
                        <span class="font-bold text-gray-900 dark:text-white">{{ $stat['total_poin'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Total Quiz</span>
                        <span class="font-bold text-gray-900 dark:text-white">{{ $stat['total_quiz'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Rata-rata Nilai</span>
                        <span class="font-bold text-gray-900 dark:text-white">{{ $stat['rata_rata_nilai'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Ranking</span>
                        <span class="font-bold text-gray-900 dark:text-white">
                            @if($stat['ranking'])
                                #{{ $stat['ranking'] }}
                            @else
                                -
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 p-12 text-center">
        <p class="text-gray-500 dark:text-gray-400">Belum ada data anak yang terdaftar</p>
    </div>
@endif
@endsection

