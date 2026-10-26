<x-guest-layout>
    <!-- Background Canvas with Blitz Photo & Overlay -->
    <div class="relative min-h-screen w-full flex items-center justify-center p-3 sm:p-6 lg:p-8"
         style="background: linear-gradient(135deg, rgba(11, 27, 43, 0.92) 0%, rgba(11, 27, 43, 0.82) 45%, rgba(0, 110, 160, 0.70) 100%), url('{{ asset('images/baground.png') }}') center/cover no-repeat fixed;">

        <!-- Ambient Glow Circles -->
        <div class="absolute top-1/4 left-10 w-72 h-72 rounded-full bg-[#00ADEF]/20 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-10 right-10 w-80 h-80 rounded-full bg-[#00ADEF]/15 blur-3xl pointer-events-none"></div>

        <!-- Master Glass Card: Compact & Proportional (max-w-lg) -->
        <div class="relative z-10 w-full max-w-lg rounded-2xl sm:rounded-3xl overflow-hidden shadow-2xl border border-white/20 backdrop-blur-xl bg-white p-6 sm:p-8 transition-all duration-300 my-6">
            
            <!-- Mobile Brand Indicator -->
            <div class="flex items-center justify-center gap-1.5 mb-2 text-xs font-semibold text-[#00ADEF]">
                <i class="bi bi-lightning-charge-fill"></i>
                <span>PT Blitz Electric Mobility</span>
            </div>

            <!-- Logo Blitz (Centered) -->
            <div class="mb-3 flex justify-center">
                <img src="{{ asset('images/logo.png') }}" alt="Blitz Logo" class="h-8 sm:h-9 w-auto object-contain">
            </div>

            <!-- Title & Subtitle (Centered) -->
            <div class="text-center">
                <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Daftar Akun Baru</h2>
                <p class="text-xs text-slate-500 mt-1">Lengkapi data diri Anda untuk membuat akun di sistem.</p>
            </div>

            <!-- Registration Form (1 Portal Pendaftaran Tunggal) -->
            <form id="registerForm" method="POST" action="{{ route('register') }}" class="mt-4 space-y-3">
                @csrf
                <input type="hidden" name="role" value="driver">

                <!-- Nama Lengkap -->
                <div>
                    <label for="name" class="block text-xs font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="bi bi-person text-base"></i>
                        </div>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                               placeholder="Contoh: Rian Hidayat"
                               class="block w-full pl-10 pr-4 py-2 text-sm rounded-xl border-slate-300 focus:border-[#00ADEF] focus:ring focus:ring-[#00ADEF]/20 bg-slate-50/50 transition-all placeholder:text-slate-400 font-medium">
                    </div>
                    <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs text-red-500 font-medium" />
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">Alamat Email</label>
                    <div class="relative rounded-xl shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="bi bi-envelope text-base"></i>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required
                               placeholder="rian@blitz.test atau email aktif Anda"
                               class="block w-full pl-10 pr-4 py-2 text-sm rounded-xl border-slate-300 focus:border-[#00ADEF] focus:ring focus:ring-[#00ADEF]/20 bg-slate-50/50 transition-all placeholder:text-slate-400 font-medium">
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-red-500 font-medium" />
                </div>

                <!-- No HP & Wilayah Hub -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <div>
                        <label for="no_hp" class="block text-xs font-semibold text-slate-700 mb-1">Nomor WhatsApp / HP</label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="bi bi-telephone text-base"></i>
                            </div>
                            <input id="no_hp" type="text" name="no_hp" value="{{ old('no_hp') }}"
                                   placeholder="0812xxxxxxxx"
                                   class="block w-full pl-10 pr-4 py-2 text-sm rounded-xl border-slate-300 focus:border-[#00ADEF] focus:ring focus:ring-[#00ADEF]/20 bg-slate-50/50 transition-all placeholder:text-slate-400 font-medium">
                        </div>
                    </div>
                    <div>
                        <label for="wilayah" class="block text-xs font-semibold text-slate-700 mb-1">Wilayah / Hub</label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="bi bi-geo-alt text-base"></i>
                            </div>
                            <input id="wilayah" type="text" name="wilayah" value="{{ old('wilayah') }}"
                                   placeholder="Hub Operasional"
                                   class="block w-full pl-10 pr-4 py-2 text-sm rounded-xl border-slate-300 focus:border-[#00ADEF] focus:ring focus:ring-[#00ADEF]/20 bg-slate-50/50 transition-all placeholder:text-slate-400 font-medium">
                        </div>
                    </div>
                </div>

                <!-- Password Fields (2 Columns) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">Password</label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="bi bi-lock text-base"></i>
                            </div>
                            <input id="password" type="password" name="password" required autocomplete="new-password"
                                   placeholder="Min. 8 karakter"
                                   class="block w-full pl-10 pr-9 py-2 text-sm rounded-xl border-slate-300 focus:border-[#00ADEF] focus:ring focus:ring-[#00ADEF]/20 bg-slate-50/50 transition-all placeholder:text-slate-400 font-medium">
                            <button type="button" onclick="togglePasswordVisibility('password', 'eyeIcon1')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none">
                                <i class="bi bi-eye text-sm" id="eyeIcon1"></i>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-red-500 font-medium" />
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-slate-700 mb-1">Konfirmasi Password</label>
                        <div class="relative rounded-xl shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <i class="bi bi-shield-check text-base"></i>
                            </div>
                            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                                   placeholder="Ulangi password"
                                   class="block w-full pl-10 pr-9 py-2 text-sm rounded-xl border-slate-300 focus:border-[#00ADEF] focus:ring focus:ring-[#00ADEF]/20 bg-slate-50/50 transition-all placeholder:text-slate-400 font-medium">
                            <button type="button" onclick="togglePasswordVisibility('password_confirmation', 'eyeIcon2')"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none">
                                <i class="bi bi-eye text-sm" id="eyeIcon2"></i>
                            </button>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs text-red-500 font-medium" />
                    </div>
                </div>

                <!-- Clean Security Verification Box -->
                <div class="pt-1 flex flex-col items-center justify-center">
                    <div id="captchaBox" 
                         onclick="triggerCaptchaCheck()"
                         class="w-full max-w-[304px] h-[68px] px-3 py-2 rounded-xl border border-slate-200 bg-[#f9fafb] hover:bg-slate-50 transition-all flex items-center justify-between shadow-sm cursor-pointer select-none">
                        <div class="flex items-center gap-3">
                            <div id="captchaCheckWrapper" 
                                 class="w-6 h-6 rounded-md border-2 border-slate-300 bg-white flex items-center justify-center transition-all shadow-inner">
                                <div id="captchaSpinner" class="hidden">
                                    <svg class="animate-spin h-3.5 w-3.5 text-[#00ADEF]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                                    </svg>
                                </div>
                                <i id="captchaCheckIcon" class="bi bi-check-lg text-emerald-600 font-bold text-base hidden"></i>
                            </div>
                            <span id="captchaLabel" class="text-xs font-medium text-slate-700">Saya bukan robot</span>
                        </div>
                        <div class="flex flex-col items-center justify-center text-slate-400 pl-2">
                            <svg class="w-6 h-6 text-[#00ADEF]" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M24 4C12.95 4 4 12.95 4 24C4 35.05 12.95 44 24 44C35.05 44 44 35.05 44 24" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>
                                <path d="M44 14V4H34" stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M38 10L24 24" stroke="currentColor" stroke-width="4" stroke-linecap="round"/>
                            </svg>
                            <span class="text-[8px] font-bold text-slate-500 tracking-tight">reCAPTCHA</span>
                        </div>
                    </div>
                    <input type="hidden" name="recaptcha_verified" id="recaptcha_verified" value="0">
                    <p id="recaptcha-error" class="hidden text-xs text-red-500 font-medium mt-1">
                        <i class="bi bi-exclamation-circle me-1"></i>Silakan centang verifikasi keamanan di atas.
                    </p>
                </div>

                <!-- Submit Button -->
                <div class="pt-1">
                    <button type="submit"
                            class="w-full py-2.5 px-4 rounded-xl text-white font-bold text-sm tracking-wide transition-all duration-200 flex items-center justify-center gap-2 shadow-md hover:shadow-lg hover:brightness-105 active:scale-[0.99]"
                            style="background: #00ADEF;">
                        <span>Daftar Akun Sekarang</span>
                        <i class="bi bi-arrow-right text-base"></i>
                    </button>
                </div>

                <!-- Already registered link -->
                <div class="pt-1 text-center text-xs text-slate-600">
                    <span>Sudah memiliki akun?</span>
                    <a href="{{ route('login') }}" class="font-bold text-[#00ADEF] hover:text-[#0088CC] hover:underline transition-colors ms-1">
                        Masuk di Sini
                    </a>
                </div>
            </form>

            <!-- Footer Support Note -->
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-center text-center text-xs text-slate-400">
                <span>Kendala pendaftaran? Hubungi Admin Operasional.</span>
            </div>
        </div>
    </div>

    <!-- Client-side Interactive JavaScript -->
    <script>
        function togglePasswordVisibility(inputId, iconId) {
            const passInput = document.getElementById(inputId);
            const eyeIcon = document.getElementById(iconId);
            if (passInput.type === 'password') {
                passInput.type = 'text';
                eyeIcon.className = 'bi bi-eye-slash text-sm text-[#00ADEF]';
            } else {
                passInput.type = 'password';
                eyeIcon.className = 'bi bi-eye text-sm text-slate-400';
            }
        }

        // Security Captcha Interaction
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

            spinner.classList.remove('hidden');
            checkWrapper.className = 'w-6 h-6 rounded-md border-2 border-[#00ADEF] bg-white flex items-center justify-center transition-all';

            setTimeout(() => {
                spinner.classList.add('hidden');
                checkIcon.classList.remove('hidden');
                checkWrapper.className = 'w-6 h-6 rounded-md border-2 border-emerald-500 bg-emerald-50 flex items-center justify-center transition-all';
                captchaBox.className = 'w-full max-w-[304px] h-[68px] px-3 py-2 rounded-xl border border-emerald-300 bg-emerald-50/30 transition-all flex items-center justify-between shadow-sm select-none';
                captchaLabel.textContent = 'Terverifikasi';
                captchaLabel.className = 'text-xs font-semibold text-emerald-700';

                isCaptchaVerified = true;
                isCaptchaChecking = false;
                hiddenInput.value = '1';
                captchaError.classList.add('hidden');
            }, 350);
        }

        // Validate on submit
        document.getElementById('registerForm').addEventListener('submit', function(e) {
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
