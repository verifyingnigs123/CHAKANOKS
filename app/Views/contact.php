<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact | ChakaNoks' SCMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-10px); } }
        .float-animation { animation: float 3s ease-in-out infinite; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-900" x-data="{ showLoginModal: false }">
    <!-- Background -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-72 h-72 bg-emerald-500/10 rounded-full blur-3xl float-animation"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-slate-900/80 backdrop-blur-md border-b border-slate-700/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="<?= base_url('/') ?>" class="flex items-center space-x-2">
                    <div class="w-9 h-9 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center shadow-lg">
                        <i class="fas fa-link text-white text-sm"></i>
                    </div>
                    <span class="text-white font-bold text-lg">ChakaNoks' <span class="text-emerald-400">SCMS</span></span>
                </a>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="<?= base_url('/') ?>" class="text-slate-400 hover:text-white transition-colors text-sm font-medium">Home</a>
                    <a href="<?= base_url('about') ?>" class="text-slate-400 hover:text-white transition-colors text-sm font-medium">About</a>
                    <a href="<?= base_url('contact') ?>" class="text-white font-medium text-sm">Contact</a>
                    <button @click="showLoginModal = true" class="px-5 py-2 bg-emerald-500 text-white font-medium rounded-lg hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/25">
                        Login
                    </button>
                </div>
                <button @click="showLoginModal = true" class="md:hidden px-4 py-2 bg-emerald-500 text-white font-medium rounded-lg">
                    Login
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero -->
    <section class="pt-32 pb-16 relative z-10">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Get in Touch</h1>
            <p class="text-xl text-slate-400">Start your partnership with ChakaNoks' today</p>
        </div>
    </section>

    <!-- Content -->
    <section class="py-8 relative z-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Contact Cards -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
                <div class="bg-slate-800/50 backdrop-blur border border-slate-700/50 rounded-2xl p-6 text-center hover:-translate-y-1 transition-all">
                    <div class="w-14 h-14 bg-pink-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-envelope text-pink-400 text-xl"></i>
                    </div>
                    <h4 class="text-white font-semibold mb-1">Email</h4>
                    <p class="text-slate-400 text-sm">franchise@chakanoks.com</p>
                </div>
                <div class="bg-slate-800/50 backdrop-blur border border-slate-700/50 rounded-2xl p-6 text-center hover:-translate-y-1 transition-all">
                    <div class="w-14 h-14 bg-emerald-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-phone text-emerald-400 text-xl"></i>
                    </div>
                    <h4 class="text-white font-semibold mb-1">Phone</h4>
                    <p class="text-slate-400 text-sm">+63 (82) 123-4567</p>
                </div>
                <div class="bg-slate-800/50 backdrop-blur border border-slate-700/50 rounded-2xl p-6 text-center hover:-translate-y-1 transition-all">
                    <div class="w-14 h-14 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-location-dot text-blue-400 text-xl"></i>
                    </div>
                    <h4 class="text-white font-semibold mb-1">Location</h4>
                    <p class="text-slate-400 text-sm">Davao City, PH</p>
                </div>
                <div class="bg-slate-800/50 backdrop-blur border border-slate-700/50 rounded-2xl p-6 text-center hover:-translate-y-1 transition-all">
                    <div class="w-14 h-14 bg-amber-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-clock text-amber-400 text-xl"></i>
                    </div>
                    <h4 class="text-white font-semibold mb-1">Hours</h4>
                    <p class="text-slate-400 text-sm">Mon-Fri 8am-6pm</p>
                </div>
            </div>

            <?php if (session()->getFlashdata('success')): ?>
            <!-- Success Message -->
            <div class="bg-slate-800/50 backdrop-blur border border-emerald-500/50 rounded-2xl p-10 text-center mb-10">
                <div class="w-20 h-20 bg-emerald-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check-circle text-emerald-400 text-4xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Application Submitted!</h2>
                <p class="text-slate-400 mb-6">Our team will contact you within 5-7 business days.</p>
                <a href="<?= base_url('contact') ?>" class="inline-flex items-center px-6 py-3 bg-emerald-500 text-white font-semibold rounded-xl hover:bg-emerald-600 transition-all">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Contact
                </a>
            </div>
            <?php else: ?>
            
            <!-- Application Form -->
            <div class="bg-slate-800/50 backdrop-blur border border-slate-700/50 rounded-2xl p-8 mb-10">
                <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-3">
                    <i class="fas fa-file-alt text-emerald-400"></i>Franchise Application
                </h2>
                <form method="post" action="<?= base_url('franchise-application/submit') ?>">
                    <?= csrf_field() ?>
                    <div class="grid md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2">Full Name <span class="text-red-400">*</span></label>
                            <input type="text" name="full_name" id="full_name" required placeholder="Enter your full name"
                                   pattern="^[A-Za-zÑñ\s]+$" title="Letters only (including Ñ/ñ), no numbers or special characters"
                                   class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-xl text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all">
                            <p class="text-slate-500 text-xs mt-1">Letters only (Ñ/ñ allowed)</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-slate-300 mb-2">Email <span class="text-red-400">*</span></label>
                            <input type="email" name="email" id="email" required placeholder="yourname@gmail.com"
                                   pattern="^[a-zA-Z0-9.]+@[a-zA-Z0-9]+\.[a-zA-Z]+$" title="Valid email without special characters"
                                   class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-xl text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all">
                            <p class="text-slate-500 text-xs mt-1">No special characters allowed</p>
                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-300 mb-2">Phone Number <span class="text-red-400">*</span></label>
                        <input type="tel" name="phone_number" id="phone_number" required placeholder="09XXXXXXXXX" 
                               pattern="^09[0-9]{9}$" maxlength="11" title="Must start with 09 and be exactly 11 digits"
                               class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-xl text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all">
                        <p class="text-slate-500 text-xs mt-1">Philippine mobile number (09XXXXXXXXX)</p>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-300 mb-2">Proposed Branch Address <span class="text-red-400">*</span></label>
                        <textarea name="address" rows="2" required placeholder="Enter complete address (street, barangay, city, province)"
                                  class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-xl text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all resize-none"></textarea>
                    </div>
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-slate-300 mb-2">Investment Capital <span class="text-red-400">*</span></label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-semibold">₱</span>
                            <input type="text" id="investment_display" required placeholder="e.g., 500,000"
                                   class="w-full pl-10 pr-4 py-3 bg-slate-700/50 border border-slate-600 rounded-xl text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all">
                            <input type="hidden" name="investment_capital" id="investment_capital">
                        </div>
                        <p class="text-slate-500 text-xs mt-1">Max: ₱100,000,000 (100 million)</p>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="inline-flex items-center px-8 py-4 bg-emerald-500 text-white font-semibold rounded-xl hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/30 hover:-translate-y-0.5">
                            <i class="fas fa-paper-plane mr-2"></i>Submit Application
                        </button>
                    </div>
                </form>
            </div>
            <?php endif; ?>

            <!-- FAQ -->
            <div class="bg-slate-800/50 backdrop-blur border border-slate-700/50 rounded-2xl p-8">
                <h2 class="text-2xl font-bold text-white mb-6">Frequently Asked Questions</h2>
                <div class="space-y-4">
                    <div class="bg-slate-700/30 rounded-xl p-5 border-l-4 border-emerald-500">
                        <h4 class="text-white font-semibold mb-2">How do I apply for a franchise?</h4>
                        <p class="text-slate-400 text-sm">Fill out our franchise application form above. We'll review and contact you within 5-7 business days.</p>
                    </div>
                    <div class="bg-slate-700/30 rounded-xl p-5 border-l-4 border-blue-500">
                        <h4 class="text-white font-semibold mb-2">What support does ChakaNoks provide?</h4>
                        <p class="text-slate-400 text-sm">We provide supply chain management, operations guidance, staff training, marketing support, and quality assurance standards.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-800/50 border-t border-slate-700/50 py-8 mt-12 relative z-10">
        <div class="max-w-7xl mx-auto px-4 text-center text-slate-500 text-sm">
            © <?= date('Y') ?> ChakaNoks' SCMS. All rights reserved.
        </div>
    </footer>

    <!-- Login Modal Popup -->
    <div x-show="showLoginModal" x-cloak class="fixed inset-0 z-[100] overflow-y-auto"
         x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="showLoginModal = false"></div>
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div x-show="showLoginModal"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                 class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden" @click.away="showLoginModal = false">
                <button @click="showLoginModal = false" class="absolute top-4 right-4 z-10 w-8 h-8 flex items-center justify-center text-white/80 hover:text-white bg-black/20 hover:bg-black/30 rounded-full transition-colors">
                    <i class="fas fa-times"></i>
                </button>
                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-8 py-6 text-center">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-link text-white text-2xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-white">Welcome Back</h2>
                    <p class="text-emerald-100 text-sm mt-1">Sign in to your account</p>
                </div>
                <div class="px-8 py-8" x-data="{ showPassword: false }">
                    <form action="<?= base_url('auth/login') ?>" method="post" class="space-y-5">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><i class="fas fa-envelope text-gray-400"></i></div>
                                <input type="email" name="email" required class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all" placeholder="name@company.com">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none"><i class="fas fa-lock text-gray-400"></i></div>
                                <input :type="showPassword ? 'text' : 'password'" name="password" required class="w-full pl-11 pr-12 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all" placeholder="Enter your password">
                                <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600">
                                    <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <label class="flex items-center cursor-pointer"><input type="checkbox" name="remember" class="w-4 h-4 text-emerald-500 border-gray-300 rounded"><span class="ml-2 text-sm text-gray-600">Remember me</span></label>
                            <a href="#" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">Forgot password?</a>
                        </div>
                        <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-semibold rounded-xl hover:from-emerald-600 hover:to-emerald-700 transition-all shadow-lg shadow-emerald-500/30">
                            <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                        </button>
                    </form>
                    <div class="relative my-6"><div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div><div class="relative flex justify-center text-sm"><span class="px-3 bg-white text-gray-500">New to ChakaNoks'?</span></div></div>
                    <a href="<?= base_url('contact') ?>" class="w-full flex items-center justify-center px-4 py-3 border-2 border-emerald-500 text-emerald-600 font-medium rounded-xl hover:bg-emerald-50 transition-all">
                        <i class="fas fa-handshake mr-2"></i>Apply for Franchise
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Name validation - letters and Ñ/ñ only
        const nameInput = document.getElementById('full_name');
        if (nameInput) {
            nameInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^A-Za-zÑñ\s]/g, '');
            });
        }
        
        // Phone validation - numbers only, starts with 09, max 11 digits
        const phoneInput = document.getElementById('phone_number');
        if (phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                // Remove non-digits
                let value = this.value.replace(/\D/g, '');
                // Ensure starts with 09
                if (value.length >= 2 && !value.startsWith('09')) {
                    value = '09' + value.substring(2);
                }
                // Limit to 11 digits
                this.value = value.substring(0, 11);
            });
            // Set default value if empty
            phoneInput.addEventListener('focus', function() {
                if (!this.value) {
                    this.value = '09';
                }
            });
        }
        
        // Email validation - no special characters except . and @
        const emailInput = document.getElementById('email');
        if (emailInput) {
            emailInput.addEventListener('input', function(e) {
                this.value = this.value.replace(/[^a-zA-Z0-9.@]/g, '');
            });
        }
        
        // Investment Capital - format with commas, max 100 million
        const investmentDisplay = document.getElementById('investment_display');
        const investmentHidden = document.getElementById('investment_capital');
        const MAX_INVESTMENT = 100000000; // 100 million
        
        if (investmentDisplay && investmentHidden) {
            investmentDisplay.addEventListener('input', function(e) {
                // Remove non-digits
                let value = this.value.replace(/\D/g, '');
                
                // Limit to max value
                if (parseInt(value) > MAX_INVESTMENT) {
                    value = MAX_INVESTMENT.toString();
                }
                
                // Store raw value in hidden field
                investmentHidden.value = value;
                
                // Format with commas for display
                if (value) {
                    this.value = parseInt(value).toLocaleString('en-PH');
                } else {
                    this.value = '';
                }
            });
            
            // Prevent non-numeric input
            investmentDisplay.addEventListener('keypress', function(e) {
                if (!/[0-9]/.test(e.key) && e.key !== 'Backspace' && e.key !== 'Delete') {
                    e.preventDefault();
                }
            });
        }
    });
    </script>
</body>
</html>
