@extends('public.layout.layout')

@section('content')
    <style>
        .cmsb114-grid {
            display: grid
        }
        .cmsb114-max-w-md {
            max-width: 28rem
        }
        .cmsb114-grid-cols-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr))
        }
        .cmsb114-gap-6 {
            gap: 1.5rem
        }
    </style>
    <main id="maincontent" class="page-main">
        <div class="container flex flex-col md&#x3A;flex-row flex-wrap font-semibold mb-5 md&#x3A;mb-7 sr-only">
            <h1 class="page-title title-font mb-0">
                <span class="base" data-ui-id="page-title-wrapper">Checkout</span> 
            </h1>
        </div>
        @if(count($cartContent) > 0)
        <div class="columns">
            <div class="column main">
                <div wire:id="hyva-checkout-main" wire:initial-data="{&quot;fingerprint&quot;:{&quot;id&quot;:&quot;hyva-checkout-main&quot;,&quot;name&quot;:&quot;hyva-checkout-main&quot;,&quot;locale&quot;:&quot;en_GB&quot;,&quot;path&quot;:&quot;\/&quot;,&quot;method&quot;:&quot;GET&quot;,&quot;resolver&quot;:&quot;hyva_checkout&quot;,&quot;handle&quot;:&quot;hyva_checkout_index_index&quot;,&quot;type&quot;:&quot;hyva-checkout-main&quot;,&quot;v&quot;:&quot;acj&quot;},&quot;effects&quot;:{&quot;loader&quot;:{&quot;navigateToStep&quot;:true,&quot;placeOrder&quot;:[&quot;Processing your order&quot;]},&quot;listeners&quot;:[&quot;refresh&quot;]},&quot;serverMemo&quot;:{&quot;data&quot;:{&quot;config&quot;:{&quot;step_history&quot;:{&quot;current&quot;:{&quot;name&quot;:&quot;shipping&quot;,&quot;label&quot;:&quot;Delivery&quot;,&quot;route&quot;:&quot;shipping&quot;,&quot;position&quot;:1},&quot;previous&quot;:{&quot;name&quot;:&quot;login&quot;,&quot;label&quot;:&quot;Login&quot;,&quot;route&quot;:&quot;login&quot;,&quot;position&quot;:0}},&quot;messenger_api&quot;:{&quot;querySelectorClass&quot;:&quot;component-messenger&quot;}}},&quot;evaluation&quot;:{&quot;checkout.shipping-details.address-form&quot;:{&quot;arguments&quot;:{&quot;event&quot;:&quot;validate-address-form&quot;,&quot;detail&quot;:{&quot;form&quot;:&quot;shipping&quot;,&quot;event&quot;:&quot;shipping:details:error&quot;,&quot;component&quot;:{&quot;id&quot;:&quot;checkout.shipping-details.address-form&quot;},&quot;message&quot;:{&quot;text&quot;:&quot;\&quot;firstname\&quot; is required. Enter and try again.&quot;,&quot;type&quot;:&quot;error&quot;,&quot;duration&quot;:3600000}},&quot;dispatch&quot;:false,&quot;blocking&quot;:{&quot;result&quot;:false,&quot;cause&quot;:null}},&quot;dispatch&quot;:false,&quot;result&quot;:false,&quot;type&quot;:&quot;event&quot;,&quot;id&quot;:&quot;checkout.shipping-details.address-form&quot;,&quot;blocking&quot;:false,&quot;hash&quot;:&quot;8b566fa9c4d4f512e4dba2ac0948032fc5724160&quot;},&quot;checkout.shipping.methods&quot;:{&quot;arguments&quot;:{&quot;event&quot;:&quot;shipping:method:success&quot;,&quot;detail&quot;:{&quot;component&quot;:{&quot;id&quot;:&quot;checkout.shipping.methods&quot;}},&quot;dispatch&quot;:true},&quot;dispatch&quot;:true,&quot;result&quot;:true,&quot;type&quot;:&quot;event&quot;,&quot;id&quot;:&quot;checkout.shipping.methods&quot;,&quot;blocking&quot;:false,&quot;hash&quot;:&quot;ff46e9aa7a9ed8288a55c2c3bd7e8c4966d62be1&quot;},&quot;hyva-checkout-main&quot;:{&quot;arguments&quot;:{&quot;event&quot;:&quot;evaluation:event:success&quot;,&quot;detail&quot;:{&quot;component&quot;:{&quot;id&quot;:&quot;hyva-checkout-main&quot;}},&quot;dispatch&quot;:true},&quot;dispatch&quot;:true,&quot;result&quot;:true,&quot;type&quot;:&quot;event&quot;,&quot;id&quot;:&quot;hyva-checkout-main&quot;,&quot;blocking&quot;:false,&quot;hash&quot;:&quot;faa8acde1e46db1d23fb3fc4d18f3f77c1905f54&quot;}},&quot;htmlHash&quot;:&quot;cefc7322&quot;,&quot;children&quot;:{&quot;checkout.shipping-details&quot;:{&quot;id&quot;:&quot;checkout.shipping-details&quot;,&quot;tag&quot;:&quot;div&quot;},&quot;checkout.shipping.methods&quot;:{&quot;id&quot;:&quot;checkout.shipping.methods&quot;,&quot;tag&quot;:&quot;div&quot;},&quot;price-summary.cart-items&quot;:{&quot;id&quot;:&quot;price-summary.cart-items&quot;,&quot;tag&quot;:&quot;div&quot;},&quot;price-summary.total-segments&quot;:{&quot;id&quot;:&quot;price-summary.total-segments&quot;,&quot;tag&quot;:&quot;div&quot;}},&quot;checksum&quot;:&quot;a06ad117adda7e8759d7d11c032d8f9fac40ef53da2c56b50e6ed3516e54c04e&quot;}}" id="hyva-checkout-main" class="step-shipping step-layout-2columns" role="main">
                    <nav id="breadcrumbs" class="flex xl:mt-12 lg:mt-10 md:mt-8 mt-6 mx-auto">
                        <ul class="breadcrumbs items-center space-x-2 xs:space-x-4 flex">
                            <li class="step space-x-2 xs:space-x-4 inline-flex items-center text-kes-blue text-center">
                                <button type="button" class="item completed" onclick="hyvaCheckout.navigation.stepTo('login', false)">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" class="mx-auto mb-3" aria-hidden="true">
                                        <path d="M7 4H1V2h6c1.1 0 2-.9 2-2h12c0 1.1.9 2 2 2h6v2h-6c-1.1 0-2 .9-2 2v4h-1V8h-8V4H7zM0 4h1.2l2.1 9h21.4l1.2-3H4.2l-.9-3h21.4l1.1-3H3.8L0 4zm0 2h3l1.2 3h-3l-.9-3zm2.3 6h20.3l1.2 3H3.1l1.2-3zM7 16c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3zm0 4c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1zm15 0c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3zm0 4c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1z"/>
                                    </svg>
                                </button>
                                <span class="step-number item completed">Shopping Cart</span>
                            </li>
                            
                            <li class="step space-x-2 xs:space-x-4 inline-flex items-center text-kes-blue text-center">
                            <span class="w-4 xs:w-10 h-0.5 bg-kes-lightgray md:w-20 bg-kes-blue"></span>
                                <button type="button" class="item completed active" onclick="hyvaCheckout.navigation.stepTo('login', false)">
                                    <svg xmlns="https://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 31.561 34.91" class="mx-auto mb-3" aria-hidden="true">
                                    <g id="Group_793" data-name="Group 793" transform="translate(-55 -33.667)">
                                        <path id="Path_271" data-name="Path 271" d="M82.5,33.667H59.056A4.065,4.065,0,0,0,55,37.724v26.8a4.065,4.065,0,0,0,4.057,4.056H82.5a4.065,4.065,0,0,0,4.056-4.057v-26.8A4.065,4.065,0,0,0,82.5,33.667Zm2.644,30.855a2.651,2.651,0,0,1-2.642,2.642H59.056a2.651,2.651,0,0,1-2.643-2.642v-26.8a2.651,2.651,0,0,1,2.642-2.643H82.5a2.651,2.651,0,0,1,2.643,2.642Z" />
                                        <path id="Path_272" data-name="Path 272" d="M183.707,101.812a.707.707,0,0,0,.707-.707,4.318,4.318,0,1,1,8.636,0,.707.707,0,1,0,1.413,0,5.742,5.742,0,0,0-2.44-4.691l-.569-.4.52-.461a4.892,4.892,0,1,0-6.487,0l.52.461-.568.4A5.742,5.742,0,0,0,183,101.105.707.707,0,0,0,183.707,101.812Zm1.544-9.918a3.481,3.481,0,1,1,3.481,3.481A3.484,3.484,0,0,1,185.251,91.894Z" transform="translate(-117.951 -49.146)" />
                                        <path id="Path_273" data-name="Path 273" d="M166.78,321.667H151.707a.707.707,0,0,0,0,1.413H166.78a.707.707,0,1,0,0-1.413Z" transform="translate(-88.463 -265.389)" />
                                        <path id="Path_274" data-name="Path 274" d="M166.78,385.667H151.707a.707.707,0,0,0,0,1.413H166.78a.707.707,0,0,0,0-1.413Z" transform="translate(-88.463 -324.365)" />
                                    </g>
                                    </svg>
                                </button>
                                <span class="step-number item completed active">Checkout Details</span>
                            </li>

                            <li class="step space-x-2 xs:space-x-4 inline-flex items-center text-kes-blue text-center">
                                <span class="w-4 xs:w-10 h-0.5 bg-kes-lightgray md:w-20 bg-kes-blue"></span>
                                <span class="item locked">
                                    <svg xmlns="https://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 37.052 32.319" class="mx-auto mb-3" aria-hidden="true">
                                    <g id="Group_792" data-name="Group 792" transform="translate(-236.205 -239.233)">
                                        <path id="Path_267" data-name="Path 267" d="M272.487,261.028a.773.773,0,0,0-.772.77v8.7H237.748V255.468h13.432a.772.772,0,0,0,0-1.544H237.748v-5.561h13.432a.773.773,0,0,0,0-1.545H238.158a1.959,1.959,0,0,0-1.953,1.96v21.307a1.96,1.96,0,0,0,1.953,1.954H271.3a1.961,1.961,0,0,0,1.954-1.954V261.8A.773.773,0,0,0,272.487,261.028Z" transform="translate(0 -0.487)" />
                                        <path id="Path_268" data-name="Path 268" d="M247.542,262.777a.773.773,0,0,0-.773-.771h-4.734a.772.772,0,0,0,0,1.543h4.734A.773.773,0,0,0,247.542,262.777Z" transform="translate(-0.324 -1.461)" />
                                        <path id="Path_269" data-name="Path 269" d="M265.112,261.978a.787.787,0,0,0,.769,0c6.321-3.633,8.672-7.344,8.672-13.689v-4.732a.772.772,0,0,0-.466-.71L265.8,239.3a.8.8,0,0,0-.306-.063.8.8,0,0,0-.31.063l-8.281,3.549a.773.773,0,0,0-.465.71v4.734C256.439,254.626,258.79,258.337,265.112,261.978Zm-7.129-17.913.071-.03,7.443-3.192.046.021,7.469,3.2v4.224c0,5.629-1.911,8.77-7.3,12l-.213.13-.213-.131c-5.39-3.238-7.3-6.378-7.3-12Z" transform="translate(-1.298)" />
                                        <path id="Path_270" data-name="Path 270" d="M264,253.939a.777.777,0,0,0,.605.343h.037a.764.764,0,0,0,.6-.289l4.734-5.918a.771.771,0,1,0-1.205-.963l-4.072,5.093-1.788-2.67a.766.766,0,0,0-.488-.329.778.778,0,0,0-.154-.015.756.756,0,0,0-.423.128.774.774,0,0,0-.216,1.069Z" transform="translate(-1.623 -0.487)" />
                                    </g>
                                    </svg>
                                </span>
                                <span class="step-number item completed">Order Complete</span>
                            </li>
                        </ul>

                        <div class="flex flex-col space-y-4 float-right md:space-x-4 md:space-y-0 md:flex-row"></div>
                    </nav>
                    <br>
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
                    <div id="hyva-checkout-container" class="hyva-checkout-container w-full md:mx-auto md:mb-10 sm:mb-6 mb-4">
                        <div class="flex flex-col gap-8 lg:flex-row">
                            <div class="column column-main w-full flex flex-col gap-8 lg:w-2/3">
                                <section id="shipping-details">
                                    <header class="section-title border-b border-gray-400 pb-1 mb-6">
                                    <h2 class="text-gray-800 text-xl font-medium">
                                        Delivery Address 
                                    </h2>
                                    </header>
                                    <form action="{{ route('submit.order') }}" id="shipping" class="space-y-2 address-form !px-0 !pt-6 !pb-0" method="POST" novalidate>
                                        @csrf
                                        <div class="grid grid-cols-12 gap-x-4">
                                            <div class="col-span-12 field-wrapper field-type-text field field-reserved field-firstname md&#x3A;col-span-12" wire:key="field-wrapper-shipping-address-firstname">
                                                <div class="w-full font-medium text-gray-700 required">
                                                <label for="shipping-firstname" class="label label-firstname">
                                                <span>Email</span>
                                                </label>
                                                <div class="flex items-center gap-4">
                                                    <input class="block w-full form-input grow renderer-text firstname" value="{{ old('email') }}" type="email" id="email" name="email" placeholder="Enter Email" />
                                                </div>
                                                @error('email')<div class="text-red-600">{{ $message }}</div>@enderror
                                                </div>
                                            </div>
                                            <div class="col-span-12 field-wrapper field-type-text field field-reserved field-firstname md&#x3A;col-span-12" wire:key="field-wrapper-shipping-address-firstname">
                                                <div class="w-full font-medium text-gray-700 required">
                                                <label for="shipping-firstname" class="label label-firstname">
                                                <span>First Name</span>
                                                </label>
                                                <div class="flex items-center gap-4">
                                                    <input class="block w-full form-input grow renderer-text firstname" type="text" value="{{ old('firstname') }}" id="shipping-firstname" name="firstname"placeholder="First Name" />
                                                </div>
                                                @error('firstname')<div class="text-red-600">{{ $message }}</div>@enderror
                                                </div>
                                            </div>
                                            <div class="col-span-12 field-wrapper field-type-text field field-reserved field-lastname md&#x3A;col-span-12" wire:key="field-wrapper-shipping-address-lastname">
                                                <div class="w-full font-medium text-gray-700 required">
                                                <label for="shipping-lastname" class="label label-lastname">
                                                <span>
                                                Last Name </span>
                                                </label>
                                                <div class="flex items-center gap-4">
                                                    <input class="block w-full form-input grow renderer-text lastname" type="text" value="{{ old('lastname') }}" id="shipping-lastname" name="lastname" data-attribute-address="true" data-magewire-is-valid="1" data-validate="&#x7B;&quot;magewire&quot;&#x3A;true&#x7D;" data-autosave wire:loading.class="loading" wire:loading.attr="disabled" wire:target="lastname" wire:model.defer="address.lastname" placeholder="Last Name" />
                                                </div>
                                                @error('lastname')<div class="text-red-600">{{ $message }}</div>@enderror
                                                </div>
                                            </div>
                                            <div class="col-span-12 field-wrapper field-type-tel field field-reserved field-telephone md&#x3A;col-span-12" wire:key="field-wrapper-shipping-address-telephone">
                                                <div class="w-full font-medium text-gray-700 required">
                                                <label for="shipping-telephone" class="label label-telephone">
                                                <span>
                                                Phone Number </span>
                                                </label>
                                                    <div class="flex items-center gap-4">
                                                        <input class="block w-full form-input grow renderer-text telephone" type="tel" data-form="shipping" value="{{ old('telephone') }}" id="shipping-telephone" name="telephone" data-attribute-address="true" data-magewire-is-valid="1" data-validate="&#x7B;&quot;magewire&quot;&#x3A;true&#x7D;" data-autosave wire:loading.class="loading" wire:loading.attr="disabled" wire:target="telephone" wire:model.defer="address.telephone" placeholder="Phone Number" />
                                                        <div class="flex gap-2 items-center tooltip" x-data="{ tooltipVisible: false }" role="tooltip">
                                                            <div x-on:mouseenter.self="tooltipVisible = true" x-on:mouseleave.self="tooltipVisible = false" x-on:click.outside.away="tooltipVisible = false" class="relative hover:cursor-pointer">
                                                                <svg xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8 opacity-75 hover:opacity-100 mt-1 icon" width="24" height="24" role="img">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                                <title>question-mark-circle</title>
                                                                </svg>
                                                                <template x-if="tooltipVisible">
                                                                <div class="absolute -right-3 px-3 pt-3 w-80">
                                                                    <div class="shadow-lg px-3 py-2 bg-gray-500 relative text-white rounded-md">
                                                                        <div class="h-3 w-3 bg-gray-500 rotate-45 transform origin-bottom-left absolute right-3 -top-3"></div>
                                                                        For delivery questions. 
                                                                    </div>
                                                                </div>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @error('telephone')<div class="text-red-600">{{ $message }}</div>@enderror
                                                </div>
                                            </div>
                                            <div class="col-span-12 group group-street field-wrapper field-type-text field field-reserved field-street md&#x3A;col-span-12" wire:key="field-wrapper-shipping-address-street-0">
                                                <div class="w-full font-medium text-gray-700 required">
                                                <label for="shipping-street-0" class="label label-street">
                                                <span>
                                                Street Address </span>
                                                </label>
                                                <div class="space-y-2">
                                                    <div class="flex items-center gap-4">
                                                        <input class="block w-full form-input grow renderer-text street" type="text" data-form="shipping" value="{{ old('streetAddress') }}" name="streetAddress" placeholder="Street Address" />
                                                    </div>
                                                    @error('streetAddress')<div class="text-red-600">{{ $message }}</div>@enderror
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-span-12 field-wrapper field-type-text field field-reserved field-city md&#x3A;col-span-12" wire:key="field-wrapper-shipping-address-city">
                                                <div class="w-full font-medium text-gray-700 required">
                                                <label for="shipping-city" class="label label-city">
                                                <span>
                                                City </span>
                                                </label>
                                                <div class="flex items-center gap-4">
                                                    <input class="block w-full form-input grow renderer-text city" type="text" id="shipping-city" name="city" value="{{ old('city') }}" placeholder="City" />
                                                </div>
                                                @error('city')<div class="text-red-600">{{ $message }}</div>@enderror
                                                </div>
                                            </div>
                                            <div class="col-span-12 field-wrapper field-type-text field field-reserved field-postcode md&#x3A;col-span-12" wire:key="field-wrapper-shipping-address-postcode">
                                                <div class="w-full font-medium text-gray-700">
                                                <label for="shipping-postcode" class="label label-postcode">
                                                <span>
                                                Postcode </span>
                                                </label>
                                                <div class="flex items-center gap-4">
                                                    <input class="block w-full form-input grow renderer-text postcode" type="text" id="shipping-postcode" name="postcode" placeholder="Postcode" value="{{ request('postcode') }}" />
                                                </div>
                                                </div>
                                            </div>
                                            <div class="col-span-12 field-wrapper field-type-select field field-reserved field-country md&#x3A;col-span-12" wire:key="field-wrapper-shipping-address-country">
                                                <div class="block font-medium text-gray-700 required">
                                                <label for="shipping-country" class="label label-country">
                                                <span>
                                                Country </span>
                                                </label>
                                                <div class="flex items-center gap-4">
                                                <select class="block w-full form-input renderer-select country" type="select" data-form="shipping" data-attribute="country" data-order="15" data-level="0" required autocomplete="country" id="shipping-country" name="country" data-attribute-address="true" data-magewire-is-valid="1" data-validate="&#x7B;&quot;magewire&quot;&#x3A;true&#x7D;" wire:loading.class="loading" wire:loading.attr="disabled">
                                                    <option disabled selected>--Select a country--</option>
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country['code'] }}" <?php if(request('country_id') === $country['code']){ echo 'selected';} ?>>{{ $country['name'] }}</option>
                                                    @endforeach
                                                </select>
                                                </div>
                                                @error('country')<div class="text-red-600">{{ $message }}</div>@enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="xs:mt-0 mt-4 xs:w-auto w-full">
                                            <button type="submit" class="btn btn-primary checkout justify-center before:absolute before:bg-white before:duration-1000 before:ease before:h-14 before:opacity-10 before:right-0 before:rotate-6 before:top-0 before:translate-x-12 before:w-6 hover:before:-translate-x-[35rem] relative transition-all hover:bg-kes-green hover:border-kes-green hover:text-white overflow-hidden" id="checkout-link-button">
                                                Submit
                                            </button>
                                        </div>
                                    </form>
                                </section>
                            </div>
                            <div class="column column-right w-full flex flex-col gap-8 lg:w-1/3">
                                <section id="quote-summary">
                                    <section class="price-summary">
                                    <header class="section-title border-b border-gray-400 pb-1 mb-6">
                                        <h2 class="text-gray-800 text-xl font-medium">
                                            Order Summary 
                                        </h2>
                                    </header>
                                    @php
                                        $subTotal = 0;
                                    @endphp
                                    <div wire:id="price-summary.cart-items" wire:initial-data="{&quot;fingerprint&quot;:{&quot;id&quot;:&quot;price-summary.cart-items&quot;,&quot;name&quot;:&quot;price-summary.cart-items&quot;,&quot;locale&quot;:&quot;en_GB&quot;,&quot;path&quot;:&quot;\/&quot;,&quot;method&quot;:&quot;GET&quot;,&quot;resolver&quot;:&quot;hyva_checkout&quot;,&quot;handle&quot;:&quot;hyva_checkout_index_index&quot;,&quot;type&quot;:&quot;default&quot;,&quot;v&quot;:&quot;acj&quot;},&quot;effects&quot;:{&quot;listeners&quot;:[&quot;refresh&quot;]},&quot;serverMemo&quot;:{&quot;data&quot;:{&quot;expand&quot;:false},&quot;htmlHash&quot;:&quot;4a62a3fd&quot;,&quot;children&quot;:[],&quot;checksum&quot;:&quot;889c71e330697191cd4e7a8b8d0f6acbefb460f17130cfb08e5a2e0a6110a4dc&quot;}}">
                                        <div class="cart-items mb-6">
                                            <div class="flex flex-col gap-4">
                                                <div class="relative grid gap-6 sm:gap-8">
                                                @forelse($cartContent as $cart)
                                                    @php
                                                        $subTotal += ($cart->quantity * $cart->price);
                                                    @endphp
                                                    <div class="flex gap-4 ">
                                                        <div class="flex-none relative">
                                                            <img src="{{ $cart->attributes['image'] }}" width="75" height="75" alt="{{ $cart->name }}" loading="lazy" />
                                                        </div>
                                                        <div class="flex-grow space-y-2">
                                                            <div class="xs:flex gap-4 md:gap-0 justify-between">
                                                                <div class="product-title">
                                                                <p>{{ $cart->name }}</p>
                                                                <p class="mb-0 text-gray-20">Qty: {{ $cart->quantity }}</p>
                                                                </div>
                                                                
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                @endforelse
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div wire:id="price-summary.total-segments" wire:initial-data="{&quot;fingerprint&quot;:{&quot;id&quot;:&quot;price-summary.total-segments&quot;,&quot;name&quot;:&quot;price-summary.total-segments&quot;,&quot;locale&quot;:&quot;en_GB&quot;,&quot;path&quot;:&quot;\/&quot;,&quot;method&quot;:&quot;GET&quot;,&quot;resolver&quot;:&quot;hyva_checkout&quot;,&quot;handle&quot;:&quot;hyva_checkout_index_index&quot;,&quot;type&quot;:&quot;default&quot;,&quot;v&quot;:&quot;acj&quot;},&quot;effects&quot;:{&quot;listeners&quot;:[&quot;shipping_method_selected&quot;,&quot;payment_method_selected&quot;,&quot;coupon_code_applied&quot;,&quot;coupon_code_revoked&quot;,&quot;shipping_address_saved&quot;,&quot;shipping_address_activated&quot;,&quot;billing_address_saved&quot;,&quot;billing_address_activated&quot;,&quot;refresh&quot;]},&quot;serverMemo&quot;:{&quot;data&quot;:[],&quot;htmlHash&quot;:&quot;398f6691&quot;,&quot;children&quot;:[],&quot;checksum&quot;:&quot;b3dbbc8065fd2f594b9ba4b3ca3e0a66fcebff74ba08df8ac2d2a6c8dfe0d0a6&quot;}}" class="total-segments leading-loose space-y-1">
                                        <div class="item subtotal">
                                            <div class="flex gap-4 justify-between md:gap-0">
                                                <span class="label font-bold">
                                                Subtotal </span>
                                                <span class="value">
                                                ₵{{ $subTotal }} </span>
                                            </div>
                                        </div>
                                        <div class="item shipping">
                                            <div class="flex gap-4 justify-between md:gap-0">
                                                <span class="label">
                                                Delivery </span>
                                                <span class="value">
                                                Shall be applicable </span>
                                            </div>
                                        </div>
                                        <div class="item tax">
                                            <div class="flex gap-4 justify-between md:gap-0">
                                                <span class="label">
                                                Tax </span>
                                                <span class="value">
                                                Shall be applicable </span>
                                            </div>
                                        </div>
                                        <div class="item grand_total">
                                            <div class="flex gap-4 justify-between md:gap-0">
                                                <span class="label uppercase font-semibold">
                                                Total To Pay </span>
                                                <span class="value text-right font-semibold">
                                                ₵{{ $subTotal }} </span>
                                            </div>
                                        </div>
                                    </div>
                                    </section>
                                </section>
                                
                                <!-- <div data-content-type="row" data-appearance="full-bleed" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src data-element="main" data-pb-style="AE0EQTY">
                                    <div data-content-type="html" data-appearance="default" data-element="main" data-decoded="true">
                                        <div class="cmsb114-grid cmsb114-gap-6 cmsb114-grid-cols-3 cmsb114-max-w-md cmsb114-mx-auto">
                                            <div class="cmsb114-m-auto">
                                                <img src="https://www.keslighting.co.uk/media/wysiwyg/CLOUDFLARE-STAMP-KES-logo.png" alt="CLOUDFLARE-STAMP" />
                                            </div>
                                            <div class="cmsb114-m-auto">
                                                <img src="https://www.keslighting.co.uk/media/wysiwyg/39YEAR-STAMP-KES-DarkBlueLightGrey-250x250_1.png" alt="38YEAR-STAMP" />
                                            </div>
                                            <div class="cmsb114-m-auto">
                                                <img src="https://www.keslighting.co.uk/media/wysiwyg/GDPR-STAMP-KES-logo.png" alt="GDPR-STAMP-KES" />
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
            @include('public.no-item', ['title' => 'No items present in the cart'])
        @endif
    </main>
@endsection
