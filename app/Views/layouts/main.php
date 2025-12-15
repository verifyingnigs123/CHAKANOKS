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

    <!-- Notification Dropdown Component -->
    <script>
        function notificationDropdown() {
            return {
                open: false,
                notifications: [],
                unreadCount: 0,
                
                init() {
                    this.loadNotifications();
                    // Refresh notifications every 30 seconds
                    setInterval(() => this.loadNotifications(), 30000);
                    // Update timestamps every 10 seconds for real-time display
                    setInterval(() => {
                        // Force Alpine to re-render by triggering reactivity
                        this.notifications = [...this.notifications];
                    }, 10000);
                },
                
                toggle() {
                    this.open = !this.open;
                    if (this.open) {
                        this.loadNotifications();
                    }
                },
                
                async loadNotifications() {
                    try {
                        const response = await fetch('<?= base_url('notifications/get-unread') ?>');
                        const data = await response.json();
                        // Sort: unread first, then by created_at DESC (newest first)
                        this.notifications = (data.notifications || []).sort((a, b) => {
                            // First sort by read status (unread first)
                            if (a.is_read !== b.is_read) {
                                return a.is_read - b.is_read; // 0 (unread) comes before 1 (read)
                            }
                            // Then sort by created_at DESC (newest first)
                            return new Date(b.created_at) - new Date(a.created_at);
                        });
                        this.unreadCount = this.notifications.filter(n => n.is_read == 0).length;
                    } catch (e) {
                        console.error('Failed to load notifications:', e);
                    }
                },
                
                async handleNotificationClick(notif) {
                    // Mark as read
                    if (notif.is_read == 0) {
                        try {
                            await fetch('<?= base_url('notifications/mark-as-read/') ?>' + notif.id, { method: 'POST' });
                            notif.is_read = 1;
                            this.unreadCount = this.notifications.filter(n => n.is_read == 0).length;
                            // Re-sort notifications after marking as read
                            this.sortNotifications();
                        } catch (e) {
                            console.error('Failed to mark as read:', e);
                        }
                    }
                    
                    // Redirect to link if available
                    if (notif.link) {
                        window.location.href = notif.link;
                    }
                },
                
                sortNotifications() {
                    // Sort: unread first, then by created_at DESC (newest first)
                    this.notifications.sort((a, b) => {
                        // First sort by read status (unread first)
                        if (a.is_read !== b.is_read) {
                            return a.is_read - b.is_read; // 0 (unread) comes before 1 (read)
                        }
                        // Then sort by created_at DESC (newest first)
                        return new Date(b.created_at) - new Date(a.created_at);
                    });
                },
                
                async markAsRead(id) {
                    try {
                        await fetch('<?= base_url('notifications/mark-as-read/') ?>' + id, { method: 'POST' });
                        const notif = this.notifications.find(n => n.id == id);
                        if (notif) notif.is_read = 1;
                        this.unreadCount = this.notifications.filter(n => n.is_read == 0).length;
                        // Re-sort notifications after marking as read
                        this.sortNotifications();
                    } catch (e) {
                        console.error('Failed to mark as read:', e);
                    }
                },
                
                async markAsReadOnly(id) {
                    try {
                        await fetch('<?= base_url('notifications/mark-as-read/') ?>' + id, { method: 'POST' });
                        const notif = this.notifications.find(n => n.id == id);
                        if (notif) notif.is_read = 1;
                        this.unreadCount = this.notifications.filter(n => n.is_read == 0).length;
                        // Re-sort notifications after marking as read
                        this.sortNotifications();
                    } catch (e) {
                        console.error('Failed to mark as read:', e);
                    }
                },
                
                async markAllAsRead() {
                    try {
                        await fetch('<?= base_url('notifications/mark-all-read') ?>', { method: 'POST' });
                        this.notifications.forEach(n => n.is_read = 1);
                        this.unreadCount = 0;
                        // Re-sort notifications after marking all as read
                        this.sortNotifications();
                    } catch (e) {
                        console.error('Failed to mark all as read:', e);
                    }
                },
                
                getIcon(type) {
                    const icons = {
                        'info': 'fas fa-info-circle text-blue-500',
                        'success': 'fas fa-check-circle text-green-500',
                        'warning': 'fas fa-exclamation-triangle text-yellow-500',
                        'danger': 'fas fa-times-circle text-red-500'
                    };
                    return icons[type] || icons['info'];
                },
                
                getIconBg(type) {
                    const bgs = {
                        'info': 'bg-blue-100',
                        'success': 'bg-green-100',
                        'warning': 'bg-yellow-100',
                        'danger': 'bg-red-100'
                    };
                    return bgs[type] || bgs['info'];
                },
                
                timeAgo(dateString) {
                    if (!dateString) return 'Unknown';
                    
                    const date = new Date(dateString);
                    const now = new Date();
                    
                    // Check if date is valid
                    if (isNaN(date.getTime())) return 'Invalid date';
                    
                    const seconds = Math.floor((now - date) / 1000);
                    
                    // Handle future dates (shouldn't happen, but just in case)
                    if (seconds < 0) return 'Just now';
                    
                    // Less than 1 minute
                    if (seconds < 60) return 'Just now';
                    
                    // Less than 1 hour (show minutes)
                    if (seconds < 3600) {
                        const minutes = Math.floor(seconds / 60);
                        return minutes === 1 ? '1 minute ago' : minutes + ' minutes ago';
                    }
                    
                    // Less than 24 hours (show hours)
                    if (seconds < 86400) {
                        const hours = Math.floor(seconds / 3600);
                        return hours === 1 ? '1 hour ago' : hours + ' hours ago';
                    }
                    
                    // Less than 7 days (show days)
                    if (seconds < 604800) {
                        const days = Math.floor(seconds / 86400);
                        return days === 1 ? '1 day ago' : days + ' days ago';
                    }
                    
                    // Less than 30 days (show weeks)
                    if (seconds < 2592000) {
                        const weeks = Math.floor(seconds / 604800);
                        return weeks === 1 ? '1 week ago' : weeks + ' weeks ago';
                    }
                    
                    // Less than 365 days (show months)
                    if (seconds < 31536000) {
                        const months = Math.floor(seconds / 2592000);
                        return months === 1 ? '1 month ago' : months + ' months ago';
                    }
                    
                    // More than a year (show years)
                    const years = Math.floor(seconds / 31536000);
                    return years === 1 ? '1 year ago' : years + ' years ago';
                }
            };
        }
    </script>

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

        // Global search input validation - letters only, no numbers or special characters
        document.addEventListener('DOMContentLoaded', function() {
            // Find all search inputs by common patterns
            const searchInputs = document.querySelectorAll('input[type="text"][id*="search"], input[type="search"], input[placeholder*="Search"], input[placeholder*="search"], input[id="searchInput"]');
            
            searchInputs.forEach(function(input) {
                // Add validation on input
                input.addEventListener('input', function(e) {
                    // Allow only letters, spaces, dash, underscore, and Ñ/ñ (NO numbers)
                    const sanitized = this.value.replace(/[^a-zA-Z\s\-_ñÑ]/g, '');
                    if (this.value !== sanitized) {
                        this.value = sanitized;
                        // Show brief visual feedback
                        this.classList.add('border-red-300');
                        setTimeout(() => this.classList.remove('border-red-300'), 500);
                    }
                });
                
                // Prevent paste of numbers and special characters
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const pastedText = (e.clipboardData || window.clipboardData).getData('text');
                    const sanitized = pastedText.replace(/[^a-zA-Z\s\-_ñÑ]/g, '');
                    document.execCommand('insertText', false, sanitized);
                });
            });
        });

    </script>
    
    <?= $this->renderSection('scripts') ?>
</body>
</html>
