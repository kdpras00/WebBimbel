<aside class="w-64 min-h-screen border-r border-gray-200" style="background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);">
    <div class="p-4">
        <nav class="space-y-2">
            @if(Auth::user()->isAdmin())
                <a href="{{ route('admin.dashboard') }}" class="flex items-center p-2 text-black rounded-lg hover:bg-white group">
                    <svg class="w-5 h-5 text-blue-500 transition duration-75 group-hover:text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                    </svg>
                    <span class="ml-3">Dashboard</span>
                </a>
                <a href="{{ route('admin.users.index') }}" class="flex items-center p-2 text-black rounded-lg hover:bg-white group">
                    <svg class="w-5 h-5 text-purple-500 transition duration-75 group-hover:text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                    </svg>
                    <span class="ml-3">Kelola User</span>
                </a>
                <a href="{{ route('admin.kelas.index') }}" class="flex items-center p-2 text-black rounded-lg hover:bg-white group">
                    <svg class="w-5 h-5 text-green-500 transition duration-75 group-hover:text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5 8.85V13a2 2 0 002 2h6a2 2 0 002-2V8.85l2.394-1.93a1 1 0 000-1.84l-7-3z"></path>
                    </svg>
                    <span class="ml-3">Kelola Kelas</span>
                </a>
                <a href="{{ route('admin.mapel.index') }}" class="flex items-center p-2 text-black rounded-lg hover:bg-white group">
                    <svg class="w-5 h-5 text-orange-500 transition duration-75 group-hover:text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-3">Kelola Mapel</span>
                </a>
                <a href="{{ route('admin.gamification.index') }}" class="flex items-center p-2 text-black rounded-lg hover:bg-white group">
                    <svg class="w-5 h-5 text-pink-500 transition duration-75 group-hover:text-pink-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    <span class="ml-3">Gamifikasi</span>
                </a>
            @elseif(Auth::user()->isPengajar())
                <a href="{{ route('pengajar.dashboard') }}" class="flex items-center p-2 text-black rounded-lg hover:bg-white group">
                    <svg class="w-5 h-5 text-blue-500 transition duration-75 group-hover:text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                    </svg>
                    <span class="ml-3">Dashboard</span>
                </a>
                <a href="{{ route('pengajar.materi.index') }}" class="flex items-center p-2 text-black rounded-lg hover:bg-white group">
                    <svg class="w-5 h-5 text-orange-500 transition duration-75 group-hover:text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-3">Materi</span>
                </a>
                <a href="{{ route('pengajar.quiz.index') }}" class="flex items-center p-2 text-black rounded-lg hover:bg-white group">
                    <svg class="w-5 h-5 text-purple-500 transition duration-75 group-hover:text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-3">Quiz</span>
                </a>
                <a href="{{ route('pengajar.results.index') }}" class="flex items-center p-2 text-black rounded-lg hover:bg-white group">
                    <svg class="w-5 h-5 text-green-500 transition duration-75 group-hover:text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-3">Hasil Belajar</span>
                </a>
                <a href="{{ route('pengajar.feedback.index') }}" class="flex items-center p-2 text-black rounded-lg hover:bg-white group">
                    <svg class="w-5 h-5 text-rose-500 transition duration-75 group-hover:text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-3">Feedback</span>
                </a>
            @elseif(Auth::user()->isSiswa())
                <a href="{{ route('siswa.dashboard') }}" class="flex items-center p-2 text-black rounded-lg hover:bg-white group">
                    <svg class="w-5 h-5 text-blue-500 transition duration-75 group-hover:text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                    </svg>
                    <span class="ml-3">Dashboard</span>
                </a>
                <a href="{{ route('siswa.materi.index') }}" class="flex items-center p-2 text-black rounded-lg hover:bg-white group">
                    <svg class="w-5 h-5 text-orange-500 transition duration-75 group-hover:text-orange-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-3">Materi</span>
                </a>
                <a href="{{ route('siswa.quiz.index') }}" class="flex items-center p-2 text-black rounded-lg hover:bg-white group">
                    <svg class="w-5 h-5 text-purple-500 transition duration-75 group-hover:text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-3">Quiz</span>
                </a>
                <a href="{{ route('siswa.leaderboard.index') }}" class="flex items-center p-2 text-black rounded-lg hover:bg-white group">
                    <svg class="w-5 h-5 text-yellow-500 transition duration-75 group-hover:text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                    </svg>
                    <span class="ml-3">Leaderboard</span>
                </a>
                <a href="{{ route('siswa.progress') }}" class="flex items-center p-2 text-black rounded-lg hover:bg-white group">
                    <svg class="w-5 h-5 text-green-500 transition duration-75 group-hover:text-green-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-3">Progress</span>
                </a>
            @elseif(Auth::user()->isWali())
                <a href="{{ route('wali.dashboard') }}" class="flex items-center p-2 text-black rounded-lg hover:bg-white group">
                    <svg class="w-5 h-5 text-blue-500 transition duration-75 group-hover:text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                    </svg>
                    <span class="ml-3">Dashboard</span>
                </a>
                <a href="{{ route('wali.nilai') }}" class="flex items-center p-2 text-black rounded-lg hover:bg-white group">
                    <svg class="w-5 h-5 text-teal-500 transition duration-75 group-hover:text-teal-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-3">Nilai Anak</span>
                </a>
                <a href="{{ route('wali.feedback') }}" class="flex items-center p-2 text-black rounded-lg hover:bg-white group">
                    <svg class="w-5 h-5 text-rose-500 transition duration-75 group-hover:text-rose-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="ml-3">Feedback</span>
                </a>
            @endif
        </nav>
    </div>
</aside>

