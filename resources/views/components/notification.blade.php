@if (session('success'))
    <div class="notification-toast success show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-success text-white">
            <i class="fas fa-check-circle me-2"></i>
            <strong class="me-auto">Success</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            {{ session('success') }}
        </div>
    </div>
@endif

@if (session('error'))
    <div class="notification-toast error show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-danger text-white">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong class="me-auto">Error</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            {{ session('error') }}
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="notification-toast error show" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-danger text-white">
            <i class="fas fa-exclamation-circle me-2"></i>
            <strong class="me-auto">Error</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endif

<style>
.notification-toast {
    position: fixed;
    top: 20px;
    right: 20px;
    min-width: 300px;
    z-index: 1050;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    opacity: 0;
    transform: translateY(-100%);
    transition: all 0.3s ease-in-out;
}

.notification-toast.show {
    opacity: 1;
    transform: translateY(0);
}

.notification-toast .toast-header {
    border-top-left-radius: 8px;
    border-top-right-radius: 8px;
    padding: 0.75rem 1rem;
}

.notification-toast .toast-body {
    padding: 1rem;
}

.notification-toast.success .toast-header {
    background-color: #28a745;
}

.notification-toast.error .toast-header {
    background-color: #dc3545;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toasts = document.querySelectorAll('.notification-toast');
    toasts.forEach(toast => {
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 5000);

        const closeBtn = toast.querySelector('.btn-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                toast.classList.remove('show');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            });
        }
    });
});
</script>
