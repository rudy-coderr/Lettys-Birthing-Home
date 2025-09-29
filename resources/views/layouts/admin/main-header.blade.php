<!-- Main Content -->
<main class="content">
    <header class="main-header navbar navbar-expand-lg navbar-light sticky-top mb-4">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <button class="mobile-menu-btn d-md-none me-3" id="mobileMenuBtnHeader" aria-label="Toggle sidebar menu">
                    <i class="fas fa-bars"></i>
                </button>
                <h4 class="page-title mb-0">@yield('page-title', 'Default Title')</h4>
            </div>

            <div class="d-flex align-items-center header-right">
                <!-- Notification Icon with Dropdown -->
                @include('partials.admin.notification')


                <!-- Profile Dropdown -->
                @php
                    $user = Auth::user();
                    $avatar =
                        $user->admin && $user->admin->avatar_path
                            ? asset($user->admin->avatar_path)
                            : asset('img/adminProfile.jpg');
                @endphp
                <div class="dropdown user-profile">
                    <button class="btn btn-link p-1" data-bs-toggle="dropdown">
                        <img src="{{ $avatar }}" alt="Profile" class="rounded-circle" width="40"
                            height="40">
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end profile-dropdown">
                        <li><a class="dropdown-item profile-dropdown-item" href="{{ route('adminProfile') }}">
                                <i class="fas fa-user-circle me-2"></i>My Profile
                            </a></li>

                        <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item profile-dropdown-item text-danger" href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a></li>
                    </ul>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </header>
