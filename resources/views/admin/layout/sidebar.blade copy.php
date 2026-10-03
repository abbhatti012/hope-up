<nav id="sidebar">
    <div class="sidebar-content">
        <div class="content-header content-header-fullrow px-15">
            <div class="content-header-section sidebar-mini-visible-b">
                <span class="content-header-item font-w700 font-size-xl float-left animated fadeIn">
                    <span class="text-dual-primary-dark">c</span><span class="text-primary">b</span>
                </span>
            </div>
            <div class="content-header-section text-center align-parent sidebar-mini-hidden">
                <button type="button" class="btn btn-circle btn-dual-secondary d-lg-none align-v-r" data-toggle="layout" data-action="sidebar_close">
                    <i class="fa fa-times text-danger"></i>
                </button>
                <div class="content-header-item">
                    <a class="link-effect font-w700" href="{{ url('/') }}">
                        <img width="100" src="" alt="">
                    </a>
                </div>
            </div>
        </div>
        <div class="content-side content-side-full content-side-user px-10 align-parent">
            <div class="sidebar-mini-visible-b align-v animated fadeIn">
                <img class="img-avatar img-avatar32" src="{{ asset(session('profile_photo')) }}" alt="">
            </div>
            <div class="sidebar-mini-hidden-b text-center">
                <a class="img-link" href="javascript:void(0)">
                    <img class="img-avatar" src="{{ asset(session('profile_photo')) }}" alt="">
                </a>
                <ul class="list-inline mt-10">
                    <li class="list-inline-item">
                        <a class="link-effect text-dual-primary-dark font-size-sm font-w600 text-uppercase" href="#">{{ auth()->user()->first_name }}</a>
                    </li>
                    <li class="list-inline-item">
                        <a class="link-effect text-dual-primary-dark" href="javascript:void(0)" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="si si-logout"></i>
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        <div class="content-side content-side-full">
            <ul class="nav-main">
                <li>
                    <a href="{{ route('home') }}"><i class="fa fa-dashboard"></i><span class="sidebar-mini-hide">Dashboard</span></a>
                </li>
                
                <li class="<?php if(Request::segment(1) == 'manage-users' || Request::segment(1) == 'add-speciality'){echo "open";} ?>">
                    <a class="nav-submenu" data-toggle="nav-submenu" href="#"><i class="fa fa-list-alt"></i><span class="sidebar-mini-hide">Manage Categories</span></a>
                    <ul>
                        <li>
                            <a class="<?php if(Request::segment(1) == 'manage-users'){echo "active";} ?>" href="{{ route('manage.doctors') }}"><span class="sidebar-mini-hide">Manage Users</span></a>
                        </li>
                        <li>
                            <a class="<?php if(Request::segment(1) == 'add-speciality'){echo "active";} ?>" href="{{ route('manage.speciality') }}"><span class="sidebar-mini-hide">Add Speciality</span></a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('schedules.index') }}"><i class="fa fa-calendar"></i><span class="sidebar-mini-hide">Manage Schedules</span></a>
                </li>
                <li>
                    <a href="{{ route('manage-appointments') }}"><i class="fa fa-list"></i><span class="sidebar-mini-hide">Manage Appointments</span></a>
                </li>
                <li>
                    <a href="{{ route('manage-transactions') }}"><i class="fa fa-exchange"></i><span class="sidebar-mini-hide">Manage Transactions</span></a>
                </li>
                <li>
                    <a href="{{ route('manage-mhc') }}"><i class="fa fa-user-md"></i><span class="sidebar-mini-hide">Manage MHC</span></a>
                </li>
            </ul>
        </div>
    </div>
</nav>
