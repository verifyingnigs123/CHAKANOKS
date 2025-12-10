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
    
    <!-- Heroicons (for icons) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
        [x-cloak] { display: none !important; }
        
        /* Hide scrollbar for sidebar */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Toast Notifications -->
    <?= $this->include('layouts/partials/toast') ?>
    
    <!-- Sidebar -->
    <?= $this->include('layouts/partials/sidebar') ?>
    
    <!-- Main Content Area -->
    <div class="ml-64 min-h-screen flex flex-col">
        
        <!-- Header -->
        <?= $this->include('layouts/partials/header') ?>
        
        <!-- Page Content -->
        <main class="flex-1 p-6 pb-20">
            <?= $this->renderSection('content') ?>
        </main>
        
        <!-- Footer -->
        <?= $this->include('layouts/partials/footer') ?>
        
    </div>

    <!-- Alpine.js for interactivity -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Chart.js (if needed) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>
