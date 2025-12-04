<!-- Mobile Sidebar Backdrop -->
<div x-show="sidebarOpen" 
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="sidebarOpen = false"
     class="fixed inset-0 z-30 bg-gray-900/50 lg:hidden"
     style="display: none;"></div>

<!-- Sidebar -->
<aside class="fixed top-0 left-0 z-40 h-screen pt-16 transition-transform bg-white border-r border-slate-200 w-64 shadow-sm"
       :class="{
           '-translate-x-full': !sidebarOpen && window.innerWidth < 1024,
           'translate-x-0': sidebarOpen || (sidebarDesktopOpen && window.innerWidth >= 1024),
           'lg:translate-x-0': sidebarDesktopOpen,
           'lg:-translate-x-full': !sidebarDesktopOpen
       }"
       aria-label="Sidebar">
    <div class="p-4 space-y-6">
        <div class="px-2">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 px-3">Menu Utama</div>
            <nav class="space-y-1">
                @if(Auth::user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl group transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.dashboard') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl group transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-purple-50 text-purple-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.users.*') ? 'text-purple-600' : 'text-slate-400 group-hover:text-slate-600' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        Kelola User
                    </a>
                    <a href="{{ route('admin.kelas.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl group transition-all duration-200 {{ request()->routeIs('admin.kelas.*') ? 'bg-green-50 text-green-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.kelas.*') ? 'text-green-600' : 'text-slate-400 group-hover:text-slate-600' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                        Kelola Kelas
                    </a>
                    <a href="{{ route('admin.mapel.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl group transition-all duration-200 {{ request()->routeIs('admin.mapel.*') ? 'bg-orange-50 text-orange-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.mapel.*') ? 'text-orange-600' : 'text-slate-400 group-hover:text-slate-600' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Kelola Mapel
                    </a>
                    <a href="{{ route('admin.gamification.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl group transition-all duration-200 {{ request()->routeIs('admin.gamification.*') ? 'bg-pink-50 text-pink-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.gamification.*') ? 'text-pink-600' : 'text-slate-400 group-hover:text-slate-600' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                        </svg>
                        Gamifikasi
                    </a>
                    <a href="{{ route('admin.informasi.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl group transition-all duration-200 {{ request()->routeIs('admin.informasi.*') ? 'bg-cyan-50 text-cyan-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.informasi.*') ? 'text-cyan-600' : 'text-slate-400 group-hover:text-slate-600' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                        </svg>
                        Kelola Informasi
                    </a>

                @elseif(Auth::user()->isPengajar())
                    <a href="{{ route('pengajar.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl group transition-all duration-200 {{ request()->routeIs('pengajar.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('pengajar.dashboard') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('pengajar.materi.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl group transition-all duration-200 {{ request()->routeIs('pengajar.materi.*') ? 'bg-orange-50 text-orange-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('pengajar.materi.*') ? 'text-orange-600' : 'text-slate-400 group-hover:text-slate-600' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Materi
                    </a>
                    <a href="{{ route('pengajar.quiz.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl group transition-all duration-200 {{ request()->routeIs('pengajar.quiz.*') ? 'bg-purple-50 text-purple-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('pengajar.quiz.*') ? 'text-purple-600' : 'text-slate-400 group-hover:text-slate-600' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Quiz
                    </a>
                    <a href="{{ route('pengajar.results.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl group transition-all duration-200 {{ request()->routeIs('pengajar.results.*') ? 'bg-green-50 text-green-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('pengajar.results.*') ? 'text-green-600' : 'text-slate-400 group-hover:text-slate-600' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Hasil Belajar
                    </a>
                    <a href="{{ route('pengajar.feedback.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl group transition-all duration-200 {{ request()->routeIs('pengajar.feedback.*') ? 'bg-rose-50 text-rose-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('pengajar.feedback.*') ? 'text-rose-600' : 'text-slate-400 group-hover:text-slate-600' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                        </svg>
                        Feedback
                    </a>

                @elseif(Auth::user()->isSiswa())
                    <a href="{{ route('siswa.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl group transition-all duration-200 {{ request()->routeIs('siswa.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('siswa.dashboard') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('siswa.materi.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl group transition-all duration-200 {{ request()->routeIs('siswa.materi.*') ? 'bg-orange-50 text-orange-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('siswa.materi.*') ? 'text-orange-600' : 'text-slate-400 group-hover:text-slate-600' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        Materi
                    </a>
                    <a href="{{ route('siswa.quiz.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl group transition-all duration-200 {{ request()->routeIs('siswa.quiz.*') ? 'bg-purple-50 text-purple-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('siswa.quiz.*') ? 'text-purple-600' : 'text-slate-400 group-hover:text-slate-600' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Quiz
                    </a>
                    <a href="{{ route('siswa.leaderboard.index') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl group transition-all duration-200 {{ request()->routeIs('siswa.leaderboard.*') ? 'bg-yellow-50 text-yellow-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('siswa.leaderboard.*') ? 'text-yellow-600' : 'text-slate-400 group-hover:text-slate-600' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Leaderboard
                    </a>
                    <a href="{{ route('siswa.progress') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl group transition-all duration-200 {{ request()->routeIs('siswa.progress') ? 'bg-green-50 text-green-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('siswa.progress') ? 'text-green-600' : 'text-slate-400 group-hover:text-slate-600' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        Progress
                    </a>

                @elseif(Auth::user()->isWali())
                    <a href="{{ route('wali.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl group transition-all duration-200 {{ request()->routeIs('wali.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('wali.dashboard') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('wali.nilai') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl group transition-all duration-200 {{ request()->routeIs('wali.nilai') ? 'bg-teal-50 text-teal-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('wali.nilai') ? 'text-teal-600' : 'text-slate-400 group-hover:text-slate-600' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Nilai Anak
                    </a>
                    <a href="{{ route('wali.progress') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl group transition-all duration-200 {{ request()->routeIs('wali.progress') ? 'bg-green-50 text-green-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('wali.progress') ? 'text-green-600' : 'text-slate-400 group-hover:text-slate-600' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                        Progress
                    </a>

                @elseif(Auth::user()->isPemilik())
                    <a href="{{ route('owner.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm font-medium rounded-xl group transition-all duration-200 {{ request()->routeIs('owner.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        <svg class="w-5 h-5 mr-3 {{ request()->routeIs('owner.dashboard') ? 'text-blue-600' : 'text-slate-400 group-hover:text-slate-600' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                        </svg>
                        Dashboard
                    </a>
                @endif
            </nav>
        </div>
    </div>
</aside>
