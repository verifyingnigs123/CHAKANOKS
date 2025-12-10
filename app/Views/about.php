<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About | ChakaNoks' SCMS</title>
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
                    <a href="<?= base_url('about') ?>" class="text-white font-medium text-sm">About</a>
                    <a href="<?= base_url('contact') ?>" class="text-slate-400 hover:text-white transition-colors text-sm font-medium">Contact</a>
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
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">About ChakaNoks'</h1>
            <p class="text-xl text-slate-400">Delivering quality Filipino food through innovative supply chain management</p>
        </div>
    </section>

    <!-- Content -->
    <section class="py-12 relative z-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- History -->
            <div class="bg-slate-800/50 backdrop-blur border border-slate-700/50 rounded-2xl p-8 mb-8">
                <h2 class="text-2xl font-bold text-white mb-4">Our Story</h2>
                <p class="text-slate-400 leading-relaxed">ChakaNoks began as a local food business in Davao City, committed to delivering authentic Filipino flavors to our community. Over the years, we've grown from a single location to multiple branches, building a reputation for quality, consistency, and customer satisfaction.</p>
            </div>

            <!-- Mission & Vision -->
            <div class="grid md:grid-cols-2 gap-6 mb-8">
                <div class="bg-slate-800/50 backdrop-blur border border-slate-700/50 rounded-2xl p-8">
                    <div class="w-12 h-12 bg-emerald-500/20 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-eye text-emerald-400 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Our Vision</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">To become the leading Filipino food brand nationwide, recognized for consistent quality, operational excellence, and sustainable growth through innovative supply chain management.</p>
                </div>
                <div class="bg-slate-800/50 backdrop-blur border border-slate-700/50 rounded-2xl p-8">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center mb-4">
                        <i class="fas fa-bullseye text-blue-400 text-xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Our Mission</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">To deliver exceptional food quality and customer experiences through centralized supply chain management, ensuring consistency across all branches and franchise locations.</p>
                </div>
            </div>

            <!-- CTA -->
            <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 rounded-2xl p-10 text-center">
                <h2 class="text-2xl font-bold text-white mb-3">Ready to Join ChakaNoks'?</h2>
                <p class="text-emerald-100 mb-6">Take the first step toward becoming a franchise partner.</p>
                <a href="<?= base_url('contact') ?>" class="inline-flex items-center px-6 py-3 bg-white text-emerald-600 font-semibold rounded-xl hover:bg-emerald-50 transition-all">
                    <i class="fas fa-rocket mr-2"></i>Apply for Franchise
                </a>
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
</body>
</html>
