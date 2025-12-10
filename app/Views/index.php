<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home | ChakaNoks' SCMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        @keyframes float { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-10px); } }
        .float-animation { animation: float 3s ease-in-out infinite; }
        .float-delay { animation: float 3s ease-in-out infinite; animation-delay: 1.5s; }
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-slate-900" x-data="{ showLoginModal: false }">
    <!-- Animated Background -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-20 left-10 w-72 h-72 bg-emerald-500/10 rounded-full blur-3xl float-animation"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-purple-500/10 rounded-full blur-3xl float-delay"></div>
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
                    <a href="<?= base_url('/') ?>" class="text-white font-medium text-sm">Home</a>
                    <a href="<?= base_url('about') ?>" class="text-slate-400 hover:text-white transition-colors text-sm font-medium">About</a>
                    <a href="<?= base_url('contact') ?>" class="text-slate-400 hover:text-white transition-colors text-sm font-medium">Contact</a>
                    <button @click="showLoginModal = true" class="px-5 py-2 bg-emerald-500 text-white font-medium rounded-lg hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/25">
                        Login
                    </button>
                </div>
                <!-- Mobile menu button -->
                <button @click="showLoginModal = true" class="md:hidden px-4 py-2 bg-emerald-500 text-white font-medium rounded-lg">
                    Login
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center pt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 text-center relative z-10">
            <div class="w-24 h-24 bg-emerald-500/20 backdrop-blur rounded-3xl flex items-center justify-center mx-auto mb-8 float-animation">
                <i class="fas fa-boxes-stacked text-emerald-400 text-4xl"></i>
            </div>
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 leading-tight">
                Welcome to <span class="text-emerald-400">ChakaNoks'</span><br>Supply Chain Management
            </h1>
            <p class="text-xl text-slate-400 mb-10 max-w-2xl mx-auto leading-relaxed">
                Streamline your food supply chain with our comprehensive management system. Experience efficiency, quality, and growth.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?= base_url('contact') ?>" class="px-8 py-4 bg-emerald-500 text-white font-semibold rounded-xl hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-500/30 hover:-translate-y-1">
                    <i class="fas fa-handshake mr-2"></i>Apply for Franchise
                </a>
                <a href="<?= base_url('about') ?>" class="px-8 py-4 bg-slate-800 text-white font-semibold rounded-xl hover:bg-slate-700 transition-all border border-slate-700">
                    <i class="fas fa-info-circle mr-2"></i>Learn More
                </a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-20 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Why Choose ChakaNoks' SCMS?</h2>
                <p class="text-slate-400 text-lg max-w-2xl mx-auto">Powerful tools designed to streamline your food supply chain operations</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-slate-800/50 backdrop-blur border border-slate-700/50 rounded-2xl p-6 hover:bg-slate-800 transition-all hover:-translate-y-2 group">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-boxes text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Centralized Inventory</h3>
                    <p class="text-slate-400 text-sm">Real-time tracking across all branches with automated alerts and barcode scanning.</p>
                </div>
                <div class="bg-slate-800/50 backdrop-blur border border-slate-700/50 rounded-2xl p-6 hover:bg-slate-800 transition-all hover:-translate-y-2 group">
                    <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-truck text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Supplier Management</h3>
                    <p class="text-slate-400 text-sm">Streamlined procurement with automated requests, approvals, and performance tracking.</p>
                </div>
                <div class="bg-slate-800/50 backdrop-blur border border-slate-700/50 rounded-2xl p-6 hover:bg-slate-800 transition-all hover:-translate-y-2 group">
                    <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-gauge-high text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Real-time Monitoring</h3>
                    <p class="text-slate-400 text-sm">Instant visibility into inventory levels with alerts for low stock or expiring items.</p>
                </div>
                <div class="bg-slate-800/50 backdrop-blur border border-slate-700/50 rounded-2xl p-6 hover:bg-slate-800 transition-all hover:-translate-y-2 group">
                    <div class="w-14 h-14 bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <i class="fas fa-chart-line text-white text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Franchise Support</h3>
                    <p class="text-slate-400 text-sm">Comprehensive support system for franchise partners including training and QA.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 relative z-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-3xl p-10 md:p-16 text-center relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="relative z-10">
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Partner With ChakaNoks'</h2>
                    <p class="text-emerald-100 text-lg mb-8 max-w-xl mx-auto">Join us in bringing quality Filipino food to communities nationwide.</p>
                    <a href="<?= base_url('contact') ?>" class="inline-flex items-center px-8 py-4 bg-white text-emerald-600 font-semibold rounded-xl hover:bg-emerald-50 transition-all shadow-lg">
                        <i class="fas fa-rocket mr-2"></i>Start Your Application
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-slate-800/50 border-t border-slate-700/50 py-12 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center">
                            <i class="fas fa-link text-white text-xs"></i>
                        </div>
                        <span class="text-white font-bold">ChakaNoks' SCMS</span>
                    </div>
                    <p class="text-slate-400 text-sm">Empowering food businesses with efficient supply chain management solutions.</p>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Quick Links</h4>
                    <div class="space-y-2">
                        <a href="<?= base_url('/') ?>" class="block text-slate-400 hover:text-white text-sm transition-colors">Home</a>
                        <a href="<?= base_url('about') ?>" class="block text-slate-400 hover:text-white text-sm transition-colors">About Us</a>
                        <a href="<?= base_url('contact') ?>" class="block text-slate-400 hover:text-white text-sm transition-colors">Contact</a>
                    </div>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Resources</h4>
                    <div class="space-y-2">
                        <a href="<?= base_url('contact') ?>" class="block text-slate-400 hover:text-white text-sm transition-colors">Franchise Application</a>
                        <button @click="showLoginModal = true" class="block text-slate-400 hover:text-white text-sm transition-colors text-left">Partner Login</button>
                    </div>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4">Contact</h4>
                    <div class="space-y-2 text-slate-400 text-sm">
                        <p><i class="fas fa-envelope mr-2"></i>info@chakanoks.com</p>
                        <p><i class="fas fa-phone mr-2"></i>+63 (82) 123-4567</p>
                        <p><i class="fas fa-location-dot mr-2"></i>Davao City, Philippines</p>
                    </div>
                </div>
            </div>
            <div class="border-t border-slate-700/50 pt-8 text-center text-slate-500 text-sm">
                © <?= date('Y') ?> ChakaNoks' SCMS. All rights reserved.
            </div>
        </div>
    </footer>

    <!-- Login Modal Popup -->
    <div x-show="showLoginModal" 
         x-cloak
         class="fixed inset-0 z-[100] overflow-y-auto"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="showLoginModal = false"></div>
        
        <!-- Modal -->
        <div class="flex items-center justify-center min-h-screen px-4 py-8">
            <div x-show="showLoginModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                 class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden"
                 @click.away="showLoginModal = false">
                
                <!-- Close Button -->
                <button @click="showLoginModal = false" 
                        class="absolute top-4 right-4 z-10 w-8 h-8 flex items-center justify-center text-white/80 hover:text-white bg-black/20 hover:bg-black/30 rounded-full transition-colors">
                    <i class="fas fa-times"></i>
                </button>
                
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-8 py-6 text-center">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-link text-white text-2xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-white">Welcome Back</h2>
                    <p class="text-emerald-100 text-sm mt-1">Sign in to your account</p>
                </div>
                
                <!-- Modal Body -->
                <div class="px-8 py-8" x-data="{ showPassword: false }">
                    <!-- Error Message -->
                    <?php if (session()->getFlashdata('msg')): ?>
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                        <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                        <span class="text-sm"><?= session()->getFlashdata('msg') ?></span>
                    </div>
                    <?php endif; ?>

                    <!-- Login Form -->
                    <form action="<?= base_url('auth/login') ?>" method="post" class="space-y-5">
                        <!-- Email Field -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400"></i>
                                </div>
                                <input type="email" name="email" required autocomplete="email"
                                       class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all text-gray-800 placeholder-gray-400"
                                       placeholder="name@company.com">
                            </div>
                        </div>

                        <!-- Password Field -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input :type="showPassword ? 'text' : 'password'" name="password" required autocomplete="current-password"
                                       class="w-full pl-11 pr-12 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all text-gray-800 placeholder-gray-400"
                                       placeholder="Enter your password">
                                <button type="button" @click="showPassword = !showPassword" 
                                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                    <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Remember & Forgot -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center cursor-pointer group">
                                <input type="checkbox" name="remember" value="1" 
                                       class="w-4 h-4 text-emerald-500 border-gray-300 rounded focus:ring-emerald-500 cursor-pointer">
                                <span class="ml-2 text-sm text-gray-600 group-hover:text-gray-800 transition-colors">Remember me</span>
                            </label>
                            <a href="#" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium hover:underline">Forgot password?</a>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full py-3.5 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-semibold rounded-xl hover:from-emerald-600 hover:to-emerald-700 transition-all shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/40 hover:-translate-y-0.5 active:translate-y-0">
                            <i class="fas fa-sign-in-alt mr-2"></i>Sign In
                        </button>
                    </form>

                    <!-- Divider -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-200"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-3 bg-white text-gray-500">New to ChakaNoks'?</span>
                        </div>
                    </div>

                    <!-- Franchise Link -->
                    <a href="<?= base_url('contact') ?>" 
                       class="w-full flex items-center justify-center px-4 py-3 border-2 border-emerald-500 text-emerald-600 font-medium rounded-xl hover:bg-emerald-50 transition-all">
                        <i class="fas fa-handshake mr-2"></i>Apply for Franchise
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Auto-open modal if there's an error -->
    <?php if (session()->getFlashdata('msg')): ?>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('loginModal', { show: true });
        });
    </script>
    <?php endif; ?>
</body>
</html>
