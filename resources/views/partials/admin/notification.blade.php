<div class="dropdown me-2">
    <button class="btn btn-link p-1 position-relative" data-bs-toggle="dropdown" aria-label="Notifications">
        <i class="fas fa-bell" style="font-size: 1.5rem; color: var(--primary-color);"></i>
        @if ($unreadNotifications->count() > 0)
            <span class="notification-badge position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{ $unreadNotifications->count() }}
                <span class="visually-hidden">unread notifications</span>
            </span>
        @endif
    </button>

    <ul class="dropdown-menu dropdown-menu-end notification-dropdown">
        <li class="p-3 border-bottom">
            <h6 class="mb-0">Notifications</h6>
        </li>

        @forelse($unreadNotifications as $notification)
            @php
                // Determine correct mark-as-read route
                $readRoute = Auth::user()->role === 'admin' 
                    ? route('admin.notifications.read', $notification->id)
                    : route('notifications.read', $notification->id);
            @endphp

            <li class="notification-item p-3">
                <a href="{{ $readRoute }}" class="text-decoration-none text-dark">
                    <div class="d-flex align-items-center">
                        <i class="{{ $notification->data['icon'] ?? 'fas fa-info-circle' }} me-2"></i>
                        <div>
                            <p class="mb-0">{{ $notification->data['message'] ?? 'New notification' }}</p>
                            <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                </a>
            </li>
        @empty
            <li class="p-3 text-center text-muted">No new notifications</li>
        @endforelse

        <li class="p-3 text-center">
             <a href="{{ route('admin.notifications.index') }}" class="text-primary text-decoration-none">
                 View all notifications
             </a>
         </li>
    </ul>
</div>
