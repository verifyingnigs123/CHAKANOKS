<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? $page_title ?? "ChakaNoks' SCMS" ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        secondary: '#64748b',
                        sidebar: '#1e293b',
                        'sidebar-hover': '#334155'
                    }
                }
            }
        }
    </script>
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
        
        /* Hide scrollbars globally but allow scrolling */
        html, body, * {
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE and Edge */
        }
        
        *::-webkit-scrollbar {
            display: none; /* Chrome, Safari, Opera */
            width: 0;
            height: 0;
        }
        
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Mobile sidebar overlay */
        .sidebar-overlay {
            background-color: rgba(0, 0, 0, 0.5);
            transition: opacity 0.3s ease;
        }
        
        /* Sidebar transition */
        .sidebar-transition {
            transition: transform 0.3s ease;
        }
        
        /* Smooth fade in animation for page load - no loading spinner */
        @keyframes smoothFadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .animate-fade-in {
            animation: smoothFadeIn 0.2s ease-out forwards;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Toast Notifications -->
    <?= $this->include('layouts/partials/toast') ?>
    
    <!-- Mobile Sidebar Overlay -->
    <div id="sidebarOverlay" class="fixed inset-0 z-40 sidebar-overlay hidden lg:hidden" onclick="toggleSidebar()"></div>
    
    <!-- Sidebar -->
    <?= $this->include('layouts/partials/sidebar') ?>
    
    <!-- Main Content Area -->
    <div class="lg:ml-64 min-h-screen flex flex-col">
        
        <!-- Header -->
        <?= $this->include('layouts/partials/header') ?>
        
        <!-- Page Content -->
        <main id="mainContent" class="flex-1 p-4 md:p-6 pb-20 overflow-x-auto animate-fade-in">
            <?= $this->renderSection('content') ?>
        </main>
        
        <!-- Footer -->
        <?= $this->include('layouts/partials/footer') ?>
        
    </div>

    <!-- Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Chart.js (if needed) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Sidebar Toggle Script -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                // Open sidebar
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                // Close sidebar
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }
        
        // Close sidebar on window resize to desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('sidebarOverlay');
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.add('hidden');
                document.body.style.overflow = '';
            }
        });
        

    </script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>
