<div data-content-type="row" data-appearance="contained" data-element="main">
    <div data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src data-element="inner" data-pb-style="CKNRXTT">
        <div data-content-type="html" data-appearance="default" data-element="main" data-decoded="true">
            <div class="md:py-16 sm:py-14 py-12 hidden md:block">
                <h3 class="text-center mb-8 sm:mb-9 sm:text-[28px] text-[26px] md:text-3xl lg:text-[33px] xl:text-4xl xl:leading-[50px] org-text">LUMINANT CATEGORIES</h3>
                <div id="feature-category-items" class="items grid xl:grid-cols-10 md:grid-cols-7 sm:grid-cols-4 grid-cols-3 gap-3 sm:gap-4 xl:gap-5">
                    <div class="item">
                        <a href="{{ route('brands') }}" class="block text-center group/image">
                            <img src="{{ asset('public_assets/media/categories/brands-1.jpg.webp') }}" alt="Brands" class="lazy-load mb-2 block border-2 border-gray-102 rounded-full overflow-hidden group-hover/image:border-bronze-10" height="123px" width="123px">
                            <span class="xl:text-[17px] text-[15px] font-semibold mt-2.5 leading-[22px] block group-hover/image:text-bronze-10">Brands</span>
                        </a>
                    </div>
                    @forelse($categories ?? [] as $category)
                        <div class="item">
                            <a href="{{ route('categories', ['slug' => $category->cat_slug]) }}" class="block text-center group/image">
                                <img src="{{ asset($category->cat_image) }}" alt="{{ $category->cat_title }}" class="lazy-load mb-2 block border-2 border-gray-102 rounded-full overflow-hidden group-hover/image:border-bronze-10" height="123px" width="123px">
                                <span class="xl:text-[17px] text-[15px] font-semibold mt-2.5 leading-[22px] block group-hover/image:text-bronze-10">{{ $category->cat_title }}</span>
                            </a>
                        </div>
                    @empty
                    @endforelse
                </div>
            </div>
            <div class="py-10 md:hidden">
                <h3 class="text-center mb-8 sm:mb-9 sm:text-[28px] text-[26px] md:text-3xl lg:text-[33px] xl:text-4xl xl:leading-[50px] org-text">LUMINANT CATEGORIES</h3>
                <div id="feature-category-items" class="items grid xl:grid-cols-10 md:grid-cols-7 sm:grid-cols-4 grid-cols-3 gap-3 sm:gap-4 xl:gap-5">
                    <div class="item">
                        <a href="{{ route('brands') }}" class="block text-center group/image">
                            <img src="{{ asset('public_assets/media/categories/brands-1.jpg.webp') }}" alt="Brands" class="lazy-load mb-2 block border-2 border-gray-102 rounded-full overflow-hidden group-hover/image:border-bronze-10" height="123px" width="123px">
                            <span class="xl:text-[17px] text-[15px] font-semibold mt-2.5 leading-[22px] block group-hover/image:text-bronze-10">Brands</span>
                        </a>
                    </div>
                    @forelse($categories ?? [] as $category)
                        <div class="item">
                            <a href="{{ route('categories', ['slug' => $category->cat_slug]) }}" class="block text-center group/image">
                                <img src="{{ asset($category->cat_image) }}" alt="{{ $category->cat_title }}" class="lazy-load mb-2 block border-2 border-gray-102 rounded-full overflow-hidden group-hover/image:border-bronze-10" height="123px" width="123px">
                                <span class="xl:text-[17px] text-[15px] font-semibold mt-2.5 leading-[22px] block group-hover/image:text-bronze-10">{{ $category->cat_title }}</span>
                            </a>
                        </div>
                    @empty
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>