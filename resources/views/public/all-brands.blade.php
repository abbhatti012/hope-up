@extends('public.layout.layout')
<style>
   #filters-content{
      z-index: 9999 !important;
   }
   #filters-content ol {
      padding-left: 25px;
   }
</style>
@section('content')
        <div class="top-container">
            <nav class="breadcrumbs block" aria-label="Breadcrumb">
               <div class="container">
                  <ul class="items list-reset pt-5 md:pt-6 lg:pt-7 mb-0 rounded flex flex-wrap text-gray-105 text-sm">
                     <li class="item hidden md:flex home">
                        <a href="/" title="Go&#x20;to&#x20;Home&#x20;Page" class="hover:text-black">Home</a>
                     </li>
                     <li class="item hidden md:flex category9">
                        <span aria-hidden="true" class="separator my-auto px-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="6" height="10" viewBox="0 0 8.848 14.746" fill="#fff" class="fill-black" aria-hidden="true">
                              <path id="Icon_ion-ios-arrow-right_16" data-name="Icon ion-ios-arrow-right" d="M10.625,7.269l.954-.894,7.894,7.373-7.894,7.373-.954-.889,6.935-6.484Z" transform="translate(-10.625 -6.375)" />
                           </svg>
                        </span>
                        <a href="{{ route('brands') }}" title class="hover:text-black">Brands</a>
                     </li>
                     <li class="item hidden md:flex category44">
                        <span aria-hidden="true" class="separator my-auto px-2">
                           <svg xmlns="http://www.w3.org/2000/svg" width="6" height="10" viewBox="0 0 8.848 14.746" fill="#fff" class="fill-black" aria-hidden="true">
                              <path id="Icon_ion-ios-arrow-right_17" data-name="Icon ion-ios-arrow-right" d="M10.625,7.269l.954-.894,7.894,7.373-7.894,7.373-.954-.889,6.935-6.484Z" transform="translate(-10.625 -6.375)" />
                           </svg>
                        </span>
                        <b><a href="javascript:void(0)" title class="hover:text-black"><?php if($brand) { echo $brand->brand_title; } ?></a></b>
                     </li>
                  </ul>
               </div>
            </nav>
         </div>
        <main id="maincontent" class="page-main">
            <div id="contentarea" tabindex="-1"></div>
                <div class="container&#x20;flex&#x20;flex-col&#x20;md&#x3A;flex-row&#x20;flex-wrap&#x20;font-semibold&#x20;mt-3&#x20;mb-5&#x20;md&#x3A;mt-5&#x20;md&#x3A;mb-7&#x20;text-3xl">
                <h1 class="page-title title-font mb-0">
                <?php if($brand) { echo $brand->brand_title; } ?>
                </h1>
            </div>
           
            <div class="columns">
               <aside class="sidebar sidebar-main">
                  <div class="md:mt-0" role="region" aria-label="Product&#x20;filters" x-data="initLayeredNavigation()" x-bind="eventListeners" x-init="checkIsMobileResolution()" @resize.window.debounce="checkIsMobileResolution()" @visibilitychange.window.debounce="checkIsMobileResolution()">
                     <button type="button" class="block-title flex items-center text-start md:!hidden text-base font-semibold absolute  top-2.5" aria-controls="filters-content" :aria-expanded="blockOpen" :aria-disabled="!isMobile" :disabled="!isMobile ? '' : null">
                        <span class="px-1" x-ref="LayeredNavigationMobileToggleIcon">
                           <svg xmlns="http://www.w3.org/2000/svg" width="20" height="16" viewBox="0 0 16.609 15.048" stroke="#000" :class="{ 'stroke-bronze-10': blockOpen }" aria-hidden="true" focusable="false">
                              <path id="Icon_feather-filter" data-name="Icon feather-filter" d="M18.609,4.5H3l6.244,7.383v5.1l3.122,1.561V11.883Z" transform="translate(-2.5 -4)" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="1" />
                           </svg>
                        </span>
                        <span :class="{ 'text-bronze-10': blockOpen }">
                        Filter </span>
                     </button>
                     <div id="filters-content" class="block-content filter-content hidden md:block md:mt-4 fixed top-0 left-0 w-full h-screen md:bg-transparent z-50 md:relative md:w-auto md:h-auto bg-white md:z-0 overflow-y-scroll md:overflow-y-visible" :class="{ 'hidden': !blockOpen }">
                        <div class="flex justify-between border-b border-gray-30 pr-3 py-[15px] md:px-2.5 px-[18px]">
                           <span class="font-semibold text-[20px]">
                           Filter By </span>
                           <button class="md:hidden closeIcon">
                              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 xxs:w-auto" width="30" height="27" aria-hidden="true">
                                 <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                              </svg>
                           </button>
                        </div>
                        <div class="filters-content-box">
                            <div class="filter-options-content pt-2">
                                <div class="flex justify-between pr-3 py-[15px] md:px-2.5 px-[18px]">
                                <span class="font-semibold text-[20px]">Brands</span>
                                </div>
                                <div data-mst-nav-filter="c2c_ceiling_finish_colourA792987A" data-mst-nav-cache-key="792987" data-mst-attribute="c2c_ceiling_finish_colour" class="mst-nav__label">
                                <div data-holder="alphabetical" x-data="initAlphabeticalIndex(0, 5)" x-init="init()"></div>
                                    <ol class="items max-h-56 overflow-y-auto kes-scrollbar">
                                       @forelse($brands as $brand)
                                          <a href="javascript:void(0)" class="filter-item_{{ $brand->id }}" rel="follow" aria-label="Aged Zinc">
                                             <li data-element="filter" style="cursor: pointer;" class="item mst-nav__label-item _mode-simple_checkbox !py-[5px] pl-1 text-[17px]">
                                                <input id="brand_title{{ $brand->id }}" type="checkbox" <?php echo (request()->get('filter') === $brand->brand_slug) ? 'checked' : '' ?> />
                                                <label for="brand_title{{ $brand->id }}">
                                                   <span class="mst-nav__label-item__label">{{ $brand->brand_title }}</span>
                                                </label>
                                                <small class="ml-auto">({{ $brand->products_count }})</small>
                                             </li>
                                          </a>
                                          <script>
                                             document.querySelectorAll('.filter-item_{{ $brand->id }} input[type="checkbox"]').forEach(function(checkbox) {
                                                checkbox.addEventListener('change', function(event) {
                                                   if (checkbox.checked) {
                                                      <?php $route = route('brands', ['filter' => $brand->brand_slug, 'limit' => $limit, 'sort' => $sort]); ?>
                                                      window.location.href = '<?= $route; ?>';
                                                   } else {
                                                      <?php $route = route('brands', ['filter' => '', 'limit' => $limit, 'sort' => $sort]); ?>
                                                      window.location.href = '<?= $route; ?>';
                                                   }
                                                });
                                             });
                                          </script>
                                       @empty
                                       @endforelse
                                    </ol>
                                </div>
                            </div>
                        </div>
                        <div class="filters-content-box">
                           <div class="filter-options-content pt-2">
                              <div class="flex justify-between pr-3 py-[15px] md:px-2.5 px-[18px]">
                                    <a href="{{ route('all-products') }}">
                                       <span style="color: #ffA500" class="font-semibold text-[20px]">View All Products</span>
                                    </a>
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#ffA500" viewBox="0 0 24 24" class="ml-2">
                                       <path d="M8 5v14l11-7z"/>
                                    </svg>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <script>
                     function initLayeredNavigation() {
                         return {
                             isMobile: false,
                             blockOpen: false,
                             init() {
                                 this.checkIsMobileResolution();
                             },
                             checkIsMobileResolution() {
                                 const mobileElement = this.$refs.LayeredNavigationMobileToggleIcon;
                                 this.isMobile = mobileElement
                                     ? getComputedStyle(mobileElement).display !== "none"
                                     : window.matchMedia('(max-width: 767px)').matches;
                             },
                             eventListeners: {
                                 ['@resize.window.debounce']() {
                                     this.checkIsMobileResolution();
                                 },
                                 ['@visibilitychange.window.debounce']() {
                                     this.checkIsMobileResolution();
                                 },
                             },
                         }
                     }
                  </script>
               </aside>
               <div class="column main">
                  <div class="mst-nav__horizontal-bar">
                  </div>
                  <div id="m-navigation-replacer"></div>
                  <section class="md:pt-8 product-list" id="product-list" aria-label="Product&#x20;list" tabindex="-1">
                     <div class="toolbar toolbar-products grid grid-cols-8 md:grid-cols-4
                        lg:grid-cols-8 grid-flow-row gap-2 items-center">
                        <nav class="justify-end md:justify-start tracking-wide flex gap-1 items-center leading-5 lg:col-span-2 md:col-span-1 col-span-4 xxs:mr-4 order-1 text-[15px]" aria-label="Products&#x20;Count">
                           Items <span class="toolbar-number"><?php if($products) { echo count($products); } else { echo count($brands); } ?></span> 
                        </nav>
                        <div class="toolbar-sorter sorter flex items-center order-1 col-span-4 md:col-span-3 lg:col-span-6 justify-end">   
                           <form id="sortForm" method="GET" action="{{ route('brands') }}">
                              <label class="inline-block mr-3">
                                 <span class="text-[15px] pr-3.5 lg:inline hidden">Sort&#x20;By&#x3A;</span>
                                 <input type="hidden" name="filter" value="{{ $brandSlug }}">
                                 <input type="hidden" name="limit" value="{{ $limit }}">
                                 <input type="hidden" name="sort" value="{{ $sort }}">
                                 <select name="sort" class="form-select sorter-options md:w-auto w-full !bg-transparent !min-h-[38px]" 
                                       onchange="document.getElementById('sortForm').submit();">
                                    <option value="asc" <?php echo $sort == 'asc' ? 'selected' : ''; ?>>
                                       Ascending 
                                    </option>
                                    <option value="desc" <?php echo $sort == 'desc' ? 'selected' : ''; ?>>
                                       Descending 
                                    </option>
                                 </select>

                              </label>
                           </form>
                        </div>

                     </div>
                     <div class="products wrapper mode-grid products-grid">
                        <ul role="list" class="mx-auto md:pt-6 pt-7 grid xl:gap-x-6 lg:gap-x-5 sm:gap-x-4 gap-x-3 xl:gap-y-10 lg:gap-y-7 sm:gap-y-6 gap-y-4 grid-cols-1 xxs:grid-cols-2 lg:grid-cols-3">
                           @if($products)
                              @include('public.products-listing', ['products' => $products])
                           @endif
                           
                           @if(!$products)
                              @include('public.brands-listing', ['brands' => $brands])
                           @endif
                        </ul>
                     </div>
                    <div id="load-more-container">
                        @if($products && $products->count() == $limit)
                           <form method="GET" action="{{ route('brands') }}">
                              <input type="hidden" name="filter" value="{{ $brandSlug }}">
                              <input type="hidden" name="limit" value="{{ $limit }}">
                              <input type="hidden" name="sort" value="{{ $sort }}">
                              <input type="hidden" name="isLoad" value="1">
                              <button type="submit" class="btn btn-secondary m-auto mst-scroll__button _next" data-page="2">Load More</button>
                           </form>
                        @endif
                  </div>
                  </section>
               </div>
            </div>
        </div>
      </main>
@endsection
@section('scripts')
   <script>
      document.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
         checkbox.addEventListener('change', function() {
               // Submit the form when a checkbox is clicked
               document.getElementById('filterForm').submit();
         });
      });

      const filterButton = document.querySelector('.block-title');
      const closeButton = document.querySelector('.closeIcon');

      const filterContent = document.getElementById('filters-content');

      filterButton.addEventListener('click', function() {
         filterContent.classList.remove('hidden'); // Show the filters
      });

      closeButton.addEventListener('click', function() {
         filterContent.classList.add('hidden'); // Hide the filters
      });
   </script>
@endsection