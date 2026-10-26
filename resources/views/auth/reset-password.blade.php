<x-guest-layout>
    <!-- Background Canvas with Blitz Photo & Overlay -->
    <div class="relative min-h-screen w-full flex items-center justify-center p-4 sm:p-6 lg:p-8"
         style="background: linear-gradient(135deg, rgba(11, 27, 43, 0.92) 0%, rgba(11, 27, 43, 0.82) 45%, rgba(0, 110, 160, 0.70) 100%), url('{{ asset('images/baground.png') }}') center/cover no-repeat fixed;">

        <!-- Ambient Glow Circles -->
        <div class="absolute top-1/4 left-10 w-72 h-72 rounded-full bg-[#00ADEF]/20 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-10 right-10 w-80 h-80 rounded-full bg-[#00ADEF]/15 blur-3xl pointer-events-none"></div>

        <!-- Master Glass Card -->
        <div class="relative z-10 w-full max-w-lg rounded-3xl overflow-hidden shadow-2xl border border-white/20 backdrop-blur-xl bg-white p-6 sm:p-10 transition-all duration-300">
            
            <!-- Logo Blitz (Centered) -->
            <div class="mb-5 flex justify-center">
                <img src="{{ asset('images/logo.png') }}" alt="Blitz Logo" class="h-9 sm:h-10 w-auto object-contain">
            </div>

            <!-- Title & Subtitle (Centered) -->
            <div class="text-center">
                <h2 class="text-2xl font-bold text-slate-900 tracking-tight">Atur Ulang Password</h2>
                <p class="text-xs text-slate-500 mt-2 leading-relaxed">
                    Silakan tentukan password baru yang aman untuk akun Anda.
                </p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="mt-6 space-y-4">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Alamat Email
                    </label>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="bi bi-envelope text-base"></i>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus
                               class="block w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border-slate-300 focus:border-[#00ADEF] focus:ring focus:ring-[#00ADEF]/20 bg-slate-50/50 transition-all placeholder:text-slate-400 font-medium">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-red-500 font-medium" />
                </div>

                <!-- New Password -->
                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Password Baru
                    </label>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="bi bi-lock text-base"></i>
                        </div>
                        <input id="password" type="password" name="password" required autocomplete="new-password"
                               placeholder="Minimal 8 karakter"
                               class="block w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border-slate-300 focus:border-[#00ADEF] focus:ring focus:ring-[#00ADEF]/20 bg-slate-50/50 transition-all placeholder:text-slate-400 font-medium">
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-red-500 font-medium" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Konfirmasi Password Baru
                    </label>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="bi bi-shield-check text-base"></i>
                        </div>
                        <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                               placeholder="Ulangi password baru"
                               class="block w-full pl-10 pr-4 py-2.5 text-sm rounded-xl border-slate-300 focus:border-[#00ADEF] focus:ring focus:ring-[#00ADEF]/20 bg-slate-50/50 transition-all placeholder:text-slate-400 font-medium">
                    </div>
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1.5 text-xs text-red-500 font-medium" />
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit"
                            class="w-full py-2.5 px-4 rounded-xl text-white font-bold text-sm tracking-wide transition-all duration-200 flex items-center justify-center gap-2 shadow-md hover:shadow-lg hover:brightness-105 active:scale-[0.99]"
                            style="background: #00ADEF;">
                        <span>Simpan Password Baru</span>
                        <i class="bi bi-check2 text-base"></i>
                    </button>
                </div>
            </form>

            <!-- Back to Login Link -->
            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-[#00ADEF] transition-colors">
                    <i class="bi bi-arrow-left"></i>
                    <span>Kembali ke Halaman Login</span>
                </a>
            </div>

            <!-- Footer Support Note (Centered) -->
            <div class="mt-8 pt-4 border-t border-slate-100 flex items-center justify-center text-center text-xs text-slate-400">
                <span>Kendala akses akun? Hubungi Admin Operasional.</span>
            </div>
        </div>
    </div>
</x-guest-layout>
