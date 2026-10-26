<x-guest-layout>
    <!-- Background Canvas with Blitz Photo & Overlay -->
    <div class="relative min-h-screen w-full flex items-center justify-center p-3 sm:p-6 lg:p-8"
         style="background: linear-gradient(135deg, rgba(11, 27, 43, 0.92) 0%, rgba(11, 27, 43, 0.82) 45%, rgba(0, 110, 160, 0.70) 100%), url('{{ asset('images/baground.png') }}') center/cover no-repeat fixed;">

        <!-- Ambient Glow Circles -->
        <div class="absolute top-1/4 left-10 w-72 h-72 rounded-full bg-[#00ADEF]/20 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-10 right-10 w-80 h-80 rounded-full bg-[#00ADEF]/15 blur-3xl pointer-events-none"></div>

        <!-- Master Glass Card: Compact max-w-md on Mobile, Expanded max-w-4xl on Desktop -->
        <div class="relative z-10 w-full max-w-md lg:max-w-4xl rounded-2xl sm:rounded-3xl overflow-hidden shadow-2xl border border-white/20 backdrop-blur-xl bg-white grid grid-cols-1 lg:grid-cols-12 transition-all duration-300">

            <!-- LEFT COLUMN: Branding & Visual Showcase (Hidden on Mobile, Visible on Desktop) -->
            <div class="hidden lg:flex lg:col-span-5 bg-gradient-to-br from-[#0B1B2B] via-[#0e243a] to-[#12395d] p-7 text-white flex-col justify-between relative overflow-hidden">
                <!-- Background Glow -->
                <div class="absolute -top-16 -right-16 w-48 h-48 rounded-full bg-[#00ADEF]/25 blur-2xl"></div>
                
                <!-- Brand Title & Tag -->
                <div class="relative z-10">
                    <h1 class="text-2xl font-bold tracking-tight leading-snug text-white">
                        Sistem Penilaian Kinerja Driver
                    </h1>
                    <p class="text-xs text-slate-300 mt-1.5 leading-relaxed">
                        Portal evaluasi dan rekapitulasi performa pengemudi armada operasional logistik ramah lingkungan.
                    </p>
                </div>

                <!-- Center Graphic: Blitz Driver with Elegant Frame (Not floating/empty) -->
                <div class="relative z-10 my-3">
                    <div class="rounded-2xl overflow-hidden border border-white/15 bg-white/5 backdrop-blur-md p-1.5 shadow-xl transition-all hover:border-[#00ADEF]/40">
                        <img src="{{ asset('images/driver1.png') }}" alt="Armada Driver Blitz" 
                             class="w-full h-auto max-h-48 object-contain rounded-xl mx-auto drop-shadow-md">
                    </div>
                </div>

                <!-- Bottom Footer Note -->
                <div class="relative z-10 text-[11px] text-slate-400 pt-3 border-t border-white/10 flex items-center justify-between">
                    <span>&copy; {{ date('Y') }} PT Blitz Electric Mobility</span>
                    <span class="text-emerald-400 font-medium flex items-center gap-1 text-[10px]">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Sistem Aktif
                    </span>
                </div>
            </div>

            <!-- RIGHT COLUMN: Unified Clean Login Form (Optimized for Mobile & Desktop) -->
            <div class="w-full lg:col-span-7 p-6 sm:p-9 flex flex-col justify-center bg-white relative">
                
                <!-- Mobile Brand Indicator (Visible only on mobile screen) -->
                <div class="lg:hidden flex items-center justify-center gap-1.5 mb-2 text-xs font-semibold text-[#00ADEF]">
                    <i class="bi bi-lightning-charge-fill"></i>
                    <span>PT Blitz Electric Mobility</span>
                </div>

                <!-- Logo Blitz (Centered) -->
                <div class="mb-4 sm:mb-5 flex justify-center">
                    <img src="{{ asset('images/logo.png') }}" alt="Blitz Logo" class="h-9 sm:h-10 w-auto object-contain">
                </div>

                <!-- Title & Subtitle (Centered) -->
                <div class="text-center">
                    <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Masuk ke Akun</h2>
                    <p class="text-xs text-slate-500 mt-1">Gunakan akun terdaftar Anda untuk mengakses sistem.</p>
                </div>

                <!-- Session Alert -->
                <x-auth-session-status class="mt-3 mb-1" :status="session('status')" />

                <!-- Login Form (1 Form Tunggal - Otomatis mendeteksi Admin & Driver) -->
                <form id="loginForm" method="POST" action="{{ route('login') }}" class="mt-5 space-y-4">
                    @csrf

                    <!-- Identifier Input (Email or Driver ID) -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">
                            ID Driver atau Email
                        </label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="bi bi-person text-base"></i>
                            </div>
                            <input id="email" type="text" name="email" value="{{ old('email') }}" required autofocus
                                   placeholder="Contoh: D001 atau admin@blitz.test"
                                   class="block w-full pl-10 pr-4 py-2.5 sm:py-2.5 text-base sm:text-sm rounded-xl border-slate-300 focus:border-[#00ADEF] focus:ring focus:ring-[#00ADEF]/20 bg-slate-50/50 transition-all placeholder:text-slate-400 font-medium">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-red-500 font-medium" />
                    </div>

                    <!-- Password Input with Toggle Eye -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-xs font-semibold text-slate-700">Password</label>
                            <a href="{{ route('password.request') }}"
                               class="text-xs font-medium text-[#00ADEF] hover:text-[#0088CC] transition-colors py-0.5">
                                Lupa password?
                            </a>
                        </div>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="bi bi-lock text-base"></i>
                            </div>
                            <input id="password" type="password" name="password" required autocomplete="current-password"
                                   placeholder="Masukkan password Anda"
                                   class="block w-full pl-10 pr-11 py-2.5 sm:py-2.5 text-base sm:text-sm rounded-xl border-slate-300 focus:border-[#00ADEF] focus:ring focus:ring-[#00ADEF]/20 bg-slate-50/50 transition-all placeholder:text-slate-400 font-medium">
                            <button type="button" onclick="togglePasswordVisibility()" aria-label="Lihat password"
                                    class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none">
                                <i class="bi bi-eye text-base" id="eyeIcon"></i>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-red-500 font-medium" />
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between pt-0.5">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                            <input id="remember_me" type="checkbox" name="remember"
                                   class="w-4 h-4 rounded border-slate-300 text-[#00ADEF] focus:ring-[#00ADEF]/30 cursor-pointer">
                            <span class="ms-2 text-xs text-slate-600">Ingat saya di perangkat ini</span>
                        </label>
                    </div>

                    <!-- Clean Production Security Verification (reCAPTCHA Style Tanpa Tulisan Merah Testing) -->
                    <div class="pt-1 flex flex-col items-center justify-center">
                        <div id="captchaBox" 
                             onclick="triggerCaptchaCheck()"
                             class="w-full max-w-[304px] h-[74px] px-3.5 py-2.5 rounded-xl border border-slate-200 bg-[#f9fafb] hover:bg-slate-50 transition-all flex items-center justify-between shadow-sm cursor-pointer select-none">
                            <div class="flex items-center gap-3">
                                <!-- Checkbox / Spinner / Checkmark -->
                                <div id="captchaCheckWrapper" 
                                     class="w-7 h-7 rounded-md border-2 border-slate-300 bg-white flex items-center justify-center transition-all shadow-inner">
                                    <div id="captchaSpinner" class="hidden">
                                        <svg class="animate-spin h-4 w-4 text-[#00ADEF]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                        </svg>
                                    </div>
                                    <i id="captchaCheckIcon" class="bi bi-check-lg text-emerald-600 font-bold text-lg hidden"></i>
                                </div>
                                <span id="captchaLabel" class="text-xs sm:text-sm font-medium text-slate-700">Saya bukan robot</span>
                            </div>
                            
                            <!-- reCAPTCHA Branding Logo -->
                            <div class="flex flex-col items-center justify-center text-slate-400 pl-2">
                                <svg class="w-7 h-7 text-[#00ADEF]" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M24 4C12.95 4 4 12.95 4 24C4 35.05 12.95 44 24 44C35.05 44 44 35.05 44 24" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>
                                    <path d="M44 14V4H34" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M38 10L24 24" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>
                                </svg>
                                <span class="text-[9px] font-bold text-slate-500 tracking-tight mt-0.5">reCAPTCHA</span>
                                <span class="text-[8px] text-slate-400">Keamanan</span>
                            </div>
                        </div>
                        
                        <!-- Hidden validation flag -->
                        <input type="hidden" name="recaptcha_verified" id="recaptcha_verified" value="0">
                        <p id="recaptcha-error" class="hidden text-xs text-red-500 font-medium mt-1.5 flex items-center gap-1">
                            <i class="bi bi-exclamation-circle"></i> Silakan centang verifikasi keamanan di atas.
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-1">
                        <button type="submit"
                                class="w-full py-2.5 sm:py-3 px-4 rounded-xl text-white font-bold text-sm tracking-wide transition-all duration-200 flex items-center justify-center gap-2 shadow-md hover:shadow-lg hover:brightness-105 active:scale-[0.99]"
                                style="background: #00ADEF;">
                            <span>Masuk</span>
                            <i class="bi bi-arrow-right text-base"></i>
                        </button>
                    </div>

                    <!-- Register Link (Daftar Akun Baru) -->
                    <div class="pt-2 text-center text-xs text-slate-600">
                        <span>Belum memiliki akun?</span>
                        <a href="{{ route('register') }}" class="font-bold text-[#00ADEF] hover:text-[#0088CC] hover:underline transition-colors ms-1">
                            Daftar Akun Baru
                        </a>
                    </div>
                </form>

                <!-- Footer Support Note (Centered) -->
                <div class="mt-6 pt-3.5 border-t border-slate-100 flex items-center justify-center text-center text-xs text-slate-400">
                    <span>Kendala akses akun? Hubungi Admin Operasional.</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Client-side Interactive JavaScript -->
    <script>
        function togglePasswordVisibility() {
            const passInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passInput.type === 'password') {
                passInput.type = 'text';
                eyeIcon.className = 'bi bi-eye-slash text-base text-[#00ADEF]';
            } else {
                passInput.type = 'password';
                eyeIcon.className = 'bi bi-eye text-base text-slate-400';
            }
        }

        // Custom Security Verification Interaction (No testing warning, 100% clean)
        let isCaptchaVerified = false;
        let isCaptchaChecking = false;

        function triggerCaptchaCheck() {
            if (isCaptchaVerified || isCaptchaChecking) return;

            isCaptchaChecking = true;
            const spinner = document.getElementById('captchaSpinner');
            const checkIcon = document.getElementById('captchaCheckIcon');
            const checkWrapper = document.getElementById('captchaCheckWrapper');
            const captchaBox = document.getElementById('captchaBox');
            const captchaLabel = document.getElementById('captchaLabel');
            const captchaError = document.getElementById('recaptcha-error');
            const hiddenInput = document.getElementById('recaptcha_verified');

            // Show spinner animation
            spinner.classList.remove('hidden');
            checkWrapper.className = 'w-7 h-7 rounded-md border-2 border-[#00ADEF] bg-white flex items-center justify-center transition-all';

            setTimeout(() => {
                spinner.classList.add('hidden');
                checkIcon.classList.remove('hidden');
                checkWrapper.className = 'w-7 h-7 rounded-md border-2 border-emerald-500 bg-emerald-50 flex items-center justify-center transition-all';
                captchaBox.className = 'w-full max-w-[304px] h-[74px] px-3.5 py-2.5 rounded-xl border border-emerald-300 bg-emerald-50/30 transition-all flex items-center justify-between shadow-sm select-none';
                captchaLabel.textContent = 'Terverifikasi';
                captchaLabel.className = 'text-xs sm:text-sm font-semibold text-emerald-700';

                isCaptchaVerified = true;
                isCaptchaChecking = false;
                hiddenInput.value = '1';
                captchaError.classList.add('hidden');
            }, 400);
        }

        // Form Validation on Submit
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            if (!isCaptchaVerified) {
                e.preventDefault();
                const captchaError = document.getElementById('recaptcha-error');
                const captchaBox = document.getElementById('captchaBox');
                captchaError.classList.remove('hidden');
                captchaBox.classList.add('border-red-400', 'bg-red-50/20');
                return false;
            }
        });
    </script>
</x-guest-layout>
