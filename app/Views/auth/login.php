<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | ChakaNoks' SCMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
        }
        .float-animation { animation: float 4s ease-in-out infinite; }
        .float-delay { animation: float 4s ease-in-out infinite; animation-delay: 2s; }
    </style>
</head>
<body class="min-h-screen bg-slate-900 flex flex-col">
    <!-- Animated Background -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-10 left-10 w-80 h-80 bg-emerald-500/20 rounded-full blur-3xl float-animation"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-purple-500/15 rounded-full blur-3xl float-delay"></div>
        <div class="absolute top-1/2 left-1/3 w-64 h-64 bg-teal-500/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-slate-900/80 backdrop-blur-md border-b border-slate-700/50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="<?= base_url('/') ?>" class="flex items-center space-x-2 group">
                    <div class="w-9 h-9 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-lg flex items-center justify-center shadow-lg group-hover:shadow-emerald-500/25 transition-shadow">
                        <i class="fas fa-link text-white text-sm"></i>
                    </div>
                    <span class="text-white font-bold text-lg">ChakaNoks' <span class="text-emerald-400">SCMS</span></span>
                </a>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="<?= base_url('/') ?>" class="text-slate-400 hover:text-white transition-colors text-sm font-medium">Home</a>
                    <a href="<?= base_url('about') ?>" class="text-slate-400 hover:text-white transition-colors text-sm font-medium">About</a>
                    <a href="<?= base_url('contact') ?>" class="text-slate-400 hover:text-white transition-colors text-sm font-medium">Contact</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content - Centered Modal Style -->
    <div class="flex-1 flex items-center justify-center px-4 pt-20 pb-8 relative z-10">
        <div class="w-full max-w-md">
            <!-- Login Card/Modal -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-8 py-6 text-center">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-link text-white text-2xl"></i>
                    </div>
                    <h1 class="text-2xl font-bold text-white">Welcome Back</h1>
                    <p class="text-emerald-100 text-sm mt-1">Sign in to your account</p>
                </div>

                <!-- Card Body -->
                <div class="px-8 py-8">
                    <!-- Error Message -->
                    <?php if (session()->getFlashdata('msg')): ?>
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-lg mb-6 flex items-center">
                        <i class="fas fa-exclamation-circle mr-3 text-red-500"></i>
                        <span class="text-sm"><?= session()->getFlashdata('msg') ?></span>
                    </div>
                    <?php endif; ?>

                    <!-- Login Form -->
                    <form action="<?= base_url('auth/login') ?>" method="post" class="space-y-5" x-data="{ showPassword: false }">
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

            <!-- Footer Text -->
            <p class="text-center text-slate-500 text-sm mt-6">
                © <?= date('Y') ?> ChakaNoks' SCMS. All rights reserved.
            </p>
        </div>
    </div>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
