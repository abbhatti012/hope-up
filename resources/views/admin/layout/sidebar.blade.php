<nav id="sidebar" class="sidebar-wrapper">
    <div class="sidebar-profile">
        <img src="{{ asset(Auth::user()->profile_photo) }}" class="img-shadow img-3x me-3 rounded-5" alt="Hope Up">
        <div class="m-0">
            <h5 class="mb-1 profile-name text-nowrap text-truncate">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h5>
            <p class="m-0 small profile-name text-nowrap text-truncate">{{ Auth::user()->role }}</p>
        </div>
    </div>
    @php
        $currentRoute = request()->route()->getName();
        $isDoctorsSection = in_array($currentRoute, ['doctors.dashboard', 'manage.doctors', 'doctors.users.create', 'doctors.users.edit']);
        $isPatientsSection = in_array($currentRoute, ['patients.dashboard', 'manage.patients', 'patients.create', 'patients.edit']);
        $isAdminSection = in_array($currentRoute, ['manage.admins', 'admin.users.create', 'admin.users.edit']);
        $isSpecialitySection = in_array($currentRoute, ['manage.speciality', 'specialities.create', 'specialities.edit']);
        $isSchedulesSection = in_array($currentRoute, ['schedules.index', 'schedules.create', 'schedules.edit']);
        $isAppointmentsSection = in_array($currentRoute, ['manage-appointments', 'appointments.create', 'appointments.edit']);
        $isTransactionsSection = in_array($currentRoute, ['manage-transactions', 'transactions.create', 'transactions.edit']);
        $isMhcSection = in_array($currentRoute, ['manage-mhc', 'content.create', 'content.edit']);
        $isReviewsSection = in_array($currentRoute, ['manage-reviews', 'reviews.create', 'reviews.edit']);
        $isLoginActivitiesSection = in_array($currentRoute, ['manage-login-activities', 'login-activities.create', 'login-activities.edit']);
        $isAssessmentsSection = in_array($currentRoute, ['assessments.index', 'assessments.create', 'assessments.edit', 'assessments.show', 'user.answers']);
    @endphp
    
    <div class="sidebarMenuScroll">
        <ul class="sidebar-menu">
            <li class="{{ request()->routeIs('home') ? 'active current-page' : '' }}">
                <a href="{{ route('home') }}">
                    <i class="ri-home-6-line"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>
            <li class="treeview {{ $isAdminSection ? 'active' : '' }}">
                <a href="#!">
                    <i class="ri-stethoscope-line"></i>
                    <span class="menu-text">Manage Admins</span>
                </a>
                <ul class="treeview-menu" style="display: {{ $isAdminSection ? 'block' : 'none' }};">
                    <li class="{{ request()->routeIs('manage.admins') ? 'active' : '' }}">
                        <a href="{{ route('manage.admins') }}">All Admins</a>
                    </li>
                    <li class="{{ request()->routeIs('admin.users.create') ? 'active' : '' }}">
                        <a href="{{ route('admin.users.create') }}">Add Admin</a>
                    </li>
                </ul>
            </li>
            <li class="treeview {{ $isDoctorsSection ? 'active' : '' }}">
                <a href="#!">
                    <i class="ri-stethoscope-line"></i>
                    <span class="menu-text">Manage Doctors</span>
                </a>
                <ul class="treeview-menu" style="display: {{ $isDoctorsSection ? 'block' : 'none' }};">
                    <li class="{{ request()->routeIs('manage.doctors') ? 'active' : '' }}">
                        <a href="{{ route('manage.doctors') }}">All Doctors</a>
                    </li>
                    <li class="{{ request()->routeIs('doctors.users.create') ? 'active' : '' }}">
                        <a href="{{ route('doctors.users.create') }}">Add Doctor</a>
                    </li>
                </ul>
            </li>
            <li class="treeview {{ $isPatientsSection ? 'active' : '' }}">
                <a href="#!">
                    <i class="ri-user-heart-line"></i>
                    <span class="menu-text">Manage Customers</span>
                </a>
                <ul class="treeview-menu" style="display: {{ $isPatientsSection ? 'block' : 'none' }};">
                    <li class="{{ request()->routeIs('manage.patients') ? 'active' : '' }}">
                        <a href="{{ route('manage.patients') }}">All Customers</a>
                    </li>
                    <li class="{{ request()->routeIs('patients.create') ? 'active' : '' }}">
                        <a href="{{ route('patients.create') }}">Add Customer</a>
                    </li>
                </ul>
            </li>
            <li class="treeview {{ $isSpecialitySection ? 'active' : '' }}">
                <a href="javascript:void(0);" class="has-arrow">
                    <i class="ri-user-heart-line"></i>
                    <span class="menu-text">Manage Speciality</span>
                </a>
                <ul class="treeview-menu" style="display: {{ $isSpecialitySection ? 'block' : 'none' }};">
                    <li class="{{ request()->routeIs('manage.speciality') ? 'active' : '' }}">
                        <a href="{{ route('manage.speciality') }}">All Speciality</a>
                    </li>
                    <li class="{{ request()->routeIs('specialities.create') ? 'active' : '' }}">
                        <a href="{{ route('specialities.create') }}">Add Speciality</a>
                    </li>
                </ul>
            </li>
            <li class="treeview {{ $isSchedulesSection ? 'active' : '' }}">
                <a href="javascript:void(0);" class="has-arrow">
                    <i class="ri-calendar-todo-line"></i>
                    <span class="menu-text">Manage Schedules</span>
                </a>
                <ul class="treeview-menu" style="display: {{ $isSchedulesSection ? 'block' : 'none' }};">
                    <li class="{{ request()->routeIs('schedules.index') ? 'active' : '' }}">
                        <a href="{{ route('schedules.index') }}">All Schedules</a>
                    </li>
                </ul>
            </li>
            <li class="treeview {{ $isAppointmentsSection ? 'active' : '' }}">
                <a href="javascript:void(0);" class="has-arrow">
                    <i class="ri-calendar-check-line"></i>
                    <span class="menu-text">Appointments</span>
                </a>
                <ul class="treeview-menu" style="display: {{ $isAppointmentsSection ? 'block' : 'none' }};">
                    <li class="{{ request()->routeIs('manage-appointments') ? 'active' : '' }}">
                        <a href="{{ route('manage-appointments') }}">All Appointments</a>
                    </li>
                    <li class="{{ request()->routeIs('appointments.create') ? 'active' : '' }}">
                        <a href="{{ route('appointments.create') }}">Add Appointment</a>
                    </li>
                </ul>
            </li>
            <li class="treeview {{ $isTransactionsSection ? 'active' : '' }}">
                <a href="javascript:void(0);" class="has-arrow">
                    <i class="ri-exchange-dollar-line"></i>
                    <span class="menu-text">Transactions Report</span>
                </a>
                <ul class="treeview-menu" style="display: {{ $isTransactionsSection ? 'block' : 'none' }};">
                    <li class="{{ request()->routeIs('manage-transactions') ? 'active' : '' }}">
                        <a href="{{ route('manage-transactions') }}">All Transactions</a>
                    </li>
                </ul>
            </li>
            <li class="treeview {{ $isMhcSection ? 'active' : '' }}">
                <a href="javascript:void(0);" class="has-arrow">
                    <i class="ri-hospital-line"></i>
                    <span class="menu-text">Manage MHC</span>
                </a>
                <ul class="treeview-menu" style="display: {{ $isMhcSection ? 'block' : 'none' }};">
                    <li class="{{ request()->routeIs('manage-mhc') ? 'active' : '' }}">
                        <a href="{{ route('manage-mhc') }}">All MHC</a>
                    </li>
                    <li class="{{ request()->routeIs('content.create') ? 'active' : '' }}">
                        <a href="{{ route('content.create') }}">Add MHC</a>
                    </li>
                </ul>
            </li>
            <li class="treeview {{ $isReviewsSection ? 'active' : '' }}">
                <a href="javascript:void(0);" class="has-arrow">
                    <i class="ri-star-line"></i>
                    <span class="menu-text">Manage Reviews</span>
                </a>
                <ul class="treeview-menu" style="display: {{ $isReviewsSection ? 'block' : 'none' }};">
                    <li class="{{ request()->routeIs('reviews.index') ? 'active' : '' }}">
                        <a href="{{ route('reviews.index') }}">All Reviews</a>
                    </li>
                    <li class="{{ request()->routeIs('reviews.create') ? 'active' : '' }}">
                        <a href="{{ route('reviews.create') }}">Add Review</a>
                    </li>
                </ul>
            </li>
            <li class="treeview {{ $isLoginActivitiesSection ? 'active' : '' }}">
                <a href="javascript:void(0);" class="has-arrow">
                    <i class="ri-history-line"></i>
                    <span class="menu-text">Login Activities</span>
                </a>
                <ul class="treeview-menu" style="display: {{ $isLoginActivitiesSection ? 'block' : 'none' }};">
                    <li class="{{ request()->routeIs('admin.login-activities.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.login-activities.index') }}">All Login Activities</a>
                    </li>
                </ul>
            </li>
            <li class="treeview {{ $isAssessmentsSection ? 'active' : '' }}">
                <a href="javascript:void(0);" class="has-arrow">
                    <i class="ri-file-list-3-line"></i>
                    <span class="menu-text">Assessment Questions</span>
                </a>
                <ul class="treeview-menu" style="display: {{ $isAssessmentsSection ? 'block' : 'none' }};">
                    <li class="{{ request()->routeIs('assessments.index') ? 'active' : '' }}">
                        <a href="{{ route('assessments.index') }}">All Questions</a>
                    </li>
                    <li class="{{ request()->routeIs('assessments.create') ? 'active' : '' }}">
                        <a href="{{ route('assessments.create') }}">Add Question</a>
                    </li>
                    <li class="{{ request()->routeIs('user.answers.list') ? 'active' : '' }}">
                        <a href="{{ route('user.answers.list') }}">User Answers</a>
                    </li>
                </ul>
            </li>
            <li class="{{ request()->routeIs('admin.web-settings') ? 'active current-page' : '' }}">
                <a href="{{ route('admin.web-settings') }}">
                    <i class="ri-settings-3-line"></i>
                    <span class="menu-text">Web Settings</span>
                </a>
            </li>
        </ul>
    </div>
    
    <script>
    
    </script>
    
    <div class="sidebar-contact">
        <p class="fw-light mb-1 text-nowrap text-truncate">Emergency Contact</p>
        <h5 class="m-0 lh-1 text-nowrap text-truncate">1234567890</h5>
        <i class="ri-phone-line"></i>
    </div>
</nav>