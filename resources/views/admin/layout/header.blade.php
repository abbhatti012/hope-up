<div class="app-header d-flex align-items-center">
    <div class="d-flex">
        <button class="toggle-sidebar">
        <i class="ri-menu-line"></i>
        </button>
        <button class="pin-sidebar">
        <i class="ri-menu-line"></i>
        </button>
    </div>
    <div class="app-brand ms-3">
        <a href="{{ route('/') }}" class="d-lg-block d-none">
            <img src="{{ asset('assets/images/logo1.svg') }}" class="logo" alt="Medicare Admin Template">
        </a>
        <a href="{{ route('/') }}" class="d-lg-none d-md-block">
            <img src="{{ asset('assets/images/logo1.svg') }}" class="logo" alt="Medicare Admin Template">
        </a>
    </div>
    <div class="header-actions">

        <div class="search-container d-lg-block d-none mx-3">
        <input type="text" class="form-control" id="searchId" placeholder="Search users, appointments, transactions..." autocomplete="off">
        <i class="ri-search-line"></i>
        </div>

        <div class="dropdown ms-2">
            <a id="userSettings" class="dropdown-toggle d-flex align-items-center" href="#!" role="button"
                data-bs-toggle="dropdown" aria-expanded="false">
                @if(Auth::user()->profile_photo)
                    <img src="{{ asset(Auth::user()->profile_photo) }}" 
                         class="avatar-box rounded-circle" 
                         alt="Profile" 
                         style="width: 40px; height: 40px; object-fit: cover;">
                @else
                    <div class="avatar-box">{{ substr(Auth::user()->first_name, 0, 1) }}{{ substr(Auth::user()->last_name, 0, 1) }}</div>
                @endif
                <span class="status busy"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-end shadow-lg">
                <div class="px-3 py-2">
                    <span class="small text-muted">{{ ucfirst(Auth::user()->role) }}</span>
                    <h6 class="m-0">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h6>
                    <small class="text-muted">{{ Auth::user()->email }}</small>
                </div>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('admin.profile') }}">
                    <i class="ri-user-line me-2"></i>
                    Profile Settings
                </a>
                <div class="dropdown-divider"></div>
                <div class="px-3 py-2">
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="ri-logout-box-line me-2"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>