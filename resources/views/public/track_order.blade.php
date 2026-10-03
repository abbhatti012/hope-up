@extends('public.layout.layout')
@section('content')
<script src="//unpkg.com/alpinejs" defer></script>
<style>
    .form-edit-account .form-input {
        width: 100%;
    }
</style>
<main id="maincontent" class="page-main" x-data="accountForm()">
    <div class="container flex flex-col md:flex-row flex-wrap font-semibold mb-5 md:mb-7 mt-7 sm:mt-8 lg:mt-10">
        <h1 class="page-title title-font mb-0">
            <span class="base" data-ui-id="page-title-wrapper">Track Order</span>
        </h1>
    </div>
    <div class="columns">
        <aside class="sidebar sidebar-main">
            <div class="block account-nav card filter-option py-4 mb-6 md:mb-0" x-data="initAccountNavigation()" x-init="checkIsMobileResolution()" @resize.window.debounce="checkIsMobileResolution()" @visibilitychange.window.debounce="checkIsMobileResolution()">
                <button type="button" class="title account-nav-title flex justify-between items-center hover:text-secondary-darker w-full">
                    <span class="text-lg title">My Account</span>
                    <span class="px-1 py-1 md:hidden" x-ref="AccountNavigationMobileToggleIcon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 17.406 11.087" stroke="#000" class="transition-transform duration-300 ease-in-out transform rotate-180" :class="{ 'rotate-180': blockOpen }" aria-hidden="true" focusable="false">
                            <path id="Icon_ion-ios-arrow-right_15" data-name="Icon ion-ios-arrow-right" d="M10.625,7.347l1.037-.972,8.585,8.019-8.585,8.019-1.037-.967,7.542-7.051Z" transform="translate(23.096 -9.892) rotate(90)" stroke-width="1" />
                        </svg>
                    </span>
                </button>
                <div :class="{ 'hidden': !blockOpen }" class="delimiter border-b border-container w-full mt-4 mb-3 hidden md:block"></div>
                <div class="content account-nav-content hidden md:block" :class="{ 'hidden': !blockOpen }" id="account-nav">
                    @include('public.aside-menu')
                </div>
            </div>
        </aside>
        <div class="column main">
        @if ($errors->any())
            <div class="bg-red-500 text-white p-4 rounded-md mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded-md mb-4">
                {{ session('success') }}
            </div>
            @endif
            <div class="bg-yellow-500 text-white p-4 rounded-md mb-4">
                To track your order please enter your Order ID in the box below and press the "Track" button. This was given to you on your receipt and in the confirmation email you should have received.
            </div>
            <form class="form form-edit-account card" action="{{ route('user-orders') }}" method="get" id="form-validate" enctype="multipart/form-data" autocomplete="off" novalidate="">
                @csrf
                
                <div class="field field-reserved">
                    <label class="label" for="order_number">
                        <span>Order Number</span>
                    </label>
                    <div class="control">
                        <input type="text" name="order_number" id="order_number" required title="Order Number" class="form-input">
                    </div>
                </div>

                <div class="field field-reserved email required !mt-4">
                    <label class="label" for="email">
                        <span>Email</span>
                    </label>
                    <div class="control">
                        <input type="email" name="email" id="email" required title="Email" class="form-input">
                    </div>
                </div>

                <div class="actions-toolbar">
                    <div class="primary">
                        <button type="submit" class="action save primary" title="Save">
                            <span>Track</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

@endsection
