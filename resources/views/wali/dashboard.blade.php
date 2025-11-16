@extends('layouts.app')

@section('title', 'Dashboard Wali')

@section('content')
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-bold text-white mb-2">Dashboard Wali</h1>
            <p class="text-gray-200 text-lg">Selamat datang, <span class="font-semibold">{{ Auth::user()->name }}</span>!</p>
        </div>
    </div>
</div>

@if(count($stats) > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
        @foreach($stats as $index => $stat)
            @php
                $colors = [
                    ['from' => 'from-blue-500', 'to' => 'to-blue-600', 'bg' => 'bg-blue-100', 'text' => 'text-blue-600'],
                    ['from' => 'from-purple-500', 'to' => 'to-purple-600', 'bg' => 'bg-purple-100', 'text' => 'text-purple-600'],
                    ['from' => 'from-pink-500', 'to' => 'to-pink-600', 'bg' => 'bg-pink-100', 'text' => 'text-pink-600'],
                    ['from' => 'from-indigo-500', 'to' => 'to-indigo-600', 'bg' => 'bg-indigo-100', 'text' => 'text-indigo-600'],
                    ['from' => 'from-teal-500', 'to' => 'to-teal-600', 'bg' => 'bg-teal-100', 'text' => 'text-teal-600'],
                ];
                $color = $colors[$index % count($colors)];
            @endphp
            <div class="group relative overflow-hidden rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <!-- Gradient Background -->
                <div class="absolute inset-0 bg-gradient-to-br {{ $color['from'] }} {{ $color['to'] }} opacity-90"></div>
                
                <!-- Content -->
                <div class="relative p-6 text-gray-700">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xl font-bold text-white">{{ $stat['anak']->name }}</h3>
                        <div class="{{ $color['bg'] }} {{ $color['text'] }} p-2 rounded-lg">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between bg-white bg-opacity-20 backdrop-blur-sm rounded-lg p-3">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                                <span class="text-sm font-medium">Total Poin</span>
                            </div>
                            <span class="text-xl font-bold">{{ number_format($stat['total_poin']) }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between bg-white bg-opacity-20 backdrop-blur-sm rounded-lg p-3">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                                <span class="text-sm font-medium">Total Quiz</span>
                            </div>
                            <span class="text-xl font-bold">{{ $stat['total_quiz'] }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between bg-white bg-opacity-20 backdrop-blur-sm rounded-lg p-3">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                <span class="text-sm font-medium">Rata-rata Nilai</span>
                            </div>
                            <span class="text-xl font-bold">{{ number_format($stat['rata_rata_nilai'], 1) }}</span>
                        </div>
                        
                        <div class="flex items-center justify-between bg-white bg-opacity-20 backdrop-blur-sm rounded-lg p-3">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                                <span class="text-sm font-medium">Ranking</span>
                            </div>
                            <span class="text-xl font-bold">
                                @if($stat['ranking'])
                                    #{{ $stat['ranking'] }}
                                @else
                                    -
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="rounded-xl shadow-lg border border-gray-200 bg-white p-12 text-center">
        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
        <p class="text-gray-500 text-lg">Belum ada data anak yang terdaftar</p>
    </div>
@endif
@endsection
