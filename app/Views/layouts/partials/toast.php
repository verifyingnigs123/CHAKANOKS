<?php
$success = session()->getFlashdata('success');
$error = session()->getFlashdata('error');
$warning = session()->getFlashdata('warning');
$info = session()->getFlashdata('info');
$message = session()->getFlashdata('message') ?? session()->getFlashdata('msg');
?>

<!-- Toast Container -->
<div id="toast-container" class="fixed top-4 right-4 z-[9999] flex flex-col gap-3 max-w-sm w-full pointer-events-none">
    
    <?php if ($success): ?>
    <div class="toast-item pointer-events-auto bg-white border-l-4 border-emerald-500 rounded-lg shadow-lg p-4 flex items-start gap-3 animate-slide-in" data-toast>
        <div class="flex-shrink-0 w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center">
            <i class="fas fa-check text-emerald-600"></i>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-gray-800">Success</p>
            <p class="text-sm text-gray-600 mt-0.5"><?= esc($success) ?></p>
        </div>
        <button onclick="closeToast(this)" class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <?php endif; ?>

    <?php if ($error): ?>
    <div class="toast-item pointer-events-auto bg-white border-l-4 border-red-500 rounded-lg shadow-lg p-4 flex items-start gap-3 animate-slide-in" data-toast>
        <div class="flex-shrink-0 w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
            <i class="fas fa-times text-red-600"></i>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-gray-800">Error</p>
            <p class="text-sm text-gray-600 mt-0.5"><?= esc($error) ?></p>
        </div>
        <button onclick="closeToast(this)" class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <?php endif; ?>

    <?php if ($warning): ?>
    <div class="toast-item pointer-events-auto bg-white border-l-4 border-amber-500 rounded-lg shadow-lg p-4 flex items-start gap-3 animate-slide-in" data-toast>
        <div class="flex-shrink-0 w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center">
            <i class="fas fa-exclamation-triangle text-amber-600"></i>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-gray-800">Warning</p>
            <p class="text-sm text-gray-600 mt-0.5"><?= esc($warning) ?></p>
        </div>
        <button onclick="closeToast(this)" class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <?php endif; ?>

    <?php if ($info): ?>
    <div class="toast-item pointer-events-auto bg-white border-l-4 border-blue-500 rounded-lg shadow-lg p-4 flex items-start gap-3 animate-slide-in" data-toast>
        <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
            <i class="fas fa-info text-blue-600"></i>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-gray-800">Info</p>
            <p class="text-sm text-gray-600 mt-0.5"><?= esc($info) ?></p>
        </div>
        <button onclick="closeToast(this)" class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <?php endif; ?>

    <?php if ($message): ?>
    <div class="toast-item pointer-events-auto bg-white border-l-4 border-gray-500 rounded-lg shadow-lg p-4 flex items-start gap-3 animate-slide-in" data-toast>
        <div class="flex-shrink-0 w-8 h-8 bg-gray-100 rounded-full flex items-center justify-center">
            <i class="fas fa-bell text-gray-600"></i>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-gray-800">Notice</p>
            <p class="text-sm text-gray-600 mt-0.5"><?= esc($message) ?></p>
        </div>
        <button onclick="closeToast(this)" class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <?php endif; ?>

</div>

<style>
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .animate-slide-in {
        animation: slideIn 0.3s ease-out forwards;
    }
    
    .animate-slide-out {
        animation: slideOut 0.3s ease-in forwards;
    }
</style>

<script>
function closeToast(button) {
    const toast = button.closest('[data-toast]');
    if (toast) {
        toast.classList.remove('animate-slide-in');
        toast.classList.add('animate-slide-out');
        setTimeout(() => toast.remove(), 300);
    }
}

// Auto-dismiss toasts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const toasts = document.querySelectorAll('[data-toast]');
    toasts.forEach((toast, index) => {
        setTimeout(() => {
            if (toast.parentNode) {
                toast.classList.remove('animate-slide-in');
                toast.classList.add('animate-slide-out');
                setTimeout(() => toast.remove(), 300);
            }
        }, 3000 + (index * 300)); // Stagger dismissal
    });
});

// Global function to show toast programmatically
function showToast(type, title, message) {
    const container = document.getElementById('toast-container');
    const colors = {
        success: { border: 'border-emerald-500', bg: 'bg-emerald-100', text: 'text-emerald-600', icon: 'fa-check' },
        error: { border: 'border-red-500', bg: 'bg-red-100', text: 'text-red-600', icon: 'fa-times' },
        warning: { border: 'border-amber-500', bg: 'bg-amber-100', text: 'text-amber-600', icon: 'fa-exclamation-triangle' },
        info: { border: 'border-blue-500', bg: 'bg-blue-100', text: 'text-blue-600', icon: 'fa-info' }
    };
    const c = colors[type] || colors.info;
    
    const toast = document.createElement('div');
    toast.className = `toast-item pointer-events-auto bg-white border-l-4 ${c.border} rounded-lg shadow-lg p-4 flex items-start gap-3 animate-slide-in`;
    toast.setAttribute('data-toast', '');
    toast.innerHTML = `
        <div class="flex-shrink-0 w-8 h-8 ${c.bg} rounded-full flex items-center justify-center">
            <i class="fas ${c.icon} ${c.text}"></i>
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-gray-800">${title}</p>
            <p class="text-sm text-gray-600 mt-0.5">${message}</p>
        </div>
        <button onclick="closeToast(this)" class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    container.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.remove('animate-slide-in');
        toast.classList.add('animate-slide-out');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
</script>
