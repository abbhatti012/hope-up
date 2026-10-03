@php
    $seenInImages = DB::table('setting_gallery')->where('type',2)->get();
@endphp
@if($seenInImages)
<div class="row large-columns-6 medium-columns-3 small-columns-2 slider row-slider slider-nav-simple"  data-flickity-options='{&quot;imagesLoaded&quot;: true, &quot;groupCells&quot;: &quot;100%&quot;, &quot;dragThreshold&quot; : 5, &quot;cellAlign&quot;: &quot;left&quot;,&quot;wrapAround&quot;: true,&quot;prevNextButtons&quot;: true,&quot;percentPosition&quot;: true,&quot;pageDots&quot;: true, &quot;rightToLeft&quot;: false, &quot;autoPlay&quot; : 4000}' >
    <div data-content-type="html" data-appearance="default" data-element="main" data-decoded="true">
        <div class="container">
            <div class="lg:py-8 md:py-8 py-8 relative" >
                <h3 class="text-center mb-8 sm:mb-9 sm:text-[28px] text-[26px] md:text-3xl lg:text-[33px] xl:text-4xl org-text">AS SEEN IN</h3>
                <div class="seenlogo-swiper swipe overflow-hidden" id="seen_logo_6687b8c00f60f" x-effect="initSlider">
                    <div class="swiper-wrapper">
                    @forelse($seenInImages as $seenImage)
                        <div class="swiper-slide 2xl:px-7 xl:px-6 lg:px-[18px] md:px-5 xxs:px-4 px-[18px] pb-3">
                              <span class="overflow-hidden">
                                    <img class="hover:scale-110 hover:transform transition ease-out duration-700" src="{{ asset($seenImage->images) }}" alt="metro">
                              </span>
                        </div>
                     @empty
                     @endforelse
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
