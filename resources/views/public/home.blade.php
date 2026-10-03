@extends('public.layout.layout')
@section('content')
<main id="maincontent" class="page-main">
    <div class="columns">
        <div class="column main">

        @include('public.main-slider')
        
        @include('public.info')

        @include('public.brands')

        <!-- @include('public.categories') -->

        @if($featured_categories)
        <div class="cmsp2-md:py-16 cmsp2-sm:py-14 cmsp2-py-12 ">
            <div class="cmsp2-container">
                <h3 class="cmsp2-text-center cmsp2-mb-8 cmsp2-sm:mb-9 cmsp2-sm:text-[28px] cmsp2-text-[26px] cmsp2-md:text-3xl cmsp2-lg:text-[33px] cmsp2-xl:text-4xl cmsp2-xl:leading-[50px] org-text">LUMINANT CATEGORIES</h3>
                <div class="cmsp2-grid cmsp2-items-start cmsp2-md:grid-cols-4 cmsp2-xs:grid-cols-3 cmsp2-grid-cols-2 cmsp2-md:gap-x-4 cmsp2-md:gap-y-8 cmsp2-lg:gap-x-6 cmsp2-gap-x-3 cmsp2-gap-y-5 cmsp2-lg:gap-y-14">
                    <a href="{{ route('brands') }}" class="cmsp2-gap-2.5 cmsp2-lg:gap-5 cmsp2-grid cmsp2-grid-cols-1 cmsp2-group/box">
                        <span class="cmsp2-overflow-hidden">
                            <img style="width: 100%; height: auto; max-height: 250px; object-fit: cover;" class="group-hover/box:scale-110 group-hover/box:transform cmsp2-transition cmsp2-ease-out cmsp2-duration-700" src="{{ asset('public_assets/media/categories/brands-1.jpg.webp') }}" alt="All Brands" title="All Brands" />
                        </span>
                        <div class="cmsp2-flex cmsp2-justify-center cmsp2-items-center cmsp2-font-bodoni_moda cmsp2-2xl:text-[28px] cmsp2-xl:text-2xl cmsp2-md:text-[22px] cmsp2-text-base cmsp2-font-semibold cmsp2-2xl:leading-[27px] cmsp2-xl:leading-[25px] cmsp2-md:leading-[22px] cmsp2-leading-[19px]">
                            <span style="font-size: 20px;" class="group-hover/box:text-bronze-10 cmsp2-lg:w-[calc(100%_-_1.75rem)] cmsp2-md:w-[calc(100%_-_1.5rem)] cmsp2-w-[calc(100%_-_1.25rem)] text-center">Brands</span>
                            <!-- <svg xmlns="https://www.w3.org/2000/svg" class="cmsp2-lg:h-7 cmsp2-lg:w-7 cmsp2-md:h-6 cmsp2-md:w-6 cmsp2-h-5 cmsp2-w-5" viewBox="0 0 28 28">
                                <g id="Group_49" data-name="Group 49" transform="translate(-542 -4002)">
                                    <g id="Ellipse_79" data-name="Ellipse 79" transform="translate(542 4002)" fill="none" stroke="#c19b5b" stroke-width="2">
                                    <circle cx="14" cy="14" r="14" stroke="none" />
                                    <circle cx="14" cy="14" r="13" fill="none" />
                                    </g>
                                    <path id="Icon_ion-ios-arrow-right" data-name="Icon ion-ios-arrow-right" d="M10.625,7.124l.8-.749,6.617,6.181-6.617,6.181-.8-.746,5.814-5.435Z" transform="translate(543.192 4003.365)" fill="#c19c5b" stroke="#c19c5b" stroke-width="1" />
                                </g>
                            </svg> -->
                        </div>
                    </a>
                    @forelse($featured_categories as $fcat)
                    <a href="{{ route('categories', ['slug' => $fcat->cat_slug]) }}" class="cmsp2-gap-2.5 cmsp2-lg:gap-5 cmsp2-grid cmsp2-grid-cols-1 cmsp2-group/box">
                        <span class="cmsp2-overflow-hidden">
                            <img style="width: 100%; height: auto; max-height: 250px; object-fit: cover;" class="group-hover/box:scale-110 group-hover/box:transform cmsp2-transition cmsp2-ease-out cmsp2-duration-700" src="{{ asset($fcat->cat_image) }}" alt="{{ $fcat->cat_title }}" title="{{ $fcat->cat_title }}" />
                        </span>
                        <div class="cmsp2-flex cmsp2-justify-center cmsp2-items-center cmsp2-font-bodoni_moda cmsp2-2xl:text-[28px] cmsp2-xl:text-2xl cmsp2-md:text-[22px] cmsp2-text-base cmsp2-font-semibold cmsp2-2xl:leading-[27px] cmsp2-xl:leading-[25px] cmsp2-md:leading-[22px] cmsp2-leading-[19px]">
                            <span style="font-size: 20px;" class="group-hover/box:text-bronze-10 cmsp2-lg:w-[calc(100%_-_1.75rem)] cmsp2-md:w-[calc(100%_-_1.5rem)] cmsp2-w-[calc(100%_-_1.25rem)] text-center">
                                {{ $fcat->cat_title }}
                            </span>
                        </div>
                    </a>
                    @empty
                    @endforelse
                </div>
            </div>
        </div>
        @endif

        @include('public.seen-in')

        @include('public.promise')
            
        <div style="padding-top: 6rem" data-content-type="row" data-appearance="full-bleed" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src data-element="main" data-pb-style="AEHL3NF">
            <div data-content-type="html" data-appearance="default" data-element="main" data-decoded="true">
                <div class>
                    <div class="cmsp2-flex cmsp2-flex-wrap cmsp2-md:flex-nowrap cmsp2-items-start cmsp2-2xl:gap-36 cmsp2-xl:gap-20 cmsp2-lg:gap-16 cmsp2-md:gap-8 cmsp2-sm:gap-12 cmsp2-gap-8">
                        <div class="cmsp2-w-full cmsp2-md:w-1/2 cmsp2-2xl:pl-[30px] cmsp2-xl:pl-[30px] cmsp2-lg:pl-[20px] cmsp2-md:pl-[20px] ">
                            <img src="{{ asset($setting->below_promise_image) }}" alt="Homeware Collection" class="cmsp2-md:ml-auto org-text" />
                        </div>
                        <div class="cmsp2-w-full cmsp2-md:w-1/2 cmsp2-2xl:pr-[calc((100%_-_1500px)_/_2)] cmsp2-xl:pr-[calc((100%_-_1280px)_/_2)]">
                            <div class="cmsp2-lg:pr-5 cmsp2-xl:pr-[30px] cmsp2-pr-[1.125rem] cmsp2-pl-[1.125rem] cmsp2-md:pl-0">
                                <p>
                                    <?= $setting->below_promise ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($news)
        <div data-content-type="row" data-appearance="full-width" data-enable-parallax="0" data-parallax-speed="0.5" class="background-image-6687b8c025dc8">
            <div class="row-full-width-inner" data-element="inner">
            <div data-content-type="html" data-appearance="default" data-element="main" data-decoded="true">
                <div class="cmsp2-grid cmsp2-sm:grid-cols-2 cmsp2-xl:gap-24 cmsp2-lg:gap-16 cmsp2-md:gap-8 cmsp2-gap-6 cmsp2-xl:py-24 cmsp2-lg:py-20 cmsp2-md:py-16 cmsp2-sm:py-14 cmsp2-py-12">
                    @foreach($news as $new)
                    <div>
                        <figure class="cmsp2-border-b-2 cmsp2-border-bronze-10 cmsp2-relative">
                            <img src="{{ asset($new->image) }}" alt="{{ $new->title }}" />
                        <label class="label-triangle cmsp2-absolute cmsp2-bg-white cmsp2-bottom-0 cmsp2-font-semibold cmsp2-xl:px-5 cmsp2-md:px-2.5 cmsp2-px-1 cmsp2-!pr-1 cmsp2-md:py-2.5 cmsp2-py-2 cmsp2-xl:text-base cmsp2-md:text-[15px] cmsp2-text-sm cmsp2-text-gray-104">FROM OUR COLLECTION</label>
                        </figure>
                        <label class="cmsp2-block cmsp2-xl:pt-7 cmsp2-md:pt-5 cmsp2-pt-4 cmsp2-font-bodoni_moda cmsp2-xl:text-3xl cmsp2-lg:text-2xl cmsp2-md:text-xl cmsp2-text-lg org-text">{{ $new->title }}</label>
                        <p class="cmsp2-xl:pt-4 cmsp2-lg:pt-3 cmsp2-pt-2">{{ $new->description }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            </div>
        </div>
        @endif
        <div class="xl:px-24 lg:px-5 px-[18px]">
            <div class="grid sm:grid-cols-4 grid-cols-2 sm:gap-[2px] gap-x-2 xx:gap-y-6 gap-y-4">
                <div :class="intersect ? 'animate-fade-up animate-duration-800 animate-delay-300':'opacity-0'" class="2xl:even:mt-[60px] lg:even:mt-12 md:even:mt-9 sm:even:mt-7 2xl:odd:mb-[60px] lg:odd:mb-12 md:odd:mb-9 sm:odd:mb-7">
                    <a href="{{ route('about-us') }}">
                    <img src="{{ asset($setting->home_below_best_image1) }}" alt="{{ $setting->home_below_best_title1 }}">
                    <span class="font-bodoni_moda xl:pt-2.5 lg:pt-2 pt-1 block font-medium text-center xl:text-3xl lg:text-2xl md:text-xl text-lg">
                    {{ $setting->home_below_best_title1 }} </span>
                    </a>
                </div>
                <div :class="intersect ? 'animate-fade-down animate-duration-800 animate-delay-300':'opacity-0'" class="2xl:even:mt-[60px] lg:even:mt-12 md:even:mt-9 sm:even:mt-7 2xl:odd:mb-[60px] lg:odd:mb-12 md:odd:mb-9 sm:odd:mb-7">
                    <a href="{{ route('our-services') }}">
                    <img src="{{ asset($setting->home_below_best_image2) }}" alt="{{ $setting->home_below_best_title2 }}">
                    <span class="font-bodoni_moda xl:pt-2.5 lg:pt-2 pt-1 block font-medium text-center xl:text-3xl lg:text-2xl md:text-xl text-lg">
                    {{ $setting->home_below_best_title2 }} </span>
                    </a>
                </div>
                <div :class="intersect ? 'animate-fade-up animate-duration-800 animate-delay-300':'opacity-0'" class="2xl:even:mt-[60px] lg:even:mt-12 md:even:mt-9 sm:even:mt-7 2xl:odd:mb-[60px] lg:odd:mb-12 md:odd:mb-9 sm:odd:mb-7">
                    <a href="{{ route('all-products') }}">
                    <img src="{{ asset($setting->home_below_best_image3) }}" alt="{{ $setting->home_below_best_title3 }}">
                    <span class="font-bodoni_moda xl:pt-2.5 lg:pt-2 pt-1 block font-medium text-center xl:text-3xl lg:text-2xl md:text-xl text-lg">
                    {{ $setting->home_below_best_title3 }} </span>
                    </a>
                </div>
                <div :class="intersect ? 'animate-fade-down animate-duration-800 animate-delay-300':'opacity-0'" class="2xl:even:mt-[60px] lg:even:mt-12 md:even:mt-9 sm:even:mt-7 2xl:odd:mb-[60px] lg:odd:mb-12 md:odd:mb-9 sm:odd:mb-7">
                    <a href="{{ route('brands') }}" rel="nofollow">
                    <img src="{{ asset($setting->home_below_best_image4) }}" alt="{{ $setting->home_below_best_title4 }}">
                    <span class="font-bodoni_moda xl:pt-2.5 lg:pt-2 pt-1 block font-medium text-center xl:text-3xl lg:text-2xl md:text-xl text-lg">
                    {{ $setting->home_below_best_title4 }} </span>
                    </a>
                </div>
            </div>
        </div>
        <style>
            @keyframes move-up {
                0% {
                    transform: translateY(20px);
                    opacity: 0;
                }
                100% {
                    transform: translateY(0);
                    opacity: 1;
                }
            }

            @keyframes move-down {
                0% {
                    transform: translateY(-20px);
                    opacity: 0;
                }
                100% {
                    transform: translateY(0);
                    opacity: 1;
                }
            }

            .animate-up {
                animation: move-up 0.8s forwards;
            }

            .animate-down {
                animation: move-down 0.8s forwards;
            }
        </style>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const images = document.querySelectorAll('.grid > div'); // Select all image containers

                const options = {
                    root: null, // Use the viewport as the root
                    rootMargin: '0px',
                    threshold: 0.1 // Trigger when 10% of the target is visible
                };

                const callback = (entries, observer) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            const index = Array.from(images).indexOf(entry.target);

                            // Apply the corresponding animation class based on index
                            if (index % 2 === 0) {
                                entry.target.classList.add('animate-up');
                            } else {
                                entry.target.classList.add('animate-down');
                            }

                            // Unobserve the target once it has animated
                            observer.unobserve(entry.target);
                        }
                    });
                };

                const observer = new IntersectionObserver(callback, options);

                images.forEach(image => {
                    observer.observe(image);
                });
            });
        </script>
        <!-- <div data-content-type="row" data-appearance="full-bleed" data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src data-element="main" data-pb-style="HW9Q578">
            <div data-content-type="html" data-appearance="default" data-element="main" data-decoded="true">
            <div class="cmsp2-sm:pb-14 cmsp2-pb-12">
                <div class="cmsp2-flex cmsp2-flex-wrap cmsp2-md:flex-nowrap cmsp2-items-center cmsp2-2xl:gap-36 cmsp2-xl:gap-24 cmsp2-lg:gap-16 cmsp2-md:gap-10 cmsp2-sm:gap-12 cmsp2-gap-8">
                    <div class="cmsp2-w-full cmsp2-md:w-1/2 cmsp2-2xl:pl-[100px] cmsp2-xl:pl-20 cmsp2-lg:pl-12 cmsp2-md:pl-10 ">
                        <img src="{{ asset('public_assets/media/home/home-business.jpg') }}" alt="Modern Designer Lighting For Home and Busines" class="cmsp2-md:ml-auto" />
                    </div>
                    <div class="cmsp2-w-full cmsp2-md:w-1/2 cmsp2-2xl:pr-[calc((100%_-_1480px)_/_2)] cmsp2-xl:pr-[calc((100%_-_1280px)_/_2)] cmsp2-lg:pr-[calc((100%_-_1024px)_/_2)] cmsp2-md:pr-[calc((100%_-_768px)_/_2)]">
                        <div class="cmsp2-lg:pr-5 cmsp2-pr-[1.125rem] cmsp2-pl-[1.125rem] cmsp2-md:pl-0">
                        <h2 class="cmsp2-lg:text-[33px] cmsp2-mb-6  cmsp2-md:text-3xl cmsp2-sm:text-[28px] cmsp2-text-[26px] cmsp2-xl:text-4xl  cmsp2-xl:leading-[50px] cmsp2-lg:leading-[44px] cmsp2-md:leading-[40px] cmsp2-leading-[36px] org-text"> Modern Designer Lighting For Home and Business… </h2>
                        <p>KES have been bringing a little light into our discerning customers' homes for more than 35 years. We stock a vast collection of luxury lighting from all over the world – and now sell a range of chic and stylish homeware products, too. </p>
                        <p class="cmsp2-lg:pt-8 cmsp2-lg:pb-10 cmsp2-md:pt-7 cmsp2-pt-4 cmsp2-md:pb-8 cmsp2-pb-6">We started out as a small independent retailer not far from Coventry, and have grown over the decades into one of the UK's leading lighting companies, stocking luxurious fittings and homeware products from all over the world.</p>
                        <a href="/about-us" class="cmsp2-underline cmsp2-uppercase cmsp2-text-[15px] cmsp2-sm:text-base cmsp2-lg:text-[17px] cmsp2-font-bold cmsp2-text-bronze-10 cmsp2-hover:text-black">READ MORE</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="cmsp2-xl:pb-24 cmsp2-lg:pb-20 cmsp2-md:pb-16 cmsp2-sm:pb-14 cmsp2-pb-12">
                <div class="cmsp2-flex cmsp2-md:flex-row cmsp2-flex-col-reverse cmsp2-md:flex-nowrap cmsp2-items-center cmsp2-2xl:gap-36 cmsp2-xl:gap-24 cmsp2-lg:gap-16 cmsp2-md:gap-10 cmsp2-sm:gap-12 cmsp2-gap-8">
                    <div class="cmsp2-w-full cmsp2-md:w-1/2 cmsp2-2xl:pl-[calc((100%_-_1480px)_/_2)] cmsp2-xl:pl-[calc((100%_-_1280px)_/_2)] cmsp2-lg:pl-[calc((100%_-_1024px)_/_2)] cmsp2-md:pl-[calc((100%_-_768px)_/_2)]">
                        <div class="cmsp2-lg:pl-5 cmsp2-pl-[1.125rem] cmsp2-pr-[1.125rem] cmsp2-md:pr-0">
                        <h5 class="cmsp2-lg:text-[33px] cmsp2-mb-6  cmsp2-md:text-3xl cmsp2-sm:text-[28px] cmsp2-text-[26px] cmsp2-xl:text-4xl  cmsp2-xl:leading-[50px] cmsp2-lg:leading-[44px] cmsp2-md:leading-[40px] cmsp2-leading-[36px] org-text">Bringing You the World’s Most Luxurious Lighting</h5>
                        <p>We’re proud partners with more than 30 brands in the UK, the US, Spain, Italy and beyond, and in many cases we are one of the few places in the whole country where you can buy their lights. Our aim is to offer first-class service, the widest possible selection of luxury lighting that we can, and speedy delivery whenever possible. </p>
                        <p class="cmsp2-lg:pt-8 cmsp2-lg:pb-10 cmsp2-md:pt-7 cmsp2-pt-4 cmsp2-md:pb-8 cmsp2-pb-6">Our team of skilled salespeople and customer support experts are able to advise on all aspects of lighting and can offer help when it comes to choosing amazing chandeliers, finding robust, luxurious exterior lighting, taking bespoke orders and much more.</p>
                        <a href="/about-us" class="cmsp2-underline cmsp2-uppercase cmsp2-text-[15px] cmsp2-sm:text-base cmsp2-lg:text-[17px] cmsp2-font-bold cmsp2-text-bronze-10 cmsp2-hover:text-black">READ MORE</a>
                        </div>
                    </div>
                    <div class="cmsp2-w-full cmsp2-md:w-1/2 cmsp2-2xl:pr-[100px] cmsp2-xl:pr-20 cmsp2-lg:pr-12 cmsp2-md:pr-10 ">
                        <img src="{{ asset('public_assets/media/home/luxurious-lighting.jpg') }}" alt="Luxurious Lighting" class="cmsp2-md:ml-auto" />
                    </div>
                </div>
            </div>
            </div>
        </div> -->
            @include('public.faq')
        </div>
    </div>
</main>
@endsection
@section('scripts')
    
@endsection
