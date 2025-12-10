<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Franchise Application | ChakaNoks' SCMS</title>
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
        <div class="absolute top-1/2 left-1/3 w-64 h-64 bg-teal-500/10 rounded-full blur-3xl"></div>
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

    <!-- Hero Section -->
    <section class="pt-28 pb-12 relative z-10">
        <div class="max-w-4xl mx-auto px-4 text-center">
            <div class="w-20 h-20 bg-emerald-500/20 backdrop-blur rounded-2xl flex items-center justify-center mx-auto mb-6 float-animation">
                <i class="fas fa-handshake text-emerald-400 text-3xl"></i>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Franchise Application</h1>
            <p class="text-xl text-slate-400 max-w-2xl mx-auto">Join the ChakaNoks' family and bring quality Filipino food to your community</p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-8 relative z-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Benefits Section -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
                <div class="bg-slate-800/50 backdrop-blur border border-slate-700/50 rounded-xl p-5 text-center">
                    <div class="w-12 h-12 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-graduation-cap text-blue-400 text-lg"></i>
                    </div>
                    <h4 class="text-white font-semibold text-sm mb-1">Training</h4>
                    <p class="text-slate-400 text-xs">Complete support</p>
                </div>
                <div class="bg-slate-800/50 backdrop-blur border border-slate-700/50 rounded-xl p-5 text-center">
                    <div class="w-12 h-12 bg-emerald-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-truck text-emerald-400 text-lg"></i>
                    </div>
                    <h4 class="text-white font-semibold text-sm mb-1">Supply Chain</h4>
                    <p class="text-slate-400 text-xs">Centralized system</p>
                </div>
                <div class="bg-slate-800/50 backdrop-blur border border-slate-700/50 rounded-xl p-5 text-center">
                    <div class="w-12 h-12 bg-amber-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-bullhorn text-amber-400 text-lg"></i>
                    </div>
                    <h4 class="text-white font-semibold text-sm mb-1">Marketing</h4>
                    <p class="text-slate-400 text-xs">Brand support</p>
                </div>
                <div class="bg-slate-800/50 backdrop-blur border border-slate-700/50 rounded-xl p-5 text-center">
                    <div class="w-12 h-12 bg-purple-500/20 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-headset text-purple-400 text-lg"></i>
                    </div>
                    <h4 class="text-white font-semibold text-sm mb-1">Support</h4>
                    <p class="text-slate-400 text-xs">24/7 assistance</p>
                </div>
            </div>

            <?php if (session()->getFlashdata('success')): ?>
            <!-- Success Message -->
            <div class="bg-slate-800/50 backdrop-blur border border-emerald-500/50 rounded-2xl p-10 text-center">
                <div class="w-20 h-20 bg-emerald-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-check-circle text-emerald-400 text-4xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Application Submitted!</h2>
                <p class="text-slate-400 mb-6">Thank you for your interest in becoming a ChakaNoks' franchise partner. Our team will review your application and contact you within 5-7 business days.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="<?= base_url('/') ?>" class="inline-flex items-center justify-center px-6 py-3 bg-emerald-500 text-white font-semibold rounded-xl hover:bg-emerald-600 transition-all">
                        <i class="fas fa-home mr-2"></i>Back to Home
                    </a>
                    <a href="<?= base_url('contact') ?>" class="inline-flex items-center justify-center px-6 py-3 bg-slate-700 text-white font-semibold rounded-xl hover:bg-slate-600 transition-all">
                        <i class="fas fa-envelope mr-2"></i>Contact Us
                    </a>
                </div>
            </div>
            <?php else: ?>
            
            <!-- Error Messages -->
            <?php if (session()->getFlashdata('errors')): ?>
            <div class="bg-red-500/10 border border-red-500/50 rounded-xl p-4 mb-6">
                <div class="flex items-start gap-3">
                    <i class="fas fa-exclamation-circle text-red-400 mt-0.5"></i>
                    <div>
                        <p class="text-red-400 font-medium mb-2">Please fix the following errors:</p>
                        <ul class="text-red-300 text-sm space-y-1">
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li>• <?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Application Form -->
            <div class="bg-slate-800/50 backdrop-blur border border-slate-700/50 rounded-2xl overflow-hidden">
                <!-- Form Header -->
                <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 px-8 py-6">
                    <h2 class="text-2xl font-bold text-white flex items-center gap-3">
                        <i class="fas fa-file-alt"></i>Application Form
                    </h2>
                    <p class="text-emerald-100 text-sm mt-1">Fill out the form below to start your franchise journey</p>
                </div>
                
                <!-- Form Body -->
                <div class="p-8">
                    <form method="post" action="<?= base_url('franchise-application/submit') ?>" class="space-y-6">
                        <?= csrf_field() ?>
                        
                        <!-- Personal Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                                <i class="fas fa-user text-emerald-400"></i>Personal Information
                            </h3>
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2">Full Name <span class="text-red-400">*</span></label>
                                    <input type="text" name="full_name" required 
                                           value="<?= old('full_name') ?>"
                                           placeholder="Enter your full name"
                                           class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-xl text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2">Email Address <span class="text-red-400">*</span></label>
                                    <input type="email" name="email" required 
                                           value="<?= old('email') ?>"
                                           placeholder="your.email@example.com"
                                           class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-xl text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all">
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                                <i class="fas fa-phone text-emerald-400"></i>Contact Information
                            </h3>
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Phone Number <span class="text-red-400">*</span></label>
                                <input type="tel" name="phone_number" required 
                                       value="<?= old('phone_number') ?>"
                                       placeholder="+63 XXX XXX XXXX"
                                       class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-xl text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all">
                            </div>
                        </div>

                        <!-- Business Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                                <i class="fas fa-store text-emerald-400"></i>Business Information
                            </h3>
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2">Business Name (if any)</label>
                                    <input type="text" name="business_name" 
                                           value="<?= old('business_name') ?>"
                                           placeholder="Your existing business name"
                                           class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-xl text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2">Investment Capital (PHP)</label>
                                    <input type="number" name="investment_capital" 
                                           value="<?= old('investment_capital') ?>"
                                           placeholder="e.g., 500000"
                                           class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-xl text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all">
                                </div>
                            </div>
                        </div>

                        <!-- Location Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                                <i class="fas fa-location-dot text-emerald-400"></i>Proposed Location
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2">Complete Address <span class="text-red-400">*</span></label>
                                    <textarea name="address" rows="3" required 
                                              placeholder="Street, Barangay, Building/Landmark"
                                              class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-xl text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all resize-none"><?= old('address') ?></textarea>
                                </div>
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-slate-300 mb-2">City/Municipality <span class="text-red-400">*</span></label>
                                        <input type="text" name="city" required 
                                               value="<?= old('city') ?>"
                                               placeholder="e.g., Davao City"
                                               class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-xl text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-slate-300 mb-2">Province <span class="text-red-400">*</span></label>
                                        <input type="text" name="province" required 
                                               value="<?= old('province') ?>"
                                               placeholder="e.g., Davao del Sur"
                                               class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-xl text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                                <i class="fas fa-clipboard-list text-emerald-400"></i>Additional Information
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2">Business Experience</label>
                                    <textarea name="business_experience" rows="3" 
                                              placeholder="Describe your previous business experience (if any)"
                                              class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-xl text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all resize-none"><?= old('business_experience') ?></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-300 mb-2">Why do you want to franchise with ChakaNoks'?</label>
                                    <textarea name="motivation" rows="3" 
                                              placeholder="Tell us your motivation for becoming a franchise partner"
                                              class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600 rounded-xl text-white placeholder-slate-500 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 outline-none transition-all resize-none"><?= old('motivation') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Terms -->
                        <div class="bg-slate-700/30 rounded-xl p-4">
                            <label class="flex items-start gap-3 cursor-pointer">
                                <input type="checkbox" required class="w-5 h-5 mt-0.5 text-emerald-500 border-slate-500 rounded focus:ring-emerald-500 bg-slate-700">
                                <span class="text-slate-400 text-sm">I agree to the <a href="#" class="text-emerald-400 hover:underline">Terms and Conditions</a> and <a href="#" class="text-emerald-400 hover:underline">Privacy Policy</a>. I understand that submitting this application does not guarantee franchise approval.</span>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="text-center pt-4">
                            <button type="submit" class="inline-flex items-center px-10 py-4 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-semibold rounded-xl hover:from-emerald-600 hover:to-emerald-700 transition-all shadow-lg shadow-emerald-500/30 hover:-translate-y-0.5">
                                <i class="fas fa-paper-plane mr-2"></i>Submit Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <?php endif; ?>

            <!-- Contact Info -->
            <div class="mt-10 text-center">
                <p class="text-slate-400 mb-4">Have questions? Contact our franchise team</p>
                <div class="flex flex-wrap justify-center gap-6">
                    <a href="mailto:franchise@chakanoks.com" class="flex items-center gap-2 text-emerald-400 hover:text-emerald-300 transition-colors">
                        <i class="fas fa-envelope"></i>
                        <span>franchise@chakanoks.com</span>
                    </a>
                    <a href="tel:+63821234567" class="flex items-center gap-2 text-emerald-400 hover:text-emerald-300 transition-colors">
                        <i class="fas fa-phone"></i>
                        <span>+63 (82) 123-4567</span>
                    </a>
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
                </div>
            </div>
        </div>
    </div>

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
