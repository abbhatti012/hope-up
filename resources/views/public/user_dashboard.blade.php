@extends('public.layout.layout')
@section('content')
    <main id="maincontent" class="page-main">
        <div class="container&#x20;flex&#x20;flex-col&#x20;md&#x3A;flex-row&#x20;flex-wrap&#x20;font-semibold&#x20;mb-5&#x20;md&#x3A;mb-7&#x20;mt-7&#x20;sm&#x3A;mt-8&#x20;lg&#x3A;mt-10">
            <h1 class="page-title title-font mb-0">
                <span class="base" data-ui-id="page-title-wrapper">My Account</span> 
            </h1>
        </div>
        <div class="columns">
            <aside class="sidebar sidebar-main">
                <div class="block account-nav card filter-option py-4 mb-6 md:mb-0" x-data="initAccountNavigation()" x-init="checkIsMobileResolution()" @resize.window.debounce="checkIsMobileResolution()" @visibilitychange.window.debounce="checkIsMobileResolution()">
                    <button type="button" class="
                    title account-nav-title
                    flex justify-between
                    items-center hover:text-secondary-darker w-full">
                    <span class="text-lg title">
                    My Account </span>
                    <span class="px-1 py-1  md:hidden" x-ref="AccountNavigationMobileToggleIcon">
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
                <h2 class="mb-6 text-2xl block-title">
                    Account Information
                </h2>
                <div class="flex flex-wrap justify-between -m-4">
                    <div class="w-full p-4 lg:w-1/2">
                    <div class="flex flex-col h-full sm:flex-row card">
                        <div class="inline-flex items-center justify-center shrink-0 w-16 h-16 mb-4
                            rounded-full sm:mr-8 sm:mb-0 bg-container-darker text-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="32" height="32" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="grow">
                            <h3 class="mb-3 text-lg font-semibold title-font">
                                <span>Contact Information</span>
                            </h3>
                            <p>
                                {{ $user->name }}<br>
                                <br>
                                <a href="javascript:void(0)">{{ $user->email }}</a><br>
                            </p>
                            <a class="inline-flex items-center w-full mt-3 md:text-sm text-secondary hover:text-secondary-darker" href="{{ route('account-information') }}" aria-label="Edit contact information">
                                <span>Edit</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="ml-2" width="16" height="16" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                            <a class="inline-flex items-center w-full md:text-sm text-secondary hover:text-secondary-darker" href="{{ route('account-information') }}">
                                Change Password 
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="ml-2" width="16" height="16" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    </div>
                    <div class="w-full p-4 lg:w-1/2">
                    <div class="flex flex-col h-full border border-gray-200 sm:flex-row card">
                        <div class="grow">
                            <h3 class="mb-3 text-lg font-semibold title-font">
                                <span>Billing Information</span>
                            </h3>
                            @if($address)
                            <p>
                                {{ $address->firstname }} {{ $address->lastname }}<br>
                                {{ $address->email }}<br>
                                {{ $address->telephone }}<br>
                                {{ $address->street_address }}<br>
                                {{ $address->city }}<br>
                                {{ $address->country }}<br>
                            </p>
                            @else
                                <div class="message info empty"><span>You have no default address</span></div>
                            @endif
                            <a class="inline-flex items-center w-full mt-3 md:text-sm text-secondary hover:text-secondary-darker" href="{{ route('billing-information') }}" aria-label="Edit contact information">
                                <span>Edit</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="ml-2" width="16" height="16" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="block block-dashboard-addresses">
                    <h2 class="block-title my-6 flex justify-between items-center card">
                    <span class="text-2xl block">Address Book</span>
                    <a class="action edit inline-block underline" href="{{ route('billing-information') }}">
                    <span>Manage Addresses</span>
                    </a>
                    </h2>
                </div>
                
            </div>
        </div>
    </main>
@endsection