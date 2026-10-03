@php
    $faqs = DB::table('faqs')->where('type',1)->limit(5)->get();
@endphp
<div data-content-type="row" data-appearance="contained" data-element="main">
    <div data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src data-element="inner" data-pb-style="GWAO1NX">
        <div data-content-type="html" data-appearance="default" data-element="main" data-decoded="true">
            <section class="flex lg:flex-nowrap flex-wrap 2xl:gap-36 xl:gap-28 lg:gap-12 xl:py-24 lg:pb-20 md:pb-16 sm:pb-14 pb-12">
                <div class="w-full lg:w-[23rem]">
                    <h5 class="mb-8 lg:mb-9 sm:text-[28px] text-[26px] md:text-3xl lg:text-[33px] xl:text-4xl xl:leading-[50px] lg:leading-[44px] md:leading-[40px] leading-[36px] org-text">
                        Frequently Asked Questions
                    </h5>
                    <a href="{{ route('faqs') }}" class="btn btn-primary w-max hidden lg:flex">
                        <span>View all Faqs</span>
                    </a>
                </div>
                <div class="w-full lg:w-[calc(100%_-_23rem)]">
                    @foreach($faqs as $faq)
                    <div class="border-b border-gray-30" x-data="{ open: false }">
                        <button role="tab" @click="open = !open" class="group gap-2 flex w-full items-baseline justify-between !pt-0 xl:py-8 lg:py-7 md:py-6 py-5 font-semibold lg:text-[22px] md:text-xl xxs:text-lg text-base">
                            <span class="text-left">{{ $faq->title }}</span>
                            <span x-cloak :class="{'rotate-180': open}" class="transition-transform">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="10" viewbox="0 0 16.366 9.819" fill="#000" class="md:h-3 md:w-5" aria-hidden="true">
                                    <path id="Icon_ion-ios-arrow-right_15" data-name="Icon ion-ios-arrow-right" d="M10.625,7.367l1.059-.992,8.761,8.183-8.761,8.183-1.059-.987,7.7-7.2Z" transform="translate(22.741 -10.625) rotate(90)"></path>
                                </svg>
                            </span>
                        </button>
                        <div role="tabpanel" x-show="open" x-cloak>
                            <div class="xl:pb-8 lg:pb-7 md:pb-6 pb-5">
                                {{ $faq->description }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                    <a href="{{ route('faqs') }}" class="btn btn-primary w-max flex mt-8 lg:hidden">
                        <span>View all Faqs</span>
                    </a>
                </div>
            </section>
        </div>
    </div>
</div>