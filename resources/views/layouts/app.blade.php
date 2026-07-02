<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Web Bimbel')</title>
    
    <!-- Google Fonts: Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
</head>
<body class="bg-blue-400 font-sans antialiased text-slate-800" style="font-family: 'Poppins', sans-serif;" 
      x-data="{ 
          sidebarOpen: false, 
          sidebarDesktopOpen: true,
          loading: true 
      }" 
      x-init="setTimeout(() => loading = false, 800)">

    <!-- Global Loader -->
    <div x-show="loading" 
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] flex items-center justify-center bg-blue-400">
        <div class="text-center">
            <div class="inline-flex p-4 bg-white rounded-2xl shadow-xl animate-bounce mb-4">
                <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-white tracking-tight animate-pulse">Bimbel HIKARI</h2>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="bg-white border-b border-slate-200 fixed w-full z-50 transition-all duration-300">
        <div class="px-4 py-3 lg:px-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        <div class="p-2 bg-blue-600 rounded-xl shadow-lg shadow-blue-600/20 group-hover:scale-105 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                        </div>
                        <span class="self-center text-xl font-bold whitespace-nowrap text-slate-800 tracking-tight">Bimbel HIKARI</span>
                    </a>
                </div>
                <div class="flex items-center gap-4">
                    <!-- Notification Bell -->
                    <div class="relative" x-data="{
                        open: false,
                        unreadCount: 0,
                        notifications: [],
                        fetchUnread() {
                            if (! '{{ Auth::check() }}') return;
                            fetch('{{ route('notifications.unread-count') }}')
                                .then(res => res.json())
                                .then(data => this.unreadCount = data.count)
                                .catch(err => console.error(err));
                        },
                        fetchNotifications() {
                            if (! '{{ Auth::check() }}') return;
                            fetch('{{ route('notifications.index') }}')
                                .then(res => res.json())
                                .then(data => {
                                    this.notifications = data;
                                })
                                .catch(err => console.error(err));
                        },
                        markAsRead(id) {
                            const request = fetch(`/notifications/${id}/read`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            });
                            // Optimistically update UI
                            this.unreadCount = Math.max(0, this.unreadCount - 1);
                            this.notifications = this.notifications.map(n => {
                                if (n.id === id) n.read_at = new Date();
                                return n;
                            });
                            return request;
                        }
                    }"
                    x-init="fetchUnread(); setInterval(() => fetchUnread(), 30000)">
                        <button @click="open = !open; if(open) fetchNotifications()" class="relative p-2 text-slate-400 hover:text-blue-600 transition-colors focus:outline-none mr-2">
                            <!-- Bell Icon -->
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                            </svg>
                            <!-- Badge -->
                            <span x-show="unreadCount > 0" x-text="unreadCount" class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/4 -translate-y-1/4 bg-red-600 rounded-full border-2 border-white"></span>
                        </button>

                        <!-- Dropdown -->
                        <div x-show="open" @click.away="open = false" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 translate-y-2"
                             class="absolute right-0 top-12 z-50 w-80 mt-2 bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden" 
                             style="display: none;">
                            <div class="px-4 py-3 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                                <span class="text-sm font-semibold text-slate-800">Notifikasi</span>
                                <div class="flex gap-3">
                                    <button @click="fetch('/notifications/read-all', {method: 'POST', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}}).then(() => { unreadCount = 0; notifications.forEach(n => n.read_at = new Date()); fetchNotifications(); })" class="text-xs text-blue-600 hover:underline">Tandai Dibaca Semua</button>
                                </div>
                            </div>
                            <div class="max-h-96 overflow-y-auto">
                                <template x-for="notification in notifications" :key="notification.id">
                                    <div class="px-4 py-3 border-b border-slate-50 hover:bg-slate-50 transition-colors cursor-pointer group" 
                                         :class="notification.read_at ? 'opacity-75' : 'bg-blue-50/30'"
                                         @click="if(!notification.read_at) { markAsRead(notification.id).then(() => window.location.href = notification.data.link) } else { window.location.href = notification.data.link }">
                                        <div class="flex items-start gap-3">
                                            <div class="flex-shrink-0 mt-1">
                                                <span class="flex h-2 w-2 rounded-full" :class="notification.read_at ? 'bg-slate-300' : 'bg-blue-600'"></span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-slate-800 group-hover:text-blue-600 transition-colors" x-text="notification.data.title"></p>
                                                <p class="text-xs text-slate-500 mt-0.5 line-clamp-2" x-text="notification.data.message"></p>
                                                <p class="text-[10px] text-slate-400 mt-1.5 uppercase tracking-wide font-medium" x-text="notification.created_at"></p>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                                <div x-show="notifications.length === 0" class="px-4 py-8 text-center">
                                    <svg class="w-10 h-10 text-slate-200 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                                    <p class="text-slate-500 text-sm">Tidak ada notifikasi baru</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Dropdown -->
                    <div class="relative">
                        <button type="button" class="flex items-center gap-3 focus:outline-none" id="user-menu-button" aria-expanded="false">
                            <div class="text-right hidden md:block">
                                <div class="text-sm font-semibold text-slate-700">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-slate-500">{{ ucfirst(Auth::user()->role) }}</div>
                            </div>
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-bold shadow-md ring-2 ring-white cursor-pointer hover:shadow-lg transition-all duration-300">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                        </button>
                        <!-- Dropdown menu -->
                        <div class="absolute right-0 top-12 z-50 hidden w-56 my-4 text-base list-none bg-white divide-y divide-slate-100 rounded-xl shadow-xl border border-slate-100 transform origin-top-right transition-all duration-200" id="user-dropdown">
                            <div class="px-4 py-3 bg-slate-50 rounded-t-xl">
                                <span class="block text-sm font-semibold text-slate-800">{{ Auth::user()->name }}</span>
                                <span class="block text-sm text-slate-500 truncate">{{ Auth::user()->email }}</span>
                            </div>
                            <ul class="py-1" aria-labelledby="user-menu-button">
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center w-full px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-blue-600 transition-colors">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            </svg>
                                            Sign out
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar & Main Content -->
    <div class="flex pt-16 min-h-screen relative">
        <!-- Floating Sidebar Toggle Button -->
        <!-- Floating Sidebar Toggle Button -->
        <button @click="sidebarDesktopOpen = !sidebarDesktopOpen" 
                class="fixed top-24 z-30 p-2 rounded-r-xl transition-all duration-300 hidden lg:flex items-center justify-center focus:outline-none group"
                :class="sidebarDesktopOpen ? 'left-64 bg-transparent text-slate-300 hover:bg-white hover:shadow-md hover:text-blue-600' : 'left-0 bg-transparent text-slate-300 hover:bg-white hover:shadow-md hover:text-blue-600 hover:w-10 w-8'">
            <svg class="w-5 h-5 transition-transform duration-300" :class="!sidebarDesktopOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
            </svg>
        </button>

        <!-- Mobile Toggle Button (Floating) -->
        <button @click="sidebarOpen = !sidebarOpen" 
                class="fixed bottom-6 right-6 z-50 p-4 bg-blue-600 text-white rounded-full shadow-lg lg:hidden hover:bg-blue-700 transition-colors focus:outline-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        @if(Auth::check())
            @include('layouts.sidebar')
        @endif
        
        <!-- Main content -->
        <main class="flex-1 p-6 lg:p-8 overflow-x-hidden transition-all duration-300"
              :class="sidebarDesktopOpen ? 'lg:ml-64' : 'lg:ml-0 lg:pl-16'">
            <div class="max-w-7xl mx-auto">


                @yield('content')
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const userMenuButton = document.getElementById('user-menu-button');
            const userDropdown = document.getElementById('user-dropdown');

            if (userMenuButton && userDropdown) {
                userMenuButton.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userDropdown.classList.toggle('hidden');
                });

                document.addEventListener('click', function(e) {
                    if (!userMenuButton.contains(e.target) && !userDropdown.contains(e.target)) {
                        userDropdown.classList.add('hidden');
                    }
                });
            }
        });

        // Sweet Alert 2 Configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            },
            customClass: {
                popup: 'colored-toast'
            }
        });

        @if(session('success'))
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        @if(session('error'))
            Toast.fire({
                icon: 'error',
                title: '{{ session('error') }}'
            });
        @endif

        // Global delete confirmation
        function confirmDelete(event, message = 'Data yang dihapus tidak dapat dikembalikan!') {
            event.preventDefault();
            const form = event.target.closest('form');
            
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-xl',
                    confirmButton: 'rounded-lg px-6 py-2.5',
                    cancelButton: 'rounded-lg px-6 py-2.5'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
    @stack('scripts')
</body>
</html>

