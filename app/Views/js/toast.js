// Toast Notification System
class ToastManager {
    constructor() {
        this.container = null;
        this.init();
    }

    init() {
        if (!this.container) {
            this.container = document.createElement('div');
            this.container.className = 'toast-container';
            document.body.appendChild(this.container);
        }
    }

    show(message, type = 'info', duration = 4000) {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        
        const icons = {
            success: '✓',
            error: '✕',
            warning: '⚠',
            info: 'ℹ'
        };
        
        toast.innerHTML = `
            <span class="toast-icon">${icons[type] || icons.info}</span>
            <span class="toast-message">${message}</span>
            <button class="toast-close" onclick="this.parentElement.classList.add('removing'); setTimeout(() => this.parentElement.remove(), 300);">×</button>
        `;
        
        this.container.appendChild(toast);
        
        // Auto remove after duration
        setTimeout(() => {
            if (toast.parentElement) {
                toast.classList.add('removing');
                setTimeout(() => toast.remove(), 300);
            }
        }, duration);
        
        // Click to dismiss
        toast.addEventListener('click', (e) => {
            if (e.target !== toast.querySelector('.toast-close')) {
                toast.classList.add('removing');
                setTimeout(() => toast.remove(), 300);
            }
        });
        
        return toast;
    }

    success(message, duration) {
        return this.show(message, 'success', duration);
    }

    error(message, duration) {
        return this.show(message, 'error', duration);
    }

    warning(message, duration) {
        return this.show(message, 'warning', duration);
    }

    info(message, duration) {
        return this.show(message, 'info', duration);
    }
}

// Confirmation Modal System
class ConfirmModal {
    static show(options = {}) {
        const {
            title = 'Confirm Action',
            message = 'Are you sure you want to proceed?',
            confirmText = 'Delete',
            cancelText = 'Cancel',
            type = 'danger',
            onConfirm = () => {},
            onCancel = () => {}
        } = options;

        // Remove any existing modal
        const existingModal = document.querySelector('.modal-overlay');
        if (existingModal) {
            existingModal.remove();
        }

        const overlay = document.createElement('div');
        overlay.className = 'modal-overlay';
        
        overlay.innerHTML = `
            <div class="modal">
                <div class="modal-icon">⚠️</div>
                <h3>${title}</h3>
                <p>${message}</p>
                <div class="modal-actions">
                    <button class="modal-btn modal-btn-cancel">${cancelText}</button>
                    <button class="modal-btn modal-btn-${type}">${confirmText}</button>
                </div>
            </div>
        `;

        document.body.appendChild(overlay);

        // Event listeners
        const cancelBtn = overlay.querySelector('.modal-btn-cancel');
        const confirmBtn = overlay.querySelector('.modal-btn-danger, .modal-btn-primary');

        cancelBtn.addEventListener('click', () => {
            overlay.remove();
            onCancel();
        });

        confirmBtn.addEventListener('click', () => {
            overlay.remove();
            onConfirm();
        });

        // Click outside to close
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                overlay.remove();
                onCancel();
            }
        });

        // Escape key to close
        const escapeHandler = (e) => {
            if (e.key === 'Escape') {
                overlay.remove();
                onCancel();
                document.removeEventListener('keydown', escapeHandler);
            }
        };
        document.addEventListener('keydown', escapeHandler);

        return overlay;
    }

    static delete(options = {}) {
        return this.show({
            title: 'Delete Confirmation',
            message: options.message || 'Are you sure you want to delete this item? This action cannot be undone.',
            confirmText: 'Delete',
            cancelText: 'Cancel',
            type: 'danger',
            ...options
        });
    }
}

// Initialize global toast manager
const toast = new ToastManager();

// Check for URL parameters to show toast messages
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    
    if (urlParams.get('success')) {
        toast.success(decodeURIComponent(urlParams.get('success')));
        // Clean URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }
    
    if (urlParams.get('error')) {
        toast.error(decodeURIComponent(urlParams.get('error')));
        // Clean URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }
    
    if (urlParams.get('warning')) {
        toast.warning(decodeURIComponent(urlParams.get('warning')));
        // Clean URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});