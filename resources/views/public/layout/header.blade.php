@php
    $categories = DB::table('categories')->where('is_featured', 1)->orderBy('id', 'asc')->get();
    $header_brands = DB::table('brands')->orderBy('id', 'asc')->get();
    $cartContent = Cart::getContent()
@endphp
<style>
   .mobile-nav li a {
      color: white !important;
   }
</style>


<div class="mfp-bg off-canvas dark off-canvas-left main-menu-overlay mfp-ready hidden" style="height: 7763px; position: absolute;"></div>
<div class="mfp-wrap mfp-auto-cursor off-canvas dark off-canvas-left mfp-ready hidden" tabindex="-1" style="top: 0px; position: absolute; height: 226px;">
   <div class="mfp-container mfp-s-ready mfp-inline-holder">
      <div class="mfp-content">
         <div id="main-menu" class="mobile-sidebar no-scrollbar">
            <div class="sidebar-menu no-scrollbar ">
               <ul class="nav nav-sidebar nav-vertical nav-uppercase mobile-nav" data-tab="1">
                  <li class="header-search-form search-form html relative has-icon">
                     <div class="header-search-form-wrapper">
                        <div class="searchform-wrapper ux-search-box relative is-normal">
                           <form role="search" method="get" class="searchform" action="#">
                              <div class="flex-row relative">
                                 <div class="flex-col flex-grow">
                                       <label class="screen-reader-text">Search for:</label>
                                       <input style="color: black;" type="search" class="search-field mb-0" placeholder="Lighting Search" value="" name="s" autocomplete="off" id="product-search">
                                 </div>
                                 <div class="flex-col">
                                       <button type="button" value="Search" class="ux-search-submit submit-button secondary button icon mb-0" aria-label="Submit" id="search-button">
                                          <i class="fas fa-search"></i>
                                       </button>
                                 </div>
                              </div>
                              <div class="live-search-results text-left z-top">
                                 <div class="autocomplete-suggestions" id="suggestions" style="position: absolute; display: none; max-height: 300px; z-index: 9999;"></div>
                              </div>
                           </form>
                        </div>
                     </div>
                  </li>
                  <li class="active menu-item menu-item-type-custom menu-item-object-custom current-menu-item current_page_item menu-item-home menu-item-71273 active"><a href="/" aria-current="page">Home</a></li>
                  <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-68936 has-child" aria-expanded="false">
                     <a href="#">Products</i></a>
                     <button class="toggle" aria-label="Toggle"><i class="fas fa-angle-down"></i></button>
                     <ul class="sub-menu nav-sidebar-ul children">
                        <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-137426">
                           <a href="{{ route('brands') }}" class="org-text">Brands</a>
                           <ul class="sub-menu nav-sidebar-ul">
                            @forelse($header_brands as $brand)
                                <li class="menu-item menu-item-type-taxonomy menu-item-object-product_cat menu-item-137447"><a href="{{ route('brands', ['filter' => $brand->brand_slug]) }}">{{ $brand->brand_title }}</a></li>
                            @empty
                            @endforelse
                           </ul>
                        </li>
                        <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-137427">
                           <a href="#" class="org-text">Light Type</a>
                           <ul class="sub-menu nav-sidebar-ul">
                            @forelse($categories as $category)
                              <li class="menu-item menu-item-type-taxonomy menu-item-object-product_cat menu-item-137428"><a href="{{ route('categories', ['slug' => $category->cat_slug]) }}">{{ $category->cat_title }}</a></li>
                            @empty
                            @endforelse
                           </ul>
                        </li>
                     </ul>
                  </li>
                  <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-68948 has-child" aria-expanded="false">
                     <a href="#">Contact us</a>
                     <button class="toggle" aria-label="Toggle"><i class="fas fa-angle-down"></i></button>
                     <ul class="sub-menu nav-sidebar-ul children">
                        <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-137445">
                           <a href="#" class="org-text">Information Pages</a>
                           <ul class="sub-menu nav-sidebar-ul">
                              <li class="label-sale menu-item menu-item-type-taxonomy menu-item-object-product_tag menu-item-137444"><a href="{{ route('our-services') }}">Our Services</a></li>
                              <li class="label-sale menu-item menu-item-type-taxonomy menu-item-object-product_tag menu-item-137444"><a href="{{ route('our-store') }}">Our Store</a></li>
                              <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-137461"><a href="{{ route('about-us') }}">About Us</a></li>
                              <!-- <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-137463"><a href="{{ route('delivery-information') }}">Delivery Information</a></li> -->
                              <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-137464"><a href="{{ route('faqs') }}">FAQ</a></li>
                              <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-137465"><a href="{{ route('showroom') }}">Showroom</a></li>
                              <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-137466"><a href="{{ route('terms-conditions') }}">Terms &amp; Conditions</a></li>
                              <!-- <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-137467"><a href="{{ route('privacy-policy') }}">Privacy Policy</a></li> -->
                           </ul>
                        </li>
                     </ul>
                  </li>
                 
                  <li class="account-item has-icon menu-item">
                     @if(auth()->user())
                        <a href="{{ route('user-dashboard') }}" class="nav-top-link nav-top-not-logged-in" title="Login">
                           <span class="header-account-title">My Account</span>
                        </a>
                     @else
                        <a href="{{ route('login') }}" class="nav-top-link nav-top-not-logged-in" title="Login">
                           <span class="header-account-title">Login</span>
                        </a>
                     @endif
                  </li>
                  <li class="account-item has-icon menu-item">
                     <a href="{{ route('cart-items') }}" class="nav-top-link nav-top-not-logged-in" title="Login">
                        <span class="header-account-title">My Cart</span>
                     </a>
                  </li>
               </ul>
            </div>
         </div>
      </div>
      <div class="mfp-preloader">Loading...</div>
   </div>
   <button title="Close (Esc)" type="button" class="mfp-close">
      <svg xmlns="https://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
         <line x1="18" y1="6" x2="6" y2="18"></line>
         <line x1="6" y1="6" x2="18" y2="18"></line>
      </svg>
   </button>
</div>
<!-- <a class="skip-link screen-reader-text" href="#main">Skip to content</a> -->

<header id="header" class="header has-sticky sticky-shrink" style="">
   <div class="header-wrapper">
      <div id="masthead" class="header-main show-logo-center">
         <div class="header-inner flex-row container logo-center medium-logo-center" role="navigation">
            <div id="logo" class="flex-col logo">
               <a href="/" title="Luminant Lighting Milton Keynes - Lighting for your home and garden. LED and smart homes." rel="home">
               <img width="1020" height="301" src="{{ asset('public_assets/media/logo.png') }}" class="header_logo header-logo" alt="Luminant Lighting Milton Keynes"><img width="1020" height="301" src="{{ asset('public_assets/media/logo.png') }}" class="header-logo-dark" alt="Luminant Lighting Milton Keynes"></a>
            </div>
            <!-- Mobile Left Elements -->
            <div class="flex-col show-for-medium flex-left">
               <ul class="mobile-nav nav nav-left">
                  <li class="nav-icon has-icon">
                     <a href="#" data-open="#main-menu" data-pos="left" data-bg="main-menu-overlay" data-color="dark" class="is-small" aria-label="Menu" aria-controls="main-menu" aria-expanded="false">
                        <i class="icon-menu"></i>
                     </a>
                  </li>
               </ul>
            </div>
            <!-- Left Elements -->
            <div class="flex-col hide-for-medium flex-left">
               <ul class="header-nav header-nav-main nav nav-left  nav-size-medium nav-spacing-medium nav-uppercase nav-prompts-overlay">
                  <li class="header-search header-search-dropdown has-icon has-dropdown menu-item-has-children">
                     <a href="#" aria-label="Search" class="is-small"><i class="fas fa-search"></i></a>
                     <ul class="nav-dropdown nav-dropdown-simple dropdown-uppercase">
                        <li class="header-search-form search-form html relative has-icon">
                           <div class="header-search-form-wrapper">
                              <div class="searchform-wrapper ux-search-box relative is-normal">
                              <form role="search" method="get" class="searchform" action="#">
                                 <div class="flex-row relative">
                                    <div class="flex-col flex-grow">
                                          <label class="screen-reader-text">Search for:</label>
                                          <input type="search" class="search-field mb-0" placeholder="Lighting Search" value="" name="s" autocomplete="off" id="product-search">
                                    </div>
                                    <div class="flex-col">
                                          <button type="button" value="Search" class="ux-search-submit submit-button secondary button icon mb-0" aria-label="Submit" id="search-button">
                                             <i class="fas fa-search"></i>
                                          </button>
                                    </div>
                                 </div>
                                 <div class="live-search-results text-left z-top">
                                    <div class="autocomplete-suggestions" id="suggestions" style="position: absolute; display: none; max-height: 300px; z-index: 9999;"></div>
                                 </div>
                              </form>
                              </div>
                           </div>
                        </li>
                     </ul>
                  </li>

                  <li id="menu-item-71273" class="menu-item menu-item-type-custom menu-item-object-custom current-menu-item current_page_item menu-item-home menu-item-71273 active menu-item-design-default"><a href="/" aria-current="page" class="nav-top-link">Home</a></li>
                  <li id="menu-item-68936" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-68936 menu-item-design-default has-dropdown">
                     <a href="#" class="nav-top-link" aria-expanded="false" aria-haspopup="menu">Products<i class="fas fa-angle-down"></i></a>
                     <ul class="sub-menu nav-dropdown nav-dropdown-simple dropdown-uppercase">
                        <li id="menu-item-137426" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-137426 nav-dropdown-col">
                           <a href="#" class="org-text">Brands</a>
                           <ul style="overflow-x: auto; height: 455px;" class="sub-menu nav-column nav-dropdown-simple dropdown-uppercase">
                            @forelse($header_brands as $brand)
                                <li class="menu-item menu-item-type-taxonomy menu-item-object-product_cat menu-item-137447"><a href="{{ route('brands', ['filter' => $brand->brand_slug]) }}">{{ $brand->brand_title }}</a></li>
                            @empty
                            @endforelse
                           </ul>
                        </li>
                        <li id="menu-item-137427" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-137427 nav-dropdown-col">
                           <a href="#" class="org-text">Light Type</a>
                           <ul class="sub-menu nav-column nav-dropdown-simple dropdown-uppercase">
                            @forelse($categories as $category)
                                <li class="menu-item menu-item-type-taxonomy menu-item-object-product_cat menu-item-137428"><a href="{{ route('categories', ['slug' => $category->cat_slug]) }}">{{ $category->cat_title }}</a></li>
                            @empty
                            @endforelse
                           </ul>
                        </li>
                     </ul>
                  </li>
                  <li id="menu-item-68948" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-68948 menu-item-design-default has-dropdown">
                     <a href="#" class="nav-top-link" aria-expanded="false" aria-haspopup="menu">Contact us<i class="fas fa-angle-down"></i></a>
                     <ul class="sub-menu nav-dropdown nav-dropdown-simple dropdown-uppercase">
                        <li id="menu-item-137445" class="menu-item menu-item-type-custom menu-item-object-custom menu-item-has-children menu-item-137445 nav-dropdown-col">
                           <a href="#" class="org-text">Information Pages</a>
                           <ul class="sub-menu nav-column nav-dropdown-simple dropdown-uppercase">
                                <li class="label-sale menu-item menu-item-type-taxonomy menu-item-object-product_tag menu-item-137444"><a href="{{ route('our-services') }}">Our Services</a></li>
                                <li class="label-sale menu-item menu-item-type-taxonomy menu-item-object-product_tag menu-item-137444"><a href="{{ route('our-store') }}">Our Store</a></li>
                                <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-137461"><a href="{{ route('about-us') }}">About Us</a></li>
                                <!-- <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-137463"><a href="{{ route('delivery-information') }}">Delivery Information</a></li> -->
                                <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-137464"><a href="{{ route('faqs') }}">FAQ</a></li>
                                <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-137465"><a href="{{ route('showroom') }}">Showroom</a></li>
                                <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-137466"><a href="{{ route('terms-conditions') }}">Terms &amp; Conditions</a></li>
                                <!-- <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-137467"><a href="{{ route('privacy-policy') }}">Privacy Policy</a></li> -->
                           </ul>
                        </li>
                     </ul>
                  </li>
               </ul>
            </div>
            <!-- Right Elements -->
            <div class="flex-col hide-for-medium flex-right">
               <ul class="header-nav header-nav-main nav nav-right  nav-size-medium nav-spacing-medium nav-uppercase nav-prompts-overlay">
                    <li class="account-item has-icon menu-item">
                        <div id="google_translate_element"></div>
                    </li>
                    <li class="cart-item has-icon has-dropdown">
                        @if(auth()->user())
                            <a href="{{ route('user-dashboard') }}">
                                <button type="button" id="customer-menu" class="block hover:text-black group/customer">
                                    <svg xmlns="https://www.w3.org/2000/svg" width="23" height="26" viewBox="0 0 22.992 26.174" class="group-hover/customer:fill-bronze-10" aria-hidden="true">
                                        <g id="user" transform="translate(-564.672 -277.798)">
                                            <path id="Path_33" data-name="Path 33" d="M576.234,291.553a6.878,6.878,0,1,0-6.879-6.877A6.886,6.886,0,0,0,576.234,291.553Zm0-12.313a5.437,5.437,0,1,1-5.437,5.437A5.443,5.443,0,0,1,576.234,279.24Z" transform="translate(-0.065)"></path>
                                            <path id="Path_34" data-name="Path 34" d="M587.664,303.469a9.989,9.989,0,0,0-2.912-7.089h0a9.88,9.88,0,0,0-7.038-2.971h-3.09a9.879,9.879,0,0,0-7.038,2.971,9.982,9.982,0,0,0-2.913,7.089.722.722,0,0,0,.721.721h21.551A.722.722,0,0,0,587.664,303.469Zm-21.518-.721,0-.053a8.567,8.567,0,0,1,8.482-7.845h3.074a8.567,8.567,0,0,1,8.482,7.845l0,.053Z" transform="translate(0 -0.218)"></path>
                                        </g>
                                    </svg>
                                </button>
                            </a>
                        @else
                            <a href="{{ route('login') }}">
                                <button type="button" id="customer-menu" class="block hover:text-black group/customer">
                                    <svg xmlns="https://www.w3.org/2000/svg" width="23" height="26" viewBox="0 0 22.992 26.174" class="group-hover/customer:fill-bronze-10" aria-hidden="true">
                                        <g id="user" transform="translate(-564.672 -277.798)">
                                            <path id="Path_33" data-name="Path 33" d="M576.234,291.553a6.878,6.878,0,1,0-6.879-6.877A6.886,6.886,0,0,0,576.234,291.553Zm0-12.313a5.437,5.437,0,1,1-5.437,5.437A5.443,5.443,0,0,1,576.234,279.24Z" transform="translate(-0.065)"></path>
                                            <path id="Path_34" data-name="Path 34" d="M587.664,303.469a9.989,9.989,0,0,0-2.912-7.089h0a9.88,9.88,0,0,0-7.038-2.971h-3.09a9.879,9.879,0,0,0-7.038,2.971,9.982,9.982,0,0,0-2.913,7.089.722.722,0,0,0,.721.721h21.551A.722.722,0,0,0,587.664,303.469Zm-21.518-.721,0-.053a8.567,8.567,0,0,1,8.482-7.845h3.074a8.567,8.567,0,0,1,8.482,7.845l0,.053Z" transform="translate(0 -0.218)"></path>
                                        </g>
                                    </svg>
                                </button>
                            </a>
                        @endif
                  </li>
                  <!-- <li class="header-divider"></li>
                  <li class="account-item has-icon">
                    <div class="hidden lg:inline-block">
                        <a title="Wishlist" class="wishlist text-center group/wishlist" href="{{ route('wishlist') }}">
                            <svg xmlns="https://www.w3.org/2000/svg" width="27" height="24" viewBox="0 0 26.677 23.96" class="group-hover/wishlist:fill-bronze-10" aria-hidden="true">
                                <g id="wishlist_2" transform="translate(-606.976 -278.715)">
                                <path id="Path_29_2" data-name="Path 29" d="M620.314,302.675a2.021,2.021,0,0,1-1.046-.293c-10.7-6.578-12.292-13.268-12.292-16.672a7.039,7.039,0,0,1,7.029-7h.033c2.833,0,5.2,2.163,6.274,3.341,1.079-1.177,3.446-3.341,6.281-3.341h.035a7.036,7.036,0,0,1,7.024,6.991c0,3.409-1.6,10.1-12.289,16.673A2.024,2.024,0,0,1,620.314,302.675ZM614,280.27a5.481,5.481,0,0,0-5.474,5.445c0,3.075,1.5,9.162,11.547,15.34a.464.464,0,0,0,.476,0C630.6,294.879,632.1,288.79,632.1,285.71a5.48,5.48,0,0,0-5.473-5.441H626.6c-3.048,0-5.638,3.422-5.665,3.456a.779.779,0,0,1-.622.313h0a.78.78,0,0,1-.623-.313c-.026-.034-2.622-3.456-5.652-3.456A.311.311,0,0,1,614,280.27Z"></path>
                                </g>
                            </svg>
                        </a>
                    </div>
                  </li> -->
                  <li class="header-divider"></li>
                  <li class="cart-item has-icon has-dropdown">
                    <a href="{{ route('cart-items') }}">
                        <button id="menu-cart-icon" class="relative outline-none focus:ring-0 pr-2 group/cart fill-kes-green" title="Cart">
                            <svg xmlns="https://www.w3.org/2000/svg" width="26" height="25" viewBox="0 0 26.383 25.257" class="group-hover/cart:fill-bronze-10" aria-hidden="true">
                                <g id="cart" transform="translate(-645.956 -278.715)">
                                    <g id="Path_23" data-name="Path 23">
                                        <path id="Path_30" data-name="Path 30" d="M655.76,303.972a1.881,1.881,0,1,1,1.881-1.881A1.883,1.883,0,0,1,655.76,303.972Zm0-2.262a.381.381,0,1,0,.381.381A.382.382,0,0,0,655.76,301.71Z"></path>
                                    </g>
                                    <g id="Path_24" data-name="Path 24">
                                        <path id="Path_31" data-name="Path 31" d="M668.2,303.972a1.881,1.881,0,1,1,1.881-1.881A1.883,1.883,0,0,1,668.2,303.972Zm0-2.262a.381.381,0,1,0,.381.381A.382.382,0,0,0,668.2,301.71Z"></path>
                                    </g>
                                    <g id="Path_25" data-name="Path 25">
                                        <path id="Path_32" data-name="Path 32" d="M656.467,297.185a3.008,3.008,0,0,1-2.94-2.425l-1.891-9.451a.771.771,0,0,1-.016-.08l-1-5.014h-3.91a.75.75,0,0,1,0-1.5h4.525a.751.751,0,0,1,.736.6l1.012,5.054h18.61a.751.751,0,0,1,.737.89l-1.8,9.492a3.059,3.059,0,0,1-3.014,2.431H656.467Zm-3.189-11.314,1.72,8.6a1.517,1.517,0,0,0,1.514,1.218h11.014a1.53,1.53,0,0,0,1.528-1.217l1.63-8.6Z"></path>
                                    </g>
                                </g>
                            </svg>
                            <span class="absolute -top-2 right-0 h-5 w-5 text-sm font-semibold text-center text-white rounded-full bg-black group-hover/cart:bg-bronze-10 bg-kes-green cartItemsCount" aria-hidden="true">{{ count($cartContent) }}</span>
                        </button>
                    </a>
                  </li>
               </ul>
            </div>
            <!-- Mobile Right Elements -->
            <div class="flex-col show-for-medium flex-right">
               <ul class="mobile-nav nav nav-right ">
              
               </ul>
            </div>
         </div>
         <div class="container">
            <div class="top-divider full-width"></div>
         </div>
      </div>
      <div class="header-bg-container fill">
         <div class="header-bg-image fill"></div>
         <div class="header-bg-color fill"></div>
      </div>
   </div>
</header>

<script type="text/javascript" src="{{ asset('public_assets/hoverIntent.min.js') }}" id="hoverIntent-js"></script>
<script type="text/javascript" id="flatsome-js-js-extra">
    var flatsomeVars = {"theme":{"version":"3.19.4"},"ajaxurl":"","rtl":"","sticky_height":"69","stickyHeaderHeight":"0","scrollPaddingTop":"0","assets_url":"https:\/\/refinedlighting.co.uk\/wp-content\/themes\/flatsome\/assets\/","lightbox":{"close_markup":"<button title=\"%title%\" type=\"button\" class=\"mfp-close\"><svg xmlns=\"http:\/\/www.w3.org\/2000\/svg\" width=\"28\" height=\"28\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"feather feather-x\"><line x1=\"18\" y1=\"6\" x2=\"6\" y2=\"18\"><\/line><line x1=\"6\" y1=\"6\" x2=\"18\" y2=\"18\"><\/line><\/svg><\/button>","close_btn_inside":false},"user":{"can_edit_pages":false},"i18n":{"mainMenu":"Main Menu","toggleButton":"Toggle"},"options":{"cookie_notice_version":"2","swatches_layout":false,"swatches_disable_deselect":false,"swatches_box_select_event":false,"swatches_box_behavior_selected":false,"swatches_box_update_urls":"1","swatches_box_reset":false,"swatches_box_reset_limited":false,"swatches_box_reset_extent":false,"swatches_box_reset_time":300,"search_result_latency":"0"},"is_mini_cart_reveal":"1"};
</script>
<script type="text/javascript" src="{{ asset('public_assets/flatsome.js') }}" id="flatsome-js-js"></script>
<script type="text/javascript" src="{{ asset('public_assets/flatsome-cookie-notice.js') }}" id="flatsome-cookie-notice-js"></script>

<script>
    function googleTranslateElementInit() {
        new google.translate.TranslateElement({
            pageLanguage: 'en',
            includedLanguages: 'fr,en',
        }, 'google_translate_element');
    }
</script>

<script src="http://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<style>
    .goog-te-gadget {
        height: 30px !important;
        overflow: hidden !important;
    }

    .goog-te-gadget .goog-te-combo {
        background: transparent;
        padding: 2px 4px !important;
        border: 0 !important;
        color: black !important;
        font-weight: 300 !important;
        outline: none !important;
    }
    .goog-te-gadget .goog-te-combo{
        bottom: 9px;
        color: white !important;
        padding: 3px 20px !important;
    }
    .goog-te-combo option {
        color: black !important;
    }

    #:0.targetLanguage {
        position: relative !important;
        top: 4px !important;
    }

    iframe {
        display: none !important;
    }

    #html-body {
        top: 0 !important;
    }
    li #google_translate_element{
        background: #ffA500;
        color: white;
    }

   #google_translate_element:after {
        /* content: '\2193'; */
        font-size: 12px;
        color: black;
        position: absolute;
        right: 5px;
        top: 50%;
        transform: translateY(-50%);
        color: white;
    }
</style>