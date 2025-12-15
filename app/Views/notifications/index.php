<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php 
$page_title = 'Notifications'; 

// PHP timeAgo function for initial render
function timeAgo($dateString) {
    if (empty($dateString)) return 'Unknown';
    
    $date = strtotime($dateString);
    $now = time();
    
    if ($date === false) return 'Invalid date';
    
    $seconds = $now - $date;
    
    if ($seconds < 0) return 'Just now';
    if ($seconds < 60) return 'Just now';
    
    if ($seconds < 3600) {
        $minutes = floor($seconds / 60);
        return $minutes === 1 ? '1 minute ago' : $minutes . ' minutes ago';
    }
    
    if ($seconds < 86400) {
        $hours = floor($seconds / 3600);
        return $hours === 1 ? '1 hour ago' : $hours . ' hours ago';
    }
    
    if ($seconds < 604800) {
        $days = floor($seconds / 86400);
        return $days === 1 ? '1 day ago' : $days . ' days ago';
    }
    
    if ($seconds < 2592000) {
        $weeks = floor($seconds / 604800);
        return $weeks === 1 ? '1 week ago' : $weeks . ' weeks ago';
    }
    
    if ($seconds < 31536000) {
        $months = floor($seconds / 2592000);
        return $months === 1 ? '1 month ago' : $months . ' months ago';
    }
    
    $years = floor($seconds / 31536000);
    return $years === 1 ? '1 year ago' : $years . ' years ago';
}
?>

<div class="bg-white rounded-xl shadow-sm border border-gray-200">
    <div class="p-4 border-b border-gray-100 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-gray-800">All Notifications</h2>
        <?php if (!empty($notifications) && count(array_filter($notifications, fn($n) => $n['is_read'] == 0)) > 0): ?>
        <button onclick="markAllAsRead()" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
            <i class="fas fa-check-double mr-1"></i> Mark all as read
        </button>
        <?php endif; ?>
    </div>
    
    <div class="p-4 space-y-3">
        <?php if (empty($notifications)): ?>
        <div class="p-12 text-center">
            <i class="fas fa-bell-slash text-4xl text-gray-300 mb-3"></i>
            <p class="text-gray-500">No notifications yet</p>
        </div>
        <?php else: ?>
            <?php foreach ($notifications as $notif): 
                $isUnread = $notif['is_read'] == 0;
                $borderColor = $isUnread ? 'border-blue-500' : 'border-gray-200';
                $bgColor = $isUnread ? 'bg-blue-50' : 'bg-gray-50';
            ?>
            <div class="border-l-4 <?= $borderColor ?> <?= $bgColor ?> rounded-lg p-4 transition-all" id="notif-<?= $notif['id'] ?>">
                <a href="<?= $notif['link'] ?? '#' ?>" onclick="markAsRead(<?= $notif['id'] ?>)" class="block">
                    <p class="text-sm text-gray-800 <?= $isUnread ? 'font-semibold' : 'font-normal' ?>">
                        <?= esc($notif['title']) ?>
                    </p>
                    <p class="text-sm text-gray-600 mt-1"><?= esc($notif['message']) ?></p>
                </a>
                <div class="flex items-center justify-between mt-3">
                    <span class="text-xs text-gray-400 notification-time" data-time="<?= $notif['created_at'] ?>">
                        <?= timeAgo($notif['created_at']) ?>
                    </span>
                    <?php if ($isUnread): ?>
                    <button onclick="markAsReadOnly(<?= $notif['id'] ?>)" 
                            class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-emerald-600 border border-emerald-500 rounded-lg hover:bg-emerald-50 transition-colors">
                        <i class="fas fa-check mr-1"></i> Mark Read
                    </button>
                    <?php else: ?>
                    <span class="text-xs text-gray-400">
                        <i class="fas fa-check-double text-emerald-500 mr-1"></i> Read
                    </span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Time ago function (same as in main.php)
function timeAgo(dateString) {
    if (!dateString) return 'Unknown';
    
    const date = new Date(dateString);
    const now = new Date();
    
    if (isNaN(date.getTime())) return 'Invalid date';
    
    const seconds = Math.floor((now - date) / 1000);
    
    if (seconds < 0) return 'Just now';
    if (seconds < 60) return 'Just now';
    
    if (seconds < 3600) {
        const minutes = Math.floor(seconds / 60);
        return minutes === 1 ? '1 minute ago' : minutes + ' minutes ago';
    }
    
    if (seconds < 86400) {
        const hours = Math.floor(seconds / 3600);
        return hours === 1 ? '1 hour ago' : hours + ' hours ago';
    }
    
    if (seconds < 604800) {
        const days = Math.floor(seconds / 86400);
        return days === 1 ? '1 day ago' : days + ' days ago';
    }
    
    if (seconds < 2592000) {
        const weeks = Math.floor(seconds / 604800);
        return weeks === 1 ? '1 week ago' : weeks + ' weeks ago';
    }
    
    if (seconds < 31536000) {
        const months = Math.floor(seconds / 2592000);
        return months === 1 ? '1 month ago' : months + ' months ago';
    }
    
    const years = Math.floor(seconds / 31536000);
    return years === 1 ? '1 year ago' : years + ' years ago';
}

// Update all timestamps every 10 seconds
function updateTimestamps() {
    document.querySelectorAll('.notification-time').forEach(el => {
        const dateTime = el.getAttribute('data-time');
        if (dateTime) {
            el.textContent = timeAgo(dateTime);
        }
    });
}

// Initial update and set interval
updateTimestamps();
setInterval(updateTimestamps, 10000); // Update every 10 seconds

async function markAsRead(id) {
    try {
        await fetch('<?= base_url('notifications/mark-as-read/') ?>' + id, { method: 'POST' });
    } catch (e) {
        console.error('Failed to mark as read:', e);
    }
}

async function markAsReadOnly(id) {
    try {
        await fetch('<?= base_url('notifications/mark-as-read/') ?>' + id, { method: 'POST' });
        // Update UI without reload
        const notifEl = document.getElementById('notif-' + id);
        if (notifEl) {
            notifEl.classList.remove('border-blue-500', 'bg-blue-50');
            notifEl.classList.add('border-gray-200', 'bg-gray-50');
            // Update title font
            const title = notifEl.querySelector('p.text-gray-800');
            if (title) {
                title.classList.remove('font-semibold');
                title.classList.add('font-normal');
            }
            // Replace button with "Read" text
            const btnContainer = notifEl.querySelector('.flex.items-center.justify-between.mt-3');
            if (btnContainer) {
                const btn = btnContainer.querySelector('button');
                if (btn) {
                    btn.outerHTML = '<span class="text-xs text-gray-400"><i class="fas fa-check-double text-emerald-500 mr-1"></i> Read</span>';
                }
            }
        }
    } catch (e) {
        console.error('Failed to mark as read:', e);
    }
}

async function markAllAsRead() {
    try {
        await fetch('<?= base_url('notifications/mark-all-read') ?>', { method: 'POST' });
        window.location.reload();
    } catch (e) {
        console.error('Failed to mark all as read:', e);
    }
}
</script>
<?= $this->endSection() ?>
