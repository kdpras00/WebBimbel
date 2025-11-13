<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Bimbel HIKARI</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-blue-400">
    <div class="flex flex-col items-center justify-center px-6 py-8 mx-auto md:h-screen lg:py-0">
        <div class="w-full rounded-lg shadow md:mt-0 sm:max-w-md xl:p-0" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
            <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
                <h1 class="text-xl font-bold leading-tight tracking-tight text-black md:text-2xl text-center w-full">
                    Bimbel HIKARI
                </h1>
                <form class="space-y-4 md:space-y-6" method="POST" action="{{ route('login') }}">
                    @csrf
                    
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-black">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}" 
                               class="bg-white border border-gray-300 text-black sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" 
                               placeholder="admin@example.com" required>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-black">Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="password" 
                                   class="bg-white border border-gray-300 text-black sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 pr-10" 
                                   placeholder="password"
                                   required>
                            <button type="button" id="togglePassword" tabindex="-1" 
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-black focus:outline-none"
                                onclick="togglePasswordVisibility()" aria-label="Tampilkan/Sembunyikan Password">
                                <svg id="eyeIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path id="eyeOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M1.458 12C2.732 7.943 6.522 5 12 5s9.268 2.943 10.542 7c-1.274 4.057-5.064 7-10.542 7s-9.268-2.943-10.542-7z"/>
                                  <circle id="eyeCircle" cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" fill="none"/>
                                </svg>
                                <svg id="eyeSlashIcon" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.94 17.94A10.01 10.01 0 0112 19c-5.478 0-9.268-2.943-10.543-7a9.964 9.964 0 012.173-3.362M6.96 6.96A9.99 9.99 0 0112 5c5.478 0 9.268 2.943 10.543 7a9.984 9.984 0 01-4.173 5.225M1 1l22 22"/>
                                  <circle cx="12" cy="12" r="3" stroke="currentColor" stroke-width="2" fill="none"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="remember" name="remember" type="checkbox" 
                                       class="w-4 h-4 border border-gray-300 rounded bg-white focus:ring-3 focus:ring-primary-300">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="remember" class="text-black">Ingat saya</label>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" 
                            class="w-full text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Masuk
                    </button>
                </form>
            </div>
        </div>
    </div>
    <script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeSlashIcon = document.getElementById('eyeSlashIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.add('hidden');
            eyeSlashIcon.classList.remove('hidden');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('hidden');
            eyeSlashIcon.classList.add('hidden');
        }
    }
    </script>
</body>
</html>
