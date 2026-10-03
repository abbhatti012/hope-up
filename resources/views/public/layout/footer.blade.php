@php
    $header_brands = DB::table('brands')->orderBy('id', 'asc')->take(6)->get();
@endphp
<footer class="page-footer">
    <div class="footer content">
        <div class="bg-bronze-20 text-white text-[15px]">
            <div class="container">
                <div class="lg:flex-nowrap flex-wrap flex justify-center xl:gap-16 lg:gap-4 md:gap-6 gap-9 md:py-12 py-8 items-center">
                <div class="flex xl:gap-6 gap-4 md:flex-nowrap flex-wrap md:justify-normal justify-center">
                    <svg xmlns="https://www.w3.org/2000/svg" width="90" height="52" viewBox="0 0 90 52" class="md:w-auto w-[68px] h-auto" aria-hidden="true">
                        <path id="email" d="M87,24H25a8.024,8.024,0,0,0-8,8v3a2,2,0,0,0,4,0V32a5.019,5.019,0,0,1,.2-1.2L43.6,50,21.2,69.2A5.019,5.019,0,0,1,21,68V65a2,2,0,0,0-4,0v3a8.024,8.024,0,0,0,8,8H87a8.024,8.024,0,0,0,8-8V32A8.024,8.024,0,0,0,87,24ZM24.2,28.1A1.949,1.949,0,0,1,25,28H87a1.949,1.949,0,0,1,.8.1L57.3,54.2a2.1,2.1,0,0,1-2.6,0ZM87,72H25a1.949,1.949,0,0,1-.8-.1L46.7,52.6l5.4,4.7a5.82,5.82,0,0,0,7.8,0l5.4-4.7L87.8,71.9A1.949,1.949,0,0,1,87,72Zm4-4a5.019,5.019,0,0,1-.2,1.2L68.4,50,90.8,30.8A5.019,5.019,0,0,1,91,32ZM11,45a2.006,2.006,0,0,1,2-2H25a2,2,0,1,1,0,4H13A2.006,2.006,0,0,1,11,45ZM25,57H7a2,2,0,0,1,0-4H25a2,2,0,1,1,0,4Z" transform="translate(-5 -24)" fill="#ffA500" />
                    </svg>
                    <div class="grid grid-cols-1 md:gap-1 gap-3 md:text-left text-center md:w-auto w-full">
                        <h6 class="xl:text-3xl text-xl md:text-[26px] font-bold text-white">Stay Up To Date With Our Best Offers</h6>
                        <span class="block leading-normal">Get the latest deals to your inbox, updates, secret deals, sales and more...</span>
                    </div>
                </div>
                <div>
                    <!-- <form class="form subscribe" action="#" method="post" @submit.prevent="submitForm()" id="newsletter-validate-detail" aria-label="Subscribe&#x20;to&#x20;Newsletter"> -->
                        <div class="flex justify-end gap-1">
                            <label for="newsletter-subscribe" class="sr-only">Email Address</label>
                            <input name="email" type="email" required id="newsletter-subscribe" class="form-input inline-flex xl:w-80 md:w-64 w-full text-gray-20 h-11 lg:h-14 border-0 px-5" placeholder="Enter your email address" aria-describedby="footer-newsletter-heading">
                            <button id="subscribe-button" type="button" class="inline-flex shrink-0 btn btn-primary h-11 lg:h-14">SIGN UP</button>
                        </div>
                        <div id="message" class="mt-2 text-red-500"></div>

                        <div>
                            <template x-if="displayErrorMessage">
                            <p class="flex items-center text-red">
                                <span class="inline-block w-8 h-8 mr-3">
                                    <svg xmlns="https://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="24" height="24" role="img">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        <title>exclamation-circle</title>
                                    </svg>
                                </span>
                                <template x-for="errorMessage in errorMessages">
                                    <span x-html="errorMessage"></span>
                                </template>
                            </p>
                            </template>
                        </div>
                    <!-- </form> -->
                </div>
                </div>
                <div class="lg:border-t border-gray-90 pb-10 lg:py-14">
                <div class="flex lg:flex-nowrap flex-wrap justify-between lg:gap-5">
                    <div class="link-column lg:border-b-0 border-b border-gray-101 w-full lg:py-0 py-5 lg-w-auto lg:min-w-[160px]" x-data="{open : false}">
                        <p class="flex justify-between items-center text-bronze-10 font-bold text-xl lg:mb-3" @click="open === false ? open = true : open = false">
                            Brands 
                            <span class="inline float-right lg:hidden" :class="{ 'transform rotate-180' : open == true }">
                            <svg xmlns="https://www.w3.org/2000/svg" width="16" height="10" viewBox="0 0 16.366 9.819" fill="#000" class="fill-bronze-10" aria-hidden="true">
                                <path id="Icon_ion-ios-arrow-right_20" data-name="Icon ion-ios-arrow-right" d="M10.625,7.367l1.059-.992,8.761,8.183-8.761,8.183-1.059-.987,7.7-7.2Z" transform="translate(22.741 -10.625) rotate(90)" />
                            </svg>
                            </span>
                        </p>
                        <div class="lg:block lg:pt-0 pt-3" :class="{ 'hidden' : !open }">
                            <ul>
                                @forelse($header_brands as $brand)
                                    <li><a href="{{ route('brands', ['filter' => $brand->brand_slug]) }}">{{ $brand->brand_title }}</a></li>
                                @empty
                                @endforelse
                                    <li><a href="{{ route('brands') }}">All Brands</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="link-column lg:border-b-0 border-b border-gray-101 w-full lg:py-0 py-5 lg-w-auto lg:min-w-[160px]" x-data="{open : false}">
                        <p class="flex justify-between items-center text-bronze-10 font-bold text-xl lg:mb-3" @click="open === false ? open = true : open = false">
                            Customer Service
                            <span class="inline float-right lg:hidden" :class="{ 'transform rotate-180' : open == true }">
                            <svg xmlns="https://www.w3.org/2000/svg" width="16" height="10" viewBox="0 0 16.366 9.819" fill="#000" class="fill-bronze-10" aria-hidden="true">
                                <path id="Icon_ion-ios-arrow-right_21" data-name="Icon ion-ios-arrow-right" d="M10.625,7.367l1.059-.992,8.761,8.183-8.761,8.183-1.059-.987,7.7-7.2Z" transform="translate(22.741 -10.625) rotate(90)" />
                            </svg>
                            </span>
                        </p>
                        <div class="lg:block lg:pt-0 pt-3" :class="{ 'hidden' : !open }">
                            <ul>
                                <li><a href="{{ route('track-order') }}">Track Your Order</a></li>
                                <li><a href="{{ route('about-us') }}">About Us</a></li>
                                <li><a href="{{ route('our-services') }}">Our Services</a></li>
                                <!-- <li><a href="{{ route('delivery-information') }}">Delivery Information</a></li> -->
                                <li><a href="{{ route('our-store') }}">Our Store</a></li>
                                <!-- <li><a href="{{ route('showroom') }}">Showroom</a></li> -->
                                <li><a href="{{ route('faqs') }}">FAQ</a></li>
                                <li><a href="{{ route('terms-conditions') }}">Terms & Conditions</a></li>
                                <!-- <li><a href="{{ route('privacy-policy') }}">Privacy Policy</a></li> -->
                            </ul>
                        </div>
                    </div>
                    <div class="link-column lg:border-b-0 border-b border-gray-101 w-full lg:py-0 py-5 lg-w-auto lg:min-w-[160px]" x-data="{open : false}">
                        <p class="flex justify-between items-center text-bronze-10 font-bold text-xl lg:mb-3" @click="open === false ? open = true : open = false">
                            Contact Us
                            <span class="inline float-right lg:hidden" :class="{ 'transform rotate-180' : open == true }">
                            <svg xmlns="https://www.w3.org/2000/svg" width="16" height="10" viewBox="0 0 16.366 9.819" fill="#000" class="fill-bronze-10" aria-hidden="true">
                                <path id="Icon_ion-ios-arrow-right_22" data-name="Icon ion-ios-arrow-right" d="M10.625,7.367l1.059-.992,8.761,8.183-8.761,8.183-1.059-.987,7.7-7.2Z" transform="translate(22.741 -10.625) rotate(90)" />
                            </svg>
                            </span>
                        </p>
                        <div class="lg:block lg:pt-0 pt-3" :class="{ 'hidden' : !open }">
                            <a title="Phone" class="flex xl:gap-5 gap-3 items-center group/call hover:text-bronze-10 mb-2" href="tel:+233 (0) 302 765981">
                                <p>Ghana: +233 (0) 302 765981</p>
                            </a>
                            <a title="Phone" class="flex xl:gap-5 gap-3 items-center group/call hover:text-bronze-10 mb-2" href="tel:+233 (0) 302 765981">
                                <p>Ivory Coast: 0022559004009 </p>
                            </a>
                            <a title="Phone" class="flex xl:gap-5 gap-3 items-center group/call hover:text-bronze-10 mb-2" href="tel:+233 (0) 504 051 792">
                                <span class="block">Mobile: +233 (0) 504 051 792</span>
                            </a>
                            <a title="Phone" class="flex xl:gap-5 gap-3 items-center group/call hover:text-bronze-10 mb-2" href="tel:+233 (0) 504 051 792">
                                <span class="block">Tel:  +447931833307 </span>
                            </a>
                        </div>
                    </div>
                    <div class="link-column lg:border-b-0 border-b border-gray-101 w-full lg:py-0 py-5 lg-w-auto" x-data="{open : false}">
                        <p class="flex justify-between items-center text-bronze-10 font-bold text-xl lg:mb-3" @click="open === false ? open = true : open = false">
                            Our Address
                            <span class="inline float-right lg:hidden" :class="{ 'transform rotate-180' : open == true }">
                            <svg xmlns="https://www.w3.org/2000/svg" width="16" height="10" viewBox="0 0 16.366 9.819" fill="#000" class="fill-bronze-10" aria-hidden="true">
                                <path id="Icon_ion-ios-arrow-right_23" data-name="Icon ion-ios-arrow-right" d="M10.625,7.367l1.059-.992,8.761,8.183-8.761,8.183-1.059-.987,7.7-7.2Z" transform="translate(22.741 -10.625) rotate(90)" />
                            </svg>
                            </span>
                        </p>
                        <div class="lg:block lg:pt-0 pt-3" :class="{ 'hidden' : !open }">
                            <div class="flex xl:gap-5 gap-3 mb-7">
                            <svg xmlns="https://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23.967 29.633" class="stroke-white" aria-hidden="true">
                                <g id="Icon_akar-location" data-name="Icon akar-location" transform="translate(-5.017 -2.184)">
                                    <path id="Path_122" data-name="Path 122" d="M21.25,14.167A4.25,4.25,0,1,1,17,9.917a4.25,4.25,0,0,1,4.25,4.25Z" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.3" />
                                    <path id="Path_123" data-name="Path 123" d="M17,2.833A11.333,11.333,0,0,0,5.667,14.167a9.121,9.121,0,0,0,2.125,6.375L17,31.167l9.208-10.625a9.121,9.121,0,0,0,2.125-6.375A11.333,11.333,0,0,0,17,2.833Z" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.3" />
                                </g>
                            </svg>
                            <address class="not-italic leading-6">
                                67 NII NORTEI NYANCHI <br>ST. DZORWULU<br/>
                                DIGITAL ADDRESS: GA-120-2542<br/>
                                GHANA, ACCRA
                            </address>
                            </div>
                            <a title="Email" class="flex xl:gap-5 gap-3 items-center group/email hover:text-bronze-10 mb-7 break-all leading-6" href="mailto:info@luminantghana.com">
                                <svg xmlns="https://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 24.262 19.67" stroke="#fff" class="group-hover/email:stroke-bronze-10" aria-hidden="true">
                                    <g id="Icon_akar-envelope" data-name="Icon akar-envelope" transform="translate(-2.183 -5.017)">
                                        <path id="Path_120" data-name="Path 120" d="M2.833,7.963a2.3,2.3,0,0,1,2.3-2.3H23.5a2.3,2.3,0,0,1,2.3,2.3V21.74a2.3,2.3,0,0,1-2.3,2.3H5.13a2.3,2.3,0,0,1-2.3-2.3Z" transform="translate(0 0)" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.3" />
                                        <path id="Path_121" data-name="Path 121" d="M2.833,11.333l8.612,6.89a4.592,4.592,0,0,0,5.738,0l8.612-6.89" transform="translate(0 -1.074)" fill="none" stroke-linejoin="round" stroke-width="1.3" />
                                    </g>
                                </svg>
                                <span class="block w-[calc(100%_-_23px)]">
                            <span class="__cf_email__">info@luminantghana.com</span> </span>
                            </a>
                        </div>
                    </div>
                    <div class="m-auto text-center mt-10 sm:mt-12 lg:m-0 lg:text-left">
                        <a href="/" title="Luminant Lighting Milton Keynes - Lighting for your home and garden. LED and smart homes." rel="home">
                            <img width="1020" height="301" src="{{ asset('public_assets/media/white-logo.png') }}" class="header_logo header-logo" alt="Luminant Lighting Milton Keynes">
                            <img width="1020" height="301" src="{{ asset('public_assets/media/white-logo.png') }}" class="header-logo-dark" alt="Luminant Lighting Milton Keynes">
                        </a>
                        <div style="justify-content: center;" class="flex gap-7 mt-9 mb-8">
                            <a href="https://www.facebook.com/LuminantGhana?_rdc=1&_rdr" aria-label="Visit our Facebook page" target="_blank" rel="noopener norefferrer me">
                            <svg xmlns="https://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 29.684 29.684" fill="#fff" class="hover:fill-bronze-10" aria-hidden="true">
                                <path id="_024-facebook" data-name="024-facebook" d="M3.711,29.684H14.842V19.48H11.132V14.842h3.711V11.132a5.565,5.565,0,0,1,5.566-5.566h3.711V10.2H22.263c-1.024,0-1.855-.1-1.855.928v3.711h4.638L23.191,19.48H20.408v10.2h5.566a3.715,3.715,0,0,0,3.711-3.711V3.711A3.714,3.714,0,0,0,25.974,0H3.711A3.713,3.713,0,0,0,0,3.711V25.974A3.714,3.714,0,0,0,3.711,29.684Z" />
                            </svg>
                            </a>
                            <a href="https://twitter.com/luminantghana" aria-label="Visit our Twitter page" target="_blank" rel="noopener norefferrer me">
                            <svg xmlns="https://www.w3.org/2000/svg" width="29" height="30" viewBox="0 0 29.047 29.685" fill="#fff" class="hover:fill-bronze-10" aria-hidden="true">
                                <path id="twitter" d="M30.472,12.57,41.285,0H38.723L29.333,10.914,21.834,0H13.185l11.34,16.5L13.185,29.685h2.563L25.663,18.16l7.92,11.526h8.649L30.471,12.57Zm-3.51,4.08-1.149-1.643L16.671,1.929h3.936l7.378,10.553,1.149,1.643,9.59,13.718H34.788L26.962,16.65Z" transform="translate(-13.185)" />
                            </svg>
                            </a>
                            <a href="https://www.instagram.com/luminantelectricals/" aria-label="Visit our Instagram page" target="_blank" rel="noopener norefferrer me">
                            <svg xmlns="https://www.w3.org/2000/svg" id="_044-instagram" data-name="044-instagram" width="30" height="30" viewBox="0 0 29.684 29.685" fill="#fff" class="hover:fill-bronze-10" aria-hidden="true">
                                <path id="Path_117" data-name="Path 117" d="M13.463,5.838a7.617,7.617,0,1,0,7.617,7.617,7.616,7.616,0,0,0-7.617-7.617Zm0,12.56a4.944,4.944,0,1,1,4.944-4.944A4.942,4.942,0,0,1,13.463,18.4Z" transform="translate(1.385 1.382)" />
                                <path id="Path_118" data-name="Path 118" d="M20.962.094c-2.731-.127-9.5-.121-12.229,0A8.87,8.87,0,0,0,2.5,2.495C-.35,5.349.015,9.2.015,14.837c0,5.774-.322,9.531,2.49,12.343,2.866,2.865,6.768,2.49,12.343,2.49,5.719,0,7.693,0,9.715-.779,2.75-1.067,4.825-3.525,5.028-7.939.129-2.732.121-9.5,0-12.229C29.346,3.511,26.549.351,20.962.094Zm4.323,25.2C23.414,27.162,20.817,27,14.811,27c-6.184,0-8.664.092-10.474-1.723C2.254,23.2,2.631,19.867,2.631,14.817c0-6.834-.7-11.755,6.157-12.106,1.576-.056,2.04-.074,6.006-.074l.056.037c6.591,0,11.762-.69,12.073,6.167.071,1.565.087,2.035.087,6C27.008,20.948,27.124,23.443,25.285,25.291Z" transform="translate(0 -0.001)" />
                                <circle id="Ellipse_10" data-name="Ellipse 10" cx="1.78" cy="1.78" r="1.78" transform="translate(20.986 5.14)" />
                            </svg>
                            </a>
                            <a href="https://twitter.com/luminantghana/" aria-label="Visit our Pinterest page" target="_blank" rel="noopener norefferrer me">
                            <svg xmlns="https://www.w3.org/2000/svg" width="25" height="31" viewBox="0 0 25.327 31.173" fill="#fff" class="hover:fill-bronze-10" aria-hidden="true">
                                <path id="pinterest" d="M15.338,0C6.792,0,2.25,5.476,2.25,11.446c0,2.768,1.547,6.222,4.024,7.317.707.318.613-.07,1.221-2.4a.552.552,0,0,0-.132-.542c-3.541-4.1-.691-12.515,7.47-12.515,11.811,0,9.6,16.343,2.055,16.343a2.787,2.787,0,0,1-2.937-3.417c.556-2.251,1.644-4.671,1.644-6.293,0-4.089-6.092-3.482-6.092,1.935a6.554,6.554,0,0,0,.592,2.8s-1.96,7.918-2.324,9.4c-.616,2.5.083,6.558.144,6.908a.205.205,0,0,0,.374.095,24.5,24.5,0,0,0,3.226-6.076c.242-.89,1.233-4.5,1.233-4.5a5.385,5.385,0,0,0,4.544,2.167c5.97,0,10.285-5.248,10.285-11.759C27.557,4.671,22.214,0,15.338,0Z" transform="translate(-2.25)" />
                            </svg>
                            </a>
                        </div>
                        <p class="text-bronze-10 font-semibold text-lg mb-3">Store Opening Times</p>
                        <p class="leading-6">Mon-Fri 8:30 AM - 7:00 PM</p>
                        <p class="leading-6">Saturdays 09:00 AM - 07:00 PM</p>
                    </div>
                </div>
                </div>
                <!-- <div class="text-xs lg:text-sm border-t border-gray-90 py-7 md:py-8 xl:py-12 flex md:flex-nowrap flex-wrap items-center md:justify-between justify-center gap-4"> -->
                <!-- <div class="flex items-center">
                    <svg xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" id="Layer_1" data-name="Layer 1" viewBox="0 0 38 24" class="sm:h-8 sm:w-12 h-6 w-10 mr-2" width="24" height="24" role="img">
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
                    <svg xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" id="Layer_1" data-name="Layer 1" viewBox="0 0 38 24" class="sm:h-8 sm:w-12 h-6 w-10 mr-2" width="24" height="24" role="img">
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
                    <svg xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" id="Layer_1" data-name="Layer 1" viewBox="0 0 38 24" class="sm:h-8 sm:w-12 h-6 w-10 mr-2" width="24" height="24" role="img">
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
                    <svg xmlns="https://www.w3.org/2000/svg" id="Group_350" data-name="Group 350" width="24" height="24" viewBox="0 0 39.959 25.582" class="sm:h-8 sm:w-12 h-6 w-10 mr-2" role="img">
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
                    <svg xmlns="https://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 41.451 25.72" class="sm:h-8 sm:w-12 h-6 w-10" role="img">
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
                </div> -->
                <!-- <p class="md:text-right text-center">
                    © Copyright 2021 Luminant Electricals - All Rights Reserved 
                </p>
                </div> -->
            </div>
        </div>
    </div>
</footer>
@section('scripts')

@endsection
