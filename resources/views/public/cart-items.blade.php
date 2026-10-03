@extends('public.layout.layout')

@section('content')
<main id="maincontent" class="page-main">
<div id="contentarea" tabindex="-1"></div>
    <!-- <div class="page messages"> -->
    <!-- <div class="container&#x20;flex&#x20;flex-col&#x20;md&#x3A;flex-row&#x20;flex-wrap&#x20;font-semibold&#x20;mb-5&#x20;md&#x3A;mb-7&#x20;mt-8&#x20;md&#x3A;mt-8&#x20;lg&#x3A;mt-10">
        <h1 class="page-title title-font mb-0">
            <span class="base" data-ui-id="page-title-wrapper">Shopping Basket</span> 
        </h1>
    </div> -->
    @if(count($cartContent) > 0)
    <div class="columns">
        <div class="column main">
            
            <script>
                function mutateItemQty(itemid, event) {
                    const target = document.getElementById(itemid);
                    let value = Number(target.value);
                    if (event === 'decrement') {
                        if (value < 0) {
                            value = 1;
                        } else {
                            value--;
                        }
                    } else {
                        if (value < 0) {
                            value = 1;
                        } else {
                            value++;
                        }
                    }
                    target.value = value;
                    submitQtyForm(itemid, value);
                }
                function submitQtyForm(itemid, quantity) {
                    if (quantity < 1) {
                        return;
                    }
                    // Create a FormData object
                    const formData = new FormData();
                    formData.append('product_id', itemid);
                    formData.append('qty', quantity);

                    fetch('/update-cart', {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Cart updated successfully:', data);
                        } else {
                            console.error('Error updating cart:', data.message);
                        }
                    })
                    .catch((error) => {
                        console.error('Error updating cart:', error);
                    });
                }
                function removeItemFromCart(productId) {
                    fetch('/remove-from-cart', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ product_id: productId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            console.error('Error removing product from cart:', data.message);
                        }
                    })
                    .catch((error) => {
                        console.error('Error:', error);
                    });
                }
            </script>
                <div wire:id="hyva-checkout-main" wire:initial-data="{&quot;fingerprint&quot;:{&quot;id&quot;:&quot;hyva-checkout-main&quot;,&quot;name&quot;:&quot;hyva-checkout-main&quot;,&quot;locale&quot;:&quot;en_GB&quot;,&quot;path&quot;:&quot;\/&quot;,&quot;method&quot;:&quot;GET&quot;,&quot;resolver&quot;:&quot;hyva_checkout&quot;,&quot;handle&quot;:&quot;hyva_checkout_index_index&quot;,&quot;type&quot;:&quot;hyva-checkout-main&quot;,&quot;v&quot;:&quot;acj&quot;},&quot;effects&quot;:{&quot;loader&quot;:{&quot;navigateToStep&quot;:true,&quot;placeOrder&quot;:[&quot;Processing your order&quot;]},&quot;listeners&quot;:[&quot;refresh&quot;]},&quot;serverMemo&quot;:{&quot;data&quot;:{&quot;config&quot;:{&quot;step_history&quot;:{&quot;current&quot;:{&quot;name&quot;:&quot;shipping&quot;,&quot;label&quot;:&quot;Delivery&quot;,&quot;route&quot;:&quot;shipping&quot;,&quot;position&quot;:1},&quot;previous&quot;:{&quot;name&quot;:&quot;login&quot;,&quot;label&quot;:&quot;Login&quot;,&quot;route&quot;:&quot;login&quot;,&quot;position&quot;:0}},&quot;messenger_api&quot;:{&quot;querySelectorClass&quot;:&quot;component-messenger&quot;}}},&quot;evaluation&quot;:{&quot;checkout.shipping-details.address-form&quot;:{&quot;arguments&quot;:{&quot;event&quot;:&quot;validate-address-form&quot;,&quot;detail&quot;:{&quot;form&quot;:&quot;shipping&quot;,&quot;event&quot;:&quot;shipping:details:error&quot;,&quot;component&quot;:{&quot;id&quot;:&quot;checkout.shipping-details.address-form&quot;},&quot;message&quot;:{&quot;text&quot;:&quot;\&quot;firstname\&quot; is required. Enter and try again.&quot;,&quot;type&quot;:&quot;error&quot;,&quot;duration&quot;:3600000}},&quot;dispatch&quot;:false,&quot;blocking&quot;:{&quot;result&quot;:false,&quot;cause&quot;:null}},&quot;dispatch&quot;:false,&quot;result&quot;:false,&quot;type&quot;:&quot;event&quot;,&quot;id&quot;:&quot;checkout.shipping-details.address-form&quot;,&quot;blocking&quot;:false,&quot;hash&quot;:&quot;8b566fa9c4d4f512e4dba2ac0948032fc5724160&quot;},&quot;checkout.shipping.methods&quot;:{&quot;arguments&quot;:{&quot;event&quot;:&quot;shipping:method:success&quot;,&quot;detail&quot;:{&quot;component&quot;:{&quot;id&quot;:&quot;checkout.shipping.methods&quot;}},&quot;dispatch&quot;:true},&quot;dispatch&quot;:true,&quot;result&quot;:true,&quot;type&quot;:&quot;event&quot;,&quot;id&quot;:&quot;checkout.shipping.methods&quot;,&quot;blocking&quot;:false,&quot;hash&quot;:&quot;ff46e9aa7a9ed8288a55c2c3bd7e8c4966d62be1&quot;},&quot;hyva-checkout-main&quot;:{&quot;arguments&quot;:{&quot;event&quot;:&quot;evaluation:event:success&quot;,&quot;detail&quot;:{&quot;component&quot;:{&quot;id&quot;:&quot;hyva-checkout-main&quot;}},&quot;dispatch&quot;:true},&quot;dispatch&quot;:true,&quot;result&quot;:true,&quot;type&quot;:&quot;event&quot;,&quot;id&quot;:&quot;hyva-checkout-main&quot;,&quot;blocking&quot;:false,&quot;hash&quot;:&quot;faa8acde1e46db1d23fb3fc4d18f3f77c1905f54&quot;}},&quot;htmlHash&quot;:&quot;cefc7322&quot;,&quot;children&quot;:{&quot;checkout.shipping-details&quot;:{&quot;id&quot;:&quot;checkout.shipping-details&quot;,&quot;tag&quot;:&quot;div&quot;},&quot;checkout.shipping.methods&quot;:{&quot;id&quot;:&quot;checkout.shipping.methods&quot;,&quot;tag&quot;:&quot;div&quot;},&quot;price-summary.cart-items&quot;:{&quot;id&quot;:&quot;price-summary.cart-items&quot;,&quot;tag&quot;:&quot;div&quot;},&quot;price-summary.total-segments&quot;:{&quot;id&quot;:&quot;price-summary.total-segments&quot;,&quot;tag&quot;:&quot;div&quot;}},&quot;checksum&quot;:&quot;a06ad117adda7e8759d7d11c032d8f9fac40ef53da2c56b50e6ed3516e54c04e&quot;}}" id="hyva-checkout-main" class="step-shipping step-layout-2columns" role="main">
                    <nav id="breadcrumbs" class="flex xl:mt-12 lg:mt-10 md:mt-8 mt-6 mx-auto">
                        <ul class="breadcrumbs items-center space-x-2 xs:space-x-4 flex">
                            <li class="step space-x-2 xs:space-x-4 inline-flex items-center text-kes-blue text-center">
                                <button type="button" class="item completed active" onclick="hyvaCheckout.navigation.stepTo('login', false)">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32" class="mx-auto mb-3" aria-hidden="true">
                                    <path d="M7 4H1V2h6c1.1 0 2-.9 2-2h12c0 1.1.9 2 2 2h6v2h-6c-1.1 0-2 .9-2 2v4h-1V8h-8V4H7zM0 4h1.2l2.1 9h21.4l1.2-3H4.2l-.9-3h21.4l1.1-3H3.8L0 4zm0 2h3l1.2 3h-3l-.9-3zm2.3 6h20.3l1.2 3H3.1l1.2-3zM7 16c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3zm0 4c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1zm15 0c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3zm0 4c-.6 0-1-.4-1-1s.4-1 1-1 1 .4 1 1-.4 1-1 1z"/>
                                </svg>

                                </button>
                                <span class="step-number item completed active">Shopping Cart</span>
                            </li>
                            
                            <li class="step space-x-2 xs:space-x-4 inline-flex items-center text-kes-blue text-center">
                            <span class="w-4 xs:w-10 h-0.5 bg-kes-lightgray md:w-20 bg-kes-blue"></span>
                                <button type="button" class="item completed" onclick="hyvaCheckout.navigation.stepTo('login', false)">
                                    <svg xmlns="https://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 31.561 34.91" class="mx-auto mb-3" aria-hidden="true">
                                    <g id="Group_793" data-name="Group 793" transform="translate(-55 -33.667)">
                                        <path id="Path_271" data-name="Path 271" d="M82.5,33.667H59.056A4.065,4.065,0,0,0,55,37.724v26.8a4.065,4.065,0,0,0,4.057,4.056H82.5a4.065,4.065,0,0,0,4.056-4.057v-26.8A4.065,4.065,0,0,0,82.5,33.667Zm2.644,30.855a2.651,2.651,0,0,1-2.642,2.642H59.056a2.651,2.651,0,0,1-2.643-2.642v-26.8a2.651,2.651,0,0,1,2.642-2.643H82.5a2.651,2.651,0,0,1,2.643,2.642Z" />
                                        <path id="Path_272" data-name="Path 272" d="M183.707,101.812a.707.707,0,0,0,.707-.707,4.318,4.318,0,1,1,8.636,0,.707.707,0,1,0,1.413,0,5.742,5.742,0,0,0-2.44-4.691l-.569-.4.52-.461a4.892,4.892,0,1,0-6.487,0l.52.461-.568.4A5.742,5.742,0,0,0,183,101.105.707.707,0,0,0,183.707,101.812Zm1.544-9.918a3.481,3.481,0,1,1,3.481,3.481A3.484,3.484,0,0,1,185.251,91.894Z" transform="translate(-117.951 -49.146)" />
                                        <path id="Path_273" data-name="Path 273" d="M166.78,321.667H151.707a.707.707,0,0,0,0,1.413H166.78a.707.707,0,1,0,0-1.413Z" transform="translate(-88.463 -265.389)" />
                                        <path id="Path_274" data-name="Path 274" d="M166.78,385.667H151.707a.707.707,0,0,0,0,1.413H166.78a.707.707,0,0,0,0-1.413Z" transform="translate(-88.463 -324.365)" />
                                    </g>
                                    </svg>
                                </button>
                                <span class="step-number item completed">Checkout Details</span>
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
                </div>
                <br>
            <div x-data="initCartForm()" class="cart-form clearfix container xl:pb-24 lg:pb-20 md:pb-16 sm:pb-14 pb-12" @private-content-loaded.window="checkCartShouldUpdate($event.detail.data)" @storage.window="onStorageChange($event)">
                <div class="w-full flex flex-wrap lg:gap-0 gap-7 items-start">
                    <div class="w-full lg:w-[70%] xl:w-3/4 lg:order-1 lg:pr-8 top-4 lg:sticky">
                    <!-- <div class="w-full bg-kes-green bg-opacity-20 px-3 py-2 mb-4 md:px-6">
                        <div class="font-semibold text-[15px] leading-none text-kes-darkgreen flex justify-center items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 47.245 32.452" class="fill-kes-darkgreen" aria-hidden="true">
                                <g id="Group_477_2" data-name="Group 477" transform="translate(0)">
                                <path id="Path_137_2" data-name="Path 137" d="M303.1,210.6l-3.184-4.1.094-.048-4.344-8.641a1.044,1.044,0,0,0-.929-.576h-6.96v-1.994a1.048,1.048,0,0,0-1.043-1.043H261.419a1.044,1.044,0,0,0,0,2.088h24.274v11.105a1.046,1.046,0,0,0,1.043,1.043h12.046l2.45,3.163v8.472h-3.707l-.089-.36a5.6,5.6,0,0,0-10.866,0l-.091.36h-9.712l-.089-.36a5.6,5.6,0,0,0-10.866,0l-.091.36h-4.3a1.043,1.043,0,1,0,0,2.086h4.319l.094.355a5.6,5.6,0,0,0,10.819,0l.094-.353H286.5l.094.355a5.6,5.6,0,0,0,10.819,0l.094-.353h4.776a1.044,1.044,0,0,0,1.042-1.043v-9.872A1.041,1.041,0,0,0,303.1,210.6Zm-5.478-4.25H287.78v-7.029h6.313Zm-2.11,14.714A3.514,3.514,0,1,1,292,217.552,3.525,3.525,0,0,1,295.515,221.066Zm-20.76,0a3.512,3.512,0,1,1-3.512-3.515A3.522,3.522,0,0,1,274.755,221.066Z" transform="translate(-256.076 -194.2)" />
                                <path id="Path_138_2" data-name="Path 138" d="M272.487,203.574h0a1.047,1.047,0,0,0-1.043-1.043H258.483a1.044,1.044,0,0,0,0,2.088h12.961A1.048,1.048,0,0,0,272.487,203.574Z" transform="translate(-257.441 -190.327)" />
                                <path id="Path_139_2" data-name="Path 139" d="M268.206,207.65a1.044,1.044,0,0,0-1.043-1.043h-6.987a1.043,1.043,0,0,0,0,2.086h6.987A1.046,1.046,0,0,0,268.206,207.65Z" transform="translate(-256.655 -188.43)" />
                                <path id="Path_140_2" data-name="Path 140" d="M260.175,200.537h12.963a1.043,1.043,0,1,0,0-2.086h-12.96a1.043,1.043,0,0,0,0,2.086Z" transform="translate(-256.654 -192.223)" />
                                </g>
                            </svg>
                            <p class="ml-2 mb-0 leading-5">
                                <div data-content-type="html" data-appearance="default" data-element="main" data-decoded="true">Free UK delivery above £99. 30 days hassle free returns</div>
                            </p>
                        </div>
                    </div> -->
                        <style>
                            .item-info td{
                                padding: 15px 15px 30px !important;
                            }
                        </style>
                        @php
                            $subTotal = 0;
                        @endphp
                        @forelse($cartContent as $cart)
                            @php
                                $subTotal += ($cart->quantity * $cart->price);
                            @endphp
                            <div x-data="{}" method="post" id="form-validate" class="form form-cart w-full float-left">
                                <div class="cart table-wrapper">
                                    <table id="shopping-cart-table" class="cart items data table w-full table-row-items">
                                        <caption class="table-caption sr-only">
                                            Shopping Bag Items 
                                        </caption>
                                        <thead class="hidden lg:table-header-group">
                                            <tr class="text-right">
                                                <th class="col item text-left pt-4 px-4 pb-2" scope="col">Item</th>
                                                <th class="col qty pt-4 px-4 pb-2" scope="col">Qty</th>
                                                <th class="col price pt-4 px-4 pb-2" scope="col">Price</th>
                                                <th class="col subtotal pt-4 px-4 pb-2" scope="col">Subtotal</th>
                                                <th class="col remove pt-4 px-4 pb-2" scope="col">Remove</th>
                                            </tr>
                                        </thead>
                                        
                                        <tbody class="cart item bg-white even:bg-container-darker">
                                            <tr class="item-info align-top text-left lg:text-right flex flex-wrap lg:table-row">
                                                <td data-th="Item" class="col item px-4 pt-4 lg:py-5 lg:px-5 flex flex-wrap xs:gap-7 gap-4 text-left w-full xs:flex-nowrap lg:w-auto">
                                                    <a href="{{ route('product.detail', $cart->attributes['slug']) }}" title="{{ $cart->name }}" tabindex="-1" class="product-item-photo shrink-0">
                                                        <img class="photo image product-image-photo" src="{{ $cart->attributes['image'] }}" loading="lazy" width="110" height="110" alt="{{ $cart->name }}" />
                                                    </a>
                                                    <div class="product-item-details grow">
                                                        <strong class="product-item-name break-all">
                                                            <a href="{{ route('product.detail', $cart->attributes['slug']) }}">{{ $cart->name }}</a>
                                                        </strong>
                                                    </div>
                                                </td>
                                                <td class="col qty px-4 pt-4 lg:py-5 lg:px-5 block w-full xs:w-1/3 lg:w-auto lg:table-cell">
                                                    <span class="lg:hidden font-bold block xs:text-center">Qty</span>
                                                    <div class="xs:m-auto lg:m-0 border border-gray-30 flex w-[100px] h-8">
                                                        <label class="label sr-only" for="{{ $cart->id }}">Quantity</label>
                                                        <span class="bg-gray-40 cursor-pointer flex flex-1 items-center justify-center px-1 select-none" @click="mutateItemQty({{ $cart->id }}, 'decrement')">
                                                            <!-- SVG for decrement -->
                                                        </span>
                                                        <input id="{{ $cart->id }}" name="cart" data-id="{{ $cart->id }}" value="{{ $cart->quantity }}" type="number" size="4" step="any" title="Qty" class="cart-qty h-full xl:h-auto z-[1] qty [-moz-appearance:textfield] [&::-webkit-inner-spin-button]:hidden appearance-none text-center border-1 border-t-0 border-b-0 border-gray-30 text-blue-10 px-1 text-base flex-1 w-12" required="required" min="1" x-on:change.debounce.200="submitQtyForm($event.target.value)" data-role="cart-item-qty" />
                                                        <span class="bg-gray-40 cursor-pointer flex flex-1 items-center justify-center px-1 select-none" @click="mutateItemQty({{ $cart->id }}, 'increment')">
                                                            <!-- SVG for increment -->
                                                        </span>
                                                    </div>
                                                </td>
                                                <td class="col price px-4 pt-4 lg:py-5 lg:px-5 block w-full xs:w-1/3 lg:w-auto lg:table-cell">
                                                    <span class="font-bold">₵{{ number_format($cart->price, 2) }}</span>
                                                </td>
                                                <td class="col subtotal px-4 pt-4 lg:py-5 lg:px-5 block w-full xs:w-1/3 lg:w-auto lg:table-cell">
                                                    <span class="font-bold">₵{{ number_format($cart->quantity * $cart->price, 2) }}</span>
                                                </td>
                                                <td class="col remove px-4 pt-4 lg:py-5 lg:px-5 block w-full xs:w-1/3 lg:w-auto lg:table-cell">
                                                    <div class="xs:m-auto lg:m-0 flex w-[100px] h-8">
                                                        <button class="flex action action-delete gap-2.5 items-center group/icone" title="Remove" @click.prevent="removeItemFromCart('{{ $cart->id }}')" type="button">
                                                            <!-- SVG for remove -->
                                                            <span class="group-hover/icone:text-bronze-10">Remove</span>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr class="item-actions lg:hidden">
                                                <td colspan="5">
                                                    <div class="flex justify-end gap-4 px-4 py-4 lg:py-5 lg:px-5 items-center">
                                                        <button class="flex action action-delete gap-2.5 items-center group/icone" title="Remove" x-data="{}" @click.prevent="removeItemFromCart('{{ $cart->id }}')" type="button">
                                                            <!-- SVG for remove -->
                                                            <span class="group-hover/icone:text-bronze-10">Remove</span>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @empty
                            <p>Your cart is empty.</p>
                        @endforelse

                    <div style="padding-top: 20px" class="cart-footer flex w-full flex-wrap justify-between">
                        <a href="/" class="border-2 btn btn-secondary lg:px-3 xl:px-5 xs:w-auto w-full justify-center before:absolute before:bg-white before:duration-1000 before:ease before:h-14 before:opacity-10 before:right-0 before:rotate-6 before:top-0 before:translate-x-12 before:w-6 hover:before:-translate-x-[35rem] relative transition-all hover:bg-kes-green hover:border-kes-green hover:text-white overflow-hidden">
                        Continue Shopping </a>
                        <div class="xl:gap-5 gap-2.5 hidden items-center justify-between lg:flex lg:mb-0 share-cart">
                            <p class="flex font-semibold gap-2 xl:gap-3 xl:text-[17px] text-[15px]">
                                Click on <b>Checkout</b> to proceed 
                            </p>
                            <a href="" class="action overflow-hidden btn btn-primary border-2 lg:px-3 xl:px-5 before:absolute before:bg-white before:duration-1000 before:ease before:h-14 before:opacity-10 before:right-0 before:rotate-6 before:top-0 before:translate-x-12 before:w-6 hover:before:-translate-x-[35rem] relative transition-all hover:bg-kes-green hover:border-kes-green hover:text-white" title="Share Basket">
                                <span>Update Cart</span>
                            </a>
                            <a href="{{ route('checkout') }}" class="action overflow-hidden btn btn-primary border-2 lg:px-3 xl:px-5 before:absolute before:bg-white before:duration-1000 before:ease before:h-14 before:opacity-10 before:right-0 before:rotate-6 before:top-0 before:translate-x-12 before:w-6 hover:before:-translate-x-[35rem] relative transition-all hover:bg-kes-green hover:border-kes-green hover:text-white" title="Share Basket">
                                <span>Checkout</span>
                            </a>
                        </div>
                        <script>
                            function openShareCartPopup() {
                                var miniCartCloseBtn = document.getElementById("btn-minicart-close");
                                if (miniCartCloseBtn && miniCartCloseBtn.length) {
                                    miniCartCloseBtn.click();
                                }
                                        document.querySelector(".top-actions li:first-child").click();
                                openShareCartForm();
                            }
                        </script>
                        <div class="lg:hidden xs:mt-0 mt-4 xs:w-auto w-full">
                            <a href="{{ route('checkout') }}" class="btn btn-primary checkout justify-center before:absolute before:bg-white before:duration-1000 before:ease before:h-14 before:opacity-10 before:right-0 before:rotate-6 before:top-0 before:translate-x-12 before:w-6 hover:before:-translate-x-[35rem] relative transition-all hover:bg-kes-green hover:border-kes-green hover:text-white overflow-hidden" id="checkout-link-button">
                            Go to Checkout </a>
                        </div>
                    </div>
                    
                    </div>
                    <div class="w-full lg:w-[30%] xl:w-1/4 lg:order-2 top-4 lg:sticky">
                    <div class="cart-summary">
                        <script>
                            function initShippingEstimation(){
                                return {
                                    toggleEstimateShipping() {
                                        this.showEstimateShipping = !this.showEstimateShipping;
                            
                                        hyva.getBrowserStorage().setItem('hyva.showEstimateShipping', this.showEstimateShipping);
                            
                                    }
                                }
                            }
                        </script>
                        <p class="flex font-semibold gap-4 items-center justify-center lg:mt-0 mt-5 mb-8 text-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" id="Group_467" data-name="Group 467" width="30" height="35" viewBox="0 0 34.063 39.961" role="img">
                                <path id="Path_131" data-name="Path 131" d="M80.829,230.419v13.806c0,15.576,16.6,19.857,16.77,19.9l.262.064.263-.064c.167-.041,16.768-4.321,16.768-19.9V230.419L97.86,224.226Zm31.866,13.806c0,12.8-12.272,16.96-14.732,17.664l-.1.028-.1-.028c-2.461-.73-14.741-5.012-14.741-17.664V231.957l14.834-5.392,14.834,5.392Z" transform="translate(-80.829 -224.226)" fill="#c19b5b" />
                                <path id="Path_132" data-name="Path 132" d="M87.964,239.793l-1.554,1.554,6.323,6.323,12.117-12.118L103.3,234,92.734,244.562Z" transform="translate(-78.6 -220.323)" fill="#c19b5b" />
                                <title>warranty</title>
                            </svg>
                            100% Secure Shopping
                        </p>
                        <div id="block-shipping" class="flex flex-col pb-3 my-2 border-b-4 border-gray-30 estimate-shipping-form" x-data="initShippingEstimation()" @private-content-loaded.window="receiveCustomerData($event.detail.data)">
                            <div class="title" id="block-shipping-title">
                                <button class="flex justify-between w-full font-semibold text-xl whitespace-nowrap cursor-pointer select-none hover:text-bronze-10" id="shipping-estimate-toggle" type="button" :aria-expanded="showEstimateShipping">
                                Address Details 
                                <span :class="{ 'rotate-180' : showEstimateShipping}" class="block transform rotate-180">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6" width="25" height="25" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </span>
                                </button>
                            </div>
                            <form action="{{ route('checkout') }}" id="shipping-zip-form" method="GET">
                                @csrf
                                <fieldset class="fieldset estimate" aria-labelledby="block-shipping-title">
                                    <p class="field note mb-3">
                                        Enter your delivery address postcode to see the available rates. 
                                    </p>
                                    <div class="field" name="shippingAddress.country_id">
                                        <label class="label mb-3">
                                            Country
                                            <select class="form-select w-full mt-1" name="country_id" aria-invalid="false" @change.debounce="setCountry($event.target.value)">
                                            @foreach ($countries as $country)
                                                    <option value="{{ $country['code'] }}">{{ $country['name'] }}</option>
                                                @endforeach
                                            </select>
                                        </label>
                                    </div>
                                    
                                    <div class="field" name="shippingAddress.postcode">
                                        <label class="label">
                                            Postcode
                                        <input class="form-input w-full mt-1" type="text" name="postcode" />
                                        </label>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="flex pb-3 my-3 border-b text-md lg:text-[17px] md:grid md:grid-cols-2 md:w-full border-gray-30">
                                <div class="w-7/12 text-left md:w-auto">Subtotal</div>
                                <div class="w-5/12 text-right md:w-auto">₵{{ number_format($subTotal, 2) }}</div>
                            </div>
                            <div class="flex pb-3 my-3 border-b text-md lg:text-[17px] md:grid md:grid-cols-2 md:w-full border-gray-30">
                                <div class="w-7/12 text-left md:w-auto">Tax and Delivery</div>
                                <div class="w-5/12 text-right md:w-auto">Shall be applicable</div>
                            </div>
                        
                        <ul class="checkout methods items checkout-methods-items">
                            <li class="item">
                                <a href="javascript:void(0)" class="overflow-hidden btn btn-primary lg:text-lg font-bold py-4 px-10 mt-6 checkout justify-center text-center before:absolute before:bg-white before:duration-1000 before:ease before:h-14 before:opacity-10 before:right-0 before:rotate-6 before:top-0 before:translate-x-12 before:w-6 hover:before:-translate-x-[35rem] relative transition-all hover:bg-kes-green hover:border-kes-green hover:text-white" id="checkout-link-button"><button type="submit" >
                                Go to Checkout </button></a>
                                </form>
                                <div class="flex items-center justify-center mt-6 cart-payment-icons">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Layer_1" data-name="Layer 1" viewBox="0 0 38 24" class="sm:h-8 sm:w-12 h-6 w-10 mr-2" width="24" height="24" role="img">
                                    <defs>
                                        <style>.vis-1{fill:none;}.vis-2{clip-path:url(#clip-path);}.vis-3{isolation:isolate;opacity:0.07;}.vis-4{fill:#fff;}.vis-5{fill:#142688;}</style>
                                        <clipPath id="clip-path">
                                            <rect class="vis-1" x="-328.18" width="374" height="24" />
                                        </clipPath>
                                    </defs>
                                    <g class="vis-2">
                                        <path class="vis-3" d="M35,0H3A3.06,3.06,0,0,0,1.84.21,3,3,0,0,0,.21,1.84,3.06,3.06,0,0,0,0,3V21a3,3,0,0,0,3,3H35a2.93,2.93,0,0,0,3-3V3a3,3,0,0,0-3-3Z" />
                                        <path class="vis-4" d="M35,1a2,2,0,0,1,2,2V21a2,2,0,0,1-2,2H3a2,2,0,0,1-2-2V3A2,2,0,0,1,3,1Z" />
                                        <path class="vis-5" d="M28.3,10.12H28a13.06,13.06,0,0,0-1,3h1.9A21.82,21.82,0,0,0,28.3,10.12ZM31.2,16H29.5c-.1,0-.1,0-.2-.1l-.2-.9-.1-.2H26.6c-.1,0-.2,0-.2.2l-.3.9a.11.11,0,0,1,0,.07A.09.09,0,0,1,26,16H23.9l.2-.5L27,8.72c0-.5.3-.7.8-.7h1.5c.1,0,.2,0,.2.2l1.4,6.5a4.45,4.45,0,0,1,.2,1.1C31.2,15.92,31.2,15.92,31.2,16Zm-13.4-.3.4-1.8a.3.3,0,0,1,.2.1,4,4,0,0,0,2.1.4,1.94,1.94,0,0,0,.7-.2c.5-.2.5-.7.1-1.1a5.1,5.1,0,0,0-.8-.5,4.37,4.37,0,0,1-1.1-.7,2.12,2.12,0,0,1-.55-.68,2.09,2.09,0,0,1-.21-.85,2,2,0,0,1,.66-1.57c.6-.4.9-.8,1.7-.8a12.87,12.87,0,0,1,3.1.2h.1a11.33,11.33,0,0,1-.4,1.7,4.13,4.13,0,0,0-1.5-.4,2.74,2.74,0,0,0-.9.1.54.54,0,0,0-.22.05.65.65,0,0,0-.18.15.36.36,0,0,0-.11.16.41.41,0,0,0,0,.19.43.43,0,0,0,0,.19.42.42,0,0,0,.11.16l.5.4a11.72,11.72,0,0,1,1.1.6,2.18,2.18,0,0,1,.7.58,2.24,2.24,0,0,1,.4.82,2.17,2.17,0,0,1-.9,2.3,1.87,1.87,0,0,1-.63.45,1.74,1.74,0,0,1-.77.15,11.76,11.76,0,0,1-3.4-.2C17.9,15.82,17.9,15.82,17.8,15.72Zm-3.5.3a3.78,3.78,0,0,1,.2-1c.5-2.2,1-4.5,1.4-6.7.1-.2.1-.3.3-.3H18a32,32,0,0,1-.7,3.2c-.3,1.5-.6,3-1,4.5,0,.2-.1.2-.3.2ZM5,8.22c0-.1.2-.2.3-.2H8.7a1,1,0,0,1,.65.21,1,1,0,0,1,.35.59l.9,4.4c0,.1,0,.1.1.2a.1.1,0,0,1,.1-.1l2.1-5.1c-.1-.1,0-.2.1-.2h2.1c0,.1,0,.1-.1.2l-3.1,7.3c-.1.2-.1.3-.2.4s-.3,0-.5,0H9.7c-.1,0-.2,0-.2-.2L7.9,9.52a2,2,0,0,0-.9-.6,6.67,6.67,0,0,0-1.9-.5Z" />
                                    </g>
                                    <title>visa</title>
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Layer_1" data-name="Layer 1" viewBox="0 0 38 24" class="sm:h-8 sm:w-12 h-6 w-10 mr-2" width="24" height="24" role="img">
                                    <defs>
                                        <style>.msc-1{fill:none;}.msc-2{clip-path:url(#clip-path_2);}.msc-3{isolation:isolate;opacity:0.07;}.msc-4{fill:#fff;}.msc-5{fill:#eb001b;}.msc-6{fill:#f79e1b;}.msc-7{fill:#ff5f00;}</style>
                                        <clipPath id="clip-path_2">
                                            <rect class="msc-1" x="-212.18" width="374" height="24" />
                                        </clipPath>
                                    </defs>
                                    <g class="msc-2">
                                        <path class="msc-3" d="M35,0H3A3.06,3.06,0,0,0,1.84.21,3,3,0,0,0,.21,1.84,3.06,3.06,0,0,0,0,3V21a3,3,0,0,0,3,3H35a2.93,2.93,0,0,0,3-3V3a3,3,0,0,0-3-3Z" />
                                        <path class="msc-4" d="M35,1a2,2,0,0,1,2,2V21a2,2,0,0,1-2,2H3a2,2,0,0,1-2-2V3A2,2,0,0,1,3,1Z" />
                                        <path class="msc-5" d="M15,19a7,7,0,1,0-7-7A7,7,0,0,0,15,19Z" />
                                        <path class="msc-6" d="M23,19a7,7,0,1,0-7-7A7,7,0,0,0,23,19Z" />
                                        <path class="msc-7" d="M22,11.7A6.84,6.84,0,0,0,19,6a7.15,7.15,0,0,0-2.18,2.49A6.89,6.89,0,0,0,19,17.4a6.84,6.84,0,0,0,3-5.7Z" />
                                    </g>
                                    <title>mastercard</title>
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Layer_1" data-name="Layer 1" viewBox="0 0 38 24" class="sm:h-8 sm:w-12 h-6 w-10 mr-2" width="24" height="24" role="img">
                                    <defs>
                                        <style>.amex-1{fill:none;}.amex-2{clip-path:url(#clip-path_3);}.amex-3{isolation:isolate;opacity:0.07;}.amex-4{fill:#006fcf;}.amex-5{fill:#fff;}</style>
                                        <clipPath id="clip-path_3">
                                            <rect class="amex-1" x="-96.18" width="374" height="24" />
                                        </clipPath>
                                    </defs>
                                    <g class="amex-2">
                                        <path class="amex-3" d="M35,0H3A3.06,3.06,0,0,0,1.84.21,3,3,0,0,0,.21,1.84,3.06,3.06,0,0,0,0,3V21a3,3,0,0,0,3,3H35a2.93,2.93,0,0,0,3-3V3a3,3,0,0,0-3-3Z" />
                                        <path class="amex-4" d="M35,1a2,2,0,0,1,2,2V21a2,2,0,0,1-2,2H3a2,2,0,0,1-2-2V3A2,2,0,0,1,3,1Z" />
                                        <path class="amex-5" d="M9,10.27l.77,1.87H8.2ZM25,10.35h-3v.82H25v1.24H22.07v.92h3v.74l2.08-2.24L25.05,9.49ZM11,8h4l.88,1.93L16.69,8H27.06l1.07,1.19L29.25,8H34l-3.52,3.85L34,15.68H29.14l-1.08-1.19-1.12,1.19H10l-.5-1.19H8.4l-.5,1.19H4L7.28,8H11Zm8.66,1.07H17.41l-1.5,3.54L14.28,9.08H12.06v4.81L10,9.08H8L5.62,14.6H7.18l.49-1.19h2.6l.5,1.19h2.72V10.66l1.74,3.94h1.19l1.74-3.93V14.6h1.46l0-5.52ZM29,11.85l2.54-2.77H29.69l-1.6,1.73L26.54,9.08H20.65V14.6h5.81l1.61-1.74,1.55,1.74H31.5L29,11.85Z" />
                                        <path class="amex-1" d="M34.82,1a2,2,0,0,1,1.41.59A2.05,2.05,0,0,1,36.82,3V21a2.05,2.05,0,0,1-.59,1.41,2,2,0,0,1-1.41.59h-32a2,2,0,0,1-2-2V3a2,2,0,0,1,2-2Z" />
                                    </g>
                                    <title>amex</title>
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" id="Group_350" data-name="Group 350" width="24" height="24" viewBox="0 0 39.959 25.582" class="sm:h-8 sm:w-12 h-6 w-10 mr-2" role="img">
                                    <g id="Group_46" data-name="Group 46" transform="translate(0)">
                                        <path id="Path_76" data-name="Path 76" d="M86.637,8H53.424c-.115,0-.23,0-.345.005a4.946,4.946,0,0,0-.751.066,2.522,2.522,0,0,0-.714.236,2.392,2.392,0,0,0-1.05,1.05,2.522,2.522,0,0,0-.236.714,4.944,4.944,0,0,0-.066.751c0,.115,0,.23-.005.345V30.413c0,.115,0,.23.005.345a4.944,4.944,0,0,0,.066.751,2.522,2.522,0,0,0,.236.714,2.388,2.388,0,0,0,1.05,1.05,2.531,2.531,0,0,0,.715.237,5.055,5.055,0,0,0,.75.065c.115,0,.23,0,.345.005H87.047c.114,0,.229,0,.345-.005a5.082,5.082,0,0,0,.751-.065,2.524,2.524,0,0,0,.714-.237,2.4,2.4,0,0,0,1.05-1.05,2.522,2.522,0,0,0,.236-.714,5.083,5.083,0,0,0,.066-.751c0-.115,0-.23.005-.345V11.169c0-.115,0-.23-.005-.345a5.084,5.084,0,0,0-.066-.751,2.522,2.522,0,0,0-.236-.714,2.4,2.4,0,0,0-1.05-1.05,2.522,2.522,0,0,0-.714-.236,4.946,4.946,0,0,0-.751-.066C87.278,8,87.162,8,87.047,8h-.41" transform="translate(-50.256 -8)" />
                                    </g>
                                    <g id="Group_47" data-name="Group 47" transform="translate(0.853 0.853)">
                                        <path id="Path_77" data-name="Path 77" d="M86.584,8.8h.4c.109,0,.219,0,.328,0a4.3,4.3,0,0,1,.624.053,1.683,1.683,0,0,1,.479.157,1.536,1.536,0,0,1,.677.678,1.608,1.608,0,0,1,.156.479,4.232,4.232,0,0,1,.053.622c0,.109,0,.217,0,.328,0,.134,0,.269,0,.4V30.357c0,.109,0,.217-.005.325a4.312,4.312,0,0,1-.053.626,1.665,1.665,0,0,1-.156.476,1.544,1.544,0,0,1-.678.678,1.672,1.672,0,0,1-.475.156,4.368,4.368,0,0,1-.621.053c-.111,0-.221,0-.334,0H53.375c-.109,0-.217,0-.326,0a4.174,4.174,0,0,1-.624-.053,1.655,1.655,0,0,1-.48-.157,1.516,1.516,0,0,1-.391-.285,1.54,1.54,0,0,1-.285-.392,1.647,1.647,0,0,1-.156-.479,4.014,4.014,0,0,1-.053-.623c0-.11,0-.219-.005-.327V11.122c0-.109,0-.219.005-.327a4.178,4.178,0,0,1,.053-.625,1.679,1.679,0,0,1,.156-.479,1.557,1.557,0,0,1,.677-.677,1.69,1.69,0,0,1,.479-.156,4.31,4.31,0,0,1,.624-.053c.11,0,.219,0,.327,0H86.584" transform="translate(-51.056 -8.8)" fill="#fff" />
                                    </g>
                                    <g id="Group_48" data-name="Group 48" transform="translate(9.028 7.062)">
                                        <path id="Path_78" data-name="Path 78" d="M60.2,16.2a2.208,2.208,0,0,0,.507-1.574,2.188,2.188,0,0,0-1.451.751,2.065,2.065,0,0,0-.52,1.513A1.828,1.828,0,0,0,60.2,16.2" transform="translate(-58.726 -14.625)" />
                                    </g>
                                    <g id="Group_49" data-name="Group 49" transform="translate(5.066 9.431)">
                                        <path id="Path_79" data-name="Path 79" d="M60.948,16.851c-.809-.048-1.5.458-1.882.458s-.977-.434-1.617-.422a2.382,2.382,0,0,0-2.026,1.231c-.869,1.5-.229,3.718.615,4.937.41.6.9,1.267,1.556,1.243.616-.023.857-.4,1.6-.4s.966.4,1.617.386c.676-.012,1.1-.6,1.508-1.208A5.31,5.31,0,0,0,63,21.692,2.192,2.192,0,0,1,61.684,19.7a2.235,2.235,0,0,1,1.062-1.871,2.31,2.31,0,0,0-1.8-.977" transform="translate(-55.008 -16.848)" />
                                    </g>
                                    <g id="Group_50" data-name="Group 50" transform="translate(15.67 7.752)">
                                        <path id="Path_80" data-name="Path 80" d="M68.352,15.273a2.841,2.841,0,0,1,2.981,2.974,2.874,2.874,0,0,1-3.025,2.988H66.363v3.093H64.957V15.273h3.395m-1.989,4.782h1.613a1.7,1.7,0,0,0,1.92-1.8,1.689,1.689,0,0,0-1.913-1.8H66.363v3.6h0" transform="translate(-64.957 -15.273)" />
                                    </g>
                                    <g id="Group_51" data-name="Group 51" transform="translate(22.395 10.156)">
                                        <path id="Path_81" data-name="Path 81" d="M71.266,22.3c0-1.161.885-1.826,2.516-1.927l1.751-.107v-.5c0-.733-.483-1.135-1.343-1.135a1.234,1.234,0,0,0-1.33.922H71.592c.037-1.174,1.142-2.026,2.636-2.026,1.606,0,2.654.84,2.654,2.146v4.506h-1.3V23.093h-.031a2.315,2.315,0,0,1-2.071,1.155A1.989,1.989,0,0,1,71.266,22.3m4.267-.584v-.508l-1.563.1c-.878.056-1.337.383-1.337.953,0,.552.478.91,1.224.91a1.533,1.533,0,0,0,1.676-1.456" transform="translate(-71.266 -17.528)" />
                                    </g>
                                    <g id="Group_52" data-name="Group 52" transform="translate(28.553 10.237)">
                                        <path id="Path_82" data-name="Path 82" d="M77.7,26.6V25.511a4.041,4.041,0,0,0,.415.025,1.118,1.118,0,0,0,1.185-.941l.126-.4L77.043,17.6h1.468l1.658,5.347H80.2L81.856,17.6h1.432l-2.467,6.922c-.565,1.587-1.211,2.108-2.58,2.108A3.781,3.781,0,0,1,77.7,26.6h0" transform="translate(-77.043 -17.604)" />
                                    </g>
                                    <title>applypay</title>
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 41.451 25.72" class="sm:h-8 sm:w-12 h-6 w-10" role="img">
                                    <g id="Group_351" data-name="Group 351" transform="translate(-0.624 -0.624)">
                                        <g id="Group_75" data-name="Group 75" transform="translate(1.124 1.124)">
                                            <path id="Path_96" data-name="Path 96" d="M257.2,9a2.254,2.254,0,0,1,2.247,2.247V31.473A2.254,2.254,0,0,1,257.2,33.72H221.247A2.254,2.254,0,0,1,219,31.473V11.247A2.254,2.254,0,0,1,221.247,9H257.2" transform="translate(-219 -9)" fill="#fff" stroke="#ccc" stroke-width="1" />
                                        </g>
                                        <g id="Group_76" data-name="Group 76" transform="translate(19.186 8.175)">
                                            <path id="Path_97" data-name="Path 97" d="M236.219,20.557v3.6h-1.144V15.276H238.1a2.75,2.75,0,0,1,1.963.778,2.563,2.563,0,0,1,.124,3.623l-.124.13a2.75,2.75,0,0,1-1.963.757l-1.88-.007m0-4.193V19.5h1.908a1.515,1.515,0,0,0,1.129-.454,1.563,1.563,0,0,0-1.129-2.645l-1.908-.034m7.286,1.515a2.852,2.852,0,0,1,2,.689,2.4,2.4,0,0,1,.73,1.853v3.746h-1.089v-.861h-.055a2.167,2.167,0,0,1-1.88,1.046,2.438,2.438,0,0,1-1.681-.6,1.873,1.873,0,0,1-.689-1.488,1.806,1.806,0,0,1,.689-1.5,3.084,3.084,0,0,1,1.908-.579,3.358,3.358,0,0,1,1.674.372v-.234a1.274,1.274,0,0,0-.469-1.012,1.593,1.593,0,0,0-1.1-.413,1.736,1.736,0,0,0-1.482.806l-1.006-.634a2.8,2.8,0,0,1,2.452-1.191h0m-1.446,4.414a.889.889,0,0,0,.379.744,1.376,1.376,0,0,0,.882.3,1.838,1.838,0,0,0,1.288-.53,1.7,1.7,0,0,0,.537-1.239,2.3,2.3,0,0,0-1.488-.42,1.934,1.934,0,0,0-1.157.33.991.991,0,0,0-.44.82h0m10.434-4.214-3.809,8.753h-1.178l1.439-3.065-2.5-5.688h1.239l1.811,4.365,1.763-4.365h1.233" transform="translate(-235.075 -15.276)" fill="#5f6368" />
                                        </g>
                                        <g id="Group_77" data-name="Group 77" transform="translate(10.813 11.646)">
                                            <path id="Path_98" data-name="Path 98" d="M232.525,19.4a6.5,6.5,0,0,0-.082-1.034h-4.82v1.963h2.754a2.356,2.356,0,0,1-1.011,1.543v1.274h1.645a4.981,4.981,0,0,0,1.515-3.746h0" transform="translate(-227.623 -18.364)" fill="#4285f4" />
                                        </g>
                                        <g id="Group_78" data-name="Group 78" transform="translate(6.267 13.533)">
                                            <path id="Path_99" data-name="Path 99" d="M228.13,24.176a4.892,4.892,0,0,0,3.382-1.233l-1.647-1.281a3.093,3.093,0,0,1-4.6-1.618h-1.688v1.315a5.091,5.091,0,0,0,4.553,2.817h0" transform="translate(-223.577 -20.044)" fill="#34a853" />
                                        </g>
                                        <g id="Group_80" data-name="Group 80" transform="translate(6.267 7.465)">
                                            <path id="Path_101" data-name="Path 101" d="M228.13,16.662a2.757,2.757,0,0,1,1.955.764l1.461-1.453a4.912,4.912,0,0,0-3.444-1.329,5.091,5.091,0,0,0-4.525,2.809l1.688,1.316a3.051,3.051,0,0,1,2.865-2.107h0" transform="translate(-223.577 -14.643)" fill="#ea4335" />
                                        </g>
                                    </g>
                                    <title>gpay</title>
                                </svg>
                                </div>
                            </li>
                        </ul>
                    </div>
                    </div>
                </div>
            </div>
            <script type="text/javascript" id="klevu_page_meta">
                var klevu_page_meta = {"platform":"magento2","pageType":"cart","cartRecords":[{"itemId":"2923","itemGroupId":""}]};
            </script>
        
            <div x-data="initKlaviyoCartTracking()" x-init="sendKlaviyoCartData()"></div>
            <script>
                function initKlaviyoCartTracking() {
                    return {
                        sendKlaviyoCartData() {
                            fetch(
                                '/reclaim/checkout/reload?form_key=' + hyva.getFormKey(),
                                {
                                    method: 'POST',
                                    body: {},
                                    headers: {contentType: 'application/json'}
                                }
                            )
                        }
                    }
                }
            </script>
        </div>
    </div>
    @else
        @include('public.no-item', ['title' => 'No items present in the cart'])
    @endif
</main>  
@endsection
