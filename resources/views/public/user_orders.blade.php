@extends('public.layout.layout')
@section('content')
    <main id="maincontent" class="page-main">
        <div class="container flex flex-col md:flex-row flex-wrap font-semibold mb-5 md:mb-7 mt-7 sm:mt-8 lg:mt-10">
            <h1 class="page-title title-font mb-0">
                <span class="base" data-ui-id="page-title-wrapper">My Account</span> 
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
                <h2 class="mb-6 text-2xl block-title">User Orders</h2>
                <div class="justify-between -m-4">
                    <div wire:id="price-summary.cart-items" wire:initial-data="{...}">
                        <div class="cart-items mb-6">
                            <div class="flex flex-col gap-4">
                                <div class="relative grid gap-6 sm:gap-8">
                                    @forelse($orders as $order)
                                        <div class="order-summary card mb-6">
                                            <h3 class="text-lg">Order #{{ $order->id }} ({{ $order->created_at->format('d M Y') }})</h3>
                                            <p class="mb-2">Status: 
                                                @php
                                                    $statusClass = '';
                                                    $statusMessage = '';
                                                    switch($order->status) {
                                                        case 1:
                                                            $statusClass = 'bg-green-100 text-green-600';
                                                            $statusMessage = 'APPROVED';
                                                            break;
                                                        case 2:
                                                            $statusClass = 'bg-red-100 text-red-600';
                                                            $statusMessage = 'REJECTED';
                                                            break;
                                                        case 0:
                                                            $statusClass = 'bg-yellow-100 text-yellow-600';
                                                            $statusMessage = 'PENDING';
                                                            break;
                                                    }
                                                @endphp
                                                <span class="{{ $statusClass }} px-2 py-1 rounded">{{ ucfirst($statusMessage) }}</span>
                                            </p>

                                            <div class="overflow-x-auto">
                                                <table class="table table-bordered w-full mt-4 text-left">
                                                    <thead>
                                                        <tr>
                                                            <th>Product</th>
                                                            <th>Title</th>
                                                            <th>Quantity</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($order->orderDetails as $detail)
                                                            <tr>
                                                                <td>
                                                                    <div class="flex gap-4">
                                                                        <img src="{{ asset($detail->product->image) }}" width="75" height="75" alt="{{ $detail->product->name }}" loading="lazy" />
                                                                        <p>{{ $detail->product->name }}</p>
                                                                    </div>
                                                                </td>
                                                                <td>{{ $detail->product->title }}</td>
                                                                <td>{{ $detail->quantity }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                            <p class="mt-4 text-right">Order Total: TBD</p>
                                        </div>
                                    @empty
                                        @include('public.no-item', ['title' => 'No orders have been placed yet'])
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    </main>
@endsection
