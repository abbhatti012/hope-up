<style>
    .flickity-viewport{
        height: 400px;
    }
</style>
<div class="relative">
    <h3 class="text-center mb-8 sm:mb-9 sm:text-[28px] text-[26px] md:text-3xl lg:text-[33px] xl:text-4xl org-text">MORE FROM THIS BRAND</h3>
    <div class="row large-columns-6 medium-columns-3 small-columns-2 row-small slider row-slider slider-nav-simple slider-nav-light flickity-enabled" data-flickity-options="{&quot;imagesLoaded&quot;: true, &quot;groupCells&quot;: &quot;100%&quot;, &quot;dragThreshold&quot; : 5, &quot;cellAlign&quot;: &quot;left&quot;,&quot;wrapAround&quot;: true,&quot;prevNextButtons&quot;: true,&quot;percentPosition&quot;: true,&quot;pageDots&quot;: true, &quot;rightToLeft&quot;: false, &quot;autoPlay&quot; : 4000}" tabindex="0">
        @forelse($products as $product)
            <div class="product-category col" aria-hidden="true">
                <div class="col-inner">
                    <a aria-label="{{ $product->title }}" href="{{ route('product', ['slug' => $product->slug]) }}">
                        <div class="box box-category has-hover box-none">
                            <div class="box-image">
                                <div class="image-glow image-cover">
                                    <img style="width: 300px; height: 300px;" decoding="async" src="{{ asset($product->image) }}" alt="{{ $product->title }}" width="300" height="300" title="{{ $product->title }}">                                                      
                                </div>
                            </div>
                            <div class="box-text text-center mt-10">
                                <div class="box-text-inner">
                                    <h5 class="uppercase header-title" title="{{ $product->title }}">
                                        {{-- Display the truncated title (20 characters) --}}
                                        {{ Str::limit($product->title, 20) }}
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        @empty
            <p>No products found.</p>
        @endforelse
     
        <!-- Flickity Navigation -->
        <button class="flickity-button flickity-prev-next-button previous" type="button" aria-label="Previous">
            <svg class="flickity-button-icon" viewBox="0 0 100 100">
                <path d="M 10,50 L 60,100 L 70,90 L 30,50  L 70,10 L 60,0 Z" class="arrow"></path>
            </svg>
        </button>
        <button class="flickity-button flickity-prev-next-button next" type="button" aria-label="Next">
            <svg class="flickity-button-icon" viewBox="0 0 100 100">
                <path d="M 10,50 L 60,100 L 70,90 L 30,50  L 70,10 L 60,0 Z" class="arrow" transform="translate(100, 100) rotate(180)"></path>
            </svg>
        </button>
        <ol class="flickity-page-dots">
            <li class="dot" aria-label="Page dot 1"></li>
            <li class="dot is-selected" aria-label="Page dot 2" aria-current="step"></li>
        </ol>
    </div>
</div><br>
