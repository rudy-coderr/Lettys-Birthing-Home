<!-- Toast Notification from Staff Management Page with Warning Support -->
    <div class="toast-container">
        @if (session('message') && session('type'))
            <div class="toast {{ session('type') === 'success' ? 'toast-success' : (session('type') === 'warning' ? 'toast-warning' : 'toast-error') }}"
                role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <strong
                        class="me-auto">{{ session('type') === 'success' ? 'Success' : (session('type') === 'warning' ? 'Warning' : 'Error') }}</strong>
                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    {{ session('message') }}
                </div>
            </div>
        @endif
    </div>
