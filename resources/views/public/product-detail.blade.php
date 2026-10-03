@extends('public.layout.layout')
<script>
         'use strict';
         (function( hyva, undefined ) {
         
             const formatStr = function (str, nStart) {
                 const args = Array.from(arguments).slice(2);
         
                 return str.replace(/(%+)([0-9]+)/g, (m, p, n) => {
                     const idx = parseInt(n) - nStart;
         
                     if (args[idx] === null || args[idx] === void 0) {
                         return m;
                     }
                     return p.length % 2
                         ? p.slice(0, -1).replace('%%', '%') + args[idx]
                         : p.replace('%%', '%') + n;
                 })
             }
         
             hyva.str = function (string) {
                 const args = Array.from(arguments);
                 args.splice(1, 0, 1);
         
                 return formatStr.apply(undefined, args);
             }
         
             hyva.strf = function () {
                 const args = Array.from(arguments);
                 args.splice(1, 0, 0);
         
                 return formatStr.apply(undefined, args);
             }
         
           
         }( window.hyva = window.hyva || {} ));
      </script>
@section('content')
    <div class="top-container">
         <nav class="breadcrumbs block" aria-label="Breadcrumb">
            <div class="container">
               <ul class="items list-reset pt-5 md:pt-6 lg:pt-7 mb-0 rounded flex flex-wrap text-gray-105 text-sm">
                  <li class="item hidden md:flex home">
                     <a href="./" title="Go&#x20;to&#x20;Home&#x20;Page" class="hover:text-black">Home</a>
                  </li>
                  @if($product->brand)
                    <li class="item hidden md:flex category998">
                        <span aria-hidden="true" class="separator my-auto px-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="10" viewBox="0 0 8.848 14.746" fill="#fff" class="fill-black" aria-hidden="true">
                            <path id="Icon_ion-ios-arrow-right_15" data-name="Icon ion-ios-arrow-right" d="M10.625,7.269l.954-.894,7.894,7.373-7.894,7.373-.954-.889,6.935-6.484Z" transform="translate(-10.625 -6.375)" />
                            </svg>
                        </span>
                        <a href="{{ route('brands', ['filter' => $product->brand->brand_slug]) }}" title class="hover:text-black">{{ $product->brand->brand_title }}</a>
                    </li>
                  @else
                    <li class="item hidden md:flex category998">
                        <span aria-hidden="true" class="separator my-auto px-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="6" height="10" viewBox="0 0 8.848 14.746" fill="#fff" class="fill-black" aria-hidden="true">
                            <path id="Icon_ion-ios-arrow-right_15" data-name="Icon ion-ios-arrow-right" d="M10.625,7.269l.954-.894,7.894,7.373-7.894,7.373-.954-.889,6.935-6.484Z" transform="translate(-10.625 -6.375)" />
                            </svg>
                        </span>
                        <a href="{{ route('brands') }}" title class="hover:text-black">Brands</a>
                    </li>
                  @endif
                  @if($product->category)
                  <li class="item hidden md:flex category999">
                     <span aria-hidden="true" class="separator my-auto px-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="6" height="10" viewBox="0 0 8.848 14.746" fill="#fff" class="fill-black" aria-hidden="true">
                           <path id="Icon_ion-ios-arrow-right_16" data-name="Icon ion-ios-arrow-right" d="M10.625,7.269l.954-.894,7.894,7.373-7.894,7.373-.954-.889,6.935-6.484Z" transform="translate(-10.625 -6.375)" />
                        </svg>
                     </span>
                     <a href="{{ route('categories', ['slug' => $product->category->cat_slug]) }}" title class="hover:text-black">{{ $product->category->cat_title }} </a>
                  </li>
                  @endif
                  @if($product->subCategory)
                  <li class="item hidden md:flex category1195">
                     <span aria-hidden="true" class="separator my-auto px-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="6" height="10" viewBox="0 0 8.848 14.746" fill="#fff" class="fill-black" aria-hidden="true">
                           <path id="Icon_ion-ios-arrow-right_17" data-name="Icon ion-ios-arrow-right" d="M10.625,7.269l.954-.894,7.894,7.373-7.894,7.373-.954-.889,6.935-6.484Z" transform="translate(-10.625 -6.375)" />
                        </svg>
                     </span>
                     <a href="{{ route('categories', ['slug' => $product->category->cat_slug, 'filter' => $product->subCategory->subcat_slug ]) }}" title class="hover:text-black">{{ $product->subCategory->subcat_title }}</a>
                  </li>
                  @endif
                  @if($product->title)
                  <li class="item hidden md:flex product">
                     <span aria-hidden="true" class="separator my-auto px-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="6" height="10" viewBox="0 0 8.848 14.746" fill="#fff" class="fill-black" aria-hidden="true">
                           <path id="Icon_ion-ios-arrow-right_18" data-name="Icon ion-ios-arrow-right" d="M10.625,7.269l.954-.894,7.894,7.373-7.894,7.373-.954-.889,6.935-6.484Z" transform="translate(-10.625 -6.375)" />
                        </svg>
                     </span>
                     <span class="text-black" aria-current="page">{{ $product->title }}</span>
                  </li>
                  @endif
               </ul>
            </div>
         </nav>
      </div>
      <main id="maincontent" class="page-main">
         <div class="columns">
            <div class="column main">
               <div class="flex items-end p-3 fixed md:hidden bottom-0 z-40 bg-white w-full left-0 border-t border-gray-30">
                  <form method="post" action="" class="product_addtocart_form flex w-full" id="product_sticky_form" x-data @submit="$dispatch('product-add-to-cart')">
                     <button type="button" form="product_sticky_form" title="Add to Basket" class="btn btn-primary lg:text-lg h-12 md:h-14 mx-2 md:mx-4 justify-center w-full px-1" data-addto="cart">
                        <span class="relative z-10">Add to Basket </span>
                     </button>
                     <div class="flex">
                        <button x-defer="intersect" @click.prevent="addToWishlist(22971)" title="Wish List" aria-label="Wish List" id="add-to-wishlist" class="group/wishlist md:w-14 w-12 h-12 md:h-14 inline-flex items-center justify-center border border-bronze-10 hover:bg-bronze-10"
                           data-addto="wishlist">
                           <svg xmlns="http://www.w3.org/2000/svg" width="26" height="24" viewBox="0 0 12.594 11.312" class="w-7 h-6 group-hover/wishlist:fill-white fill-bronze-10" role="img">
                              <g id="Icon_akar-heart" data-name="Icon akar-heart" transform="translate(-606.976 -278.715)">
                                 <path id="Path_29_3" data-name="Path 29" d="M613.273,290.027a.954.954,0,0,1-.494-.138c-5.05-3.105-5.8-6.264-5.8-7.871a3.323,3.323,0,0,1,3.318-3.3h.016a4.387,4.387,0,0,1,2.962,1.577,4.394,4.394,0,0,1,2.965-1.577h.017a3.322,3.322,0,0,1,3.316,3.3c0,1.609-.753,4.768-5.8,7.871A.956.956,0,0,1,613.273,290.027Zm-2.979-10.578a2.588,2.588,0,0,0-2.584,2.571c0,1.452.708,4.326,5.451,7.242a.219.219,0,0,0,.225,0c4.742-2.915,5.45-5.789,5.45-7.244a2.587,2.587,0,0,0-2.584-2.569h-.013c-1.439,0-2.662,1.616-2.674,1.632a.368.368,0,0,1-.294.148h0a.368.368,0,0,1-.294-.148c-.012-.016-1.238-1.632-2.668-1.632Z"
                                    />
                              </g>
                              <title>heart</title>
                           </svg>
                        </button>
                     </div>
                  </form>
               </div>
               <div class="product-info-main">
                  <div x-init="trackKlaviyoProductView()"></div>
                  <section class="body-font">
                     <div class="flex md:pt-8 lg:flex-row flex-col items-center">
                        <div class="items-start grid grid-rows-auto 
                           lg:gap-x-7 xl:gap-x-12 2xl:gap-x-16
                           md:grid-rows-[min-content_minmax(0,_1fr)] 
                           grid-cols-1   
                           lg:grid-cols-2 
                           xl:grid-cols-[45%_minmax(0,_1fr)] 
                           w-full">
                           <div class="container&#x20;flex&#x20;flex-col&#x20;md&#x3A;flex-row&#x20;flex-wrap&#x20;font-semibold&#x20;mb-5&#x20;md&#x3A;mb-7&#x20;text-left&#x20;p-0&#x20;&#x21;m-0">
                              <h1 class="page-title title-font mb-0">
                                 <span class="base" data-ui-id="page-title-wrapper">{{ $product->title }}</span> 
                              </h1>
                           </div>
                           <div id="gallery" x-data="initGallery()" x-bind="eventListeners" class="lg:sticky lg:top-8 w-full pt-6 md:pt-0 md:h-auto md:row-start-1 row-start-1 md:row-span-2 md:col-start-1 mb-7 md:mb-10" :class="{'lg:sticky z-[2]': !fullscreen}">
                              <div class="relative grid gap-y-6 grid-cols-1 grid-rows-[auto_var(--thumbs-size)] [--thumbs-gap:theme('spacing.2')] lg:[--thumbs-gap:theme('spacing.4')]
                                 lg:gap-y-0 lg:gap-x-5 lg:grid-cols-[var(--thumbs-size)_1fr] lg:grid-rows-1" style="--thumbs-size: 90px;" :class="{
                                 'w-full h-full fixed top-0 left-0 bg-white z-50 flex': fullscreen,
                                 'relative': !fullscreen
                                 }" :role="fullscreen ? 'dialog' : false" :aria-modal="fullscreen" :aria-label="fullscreen ? 'Gallery\u0020modal\u0020fullscreen' : false">
                                 <div class="relative self-center w-full lg:col-start-2" @touchstart="handleTouchStart" @touchmove="handleTouchMove" x-transition:enter="ease-out duration-500" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                                    <div class="relative" aria-live="polite" aria-atomic="true">
                                       <img alt="{{ $product->title }}" title="{{ $product->title }}" class="object-contain object-center w-full h-auto max-h-screen-75"
                                          :class="'invisible'" src="{{ asset($product->image) }}" width="700" height="700" />
                                       <template x-for="(image, index) in images" :key="index">
                                          <img :alt="image.caption || 'Caspen\u0020Sobrado\u00206\u0020Light\u0020Pendant\u0020Light\u0020Antique\u0020Brass\u0020Opal\u0020Glass'" :title="image.caption || 'Caspen\u0020Sobrado\u00206\u0020Light\u0020Pendant\u0020Light\u0020Antique\u0020Brass\u0020Opal\u0020Glass'"
                                             class="absolute inset-0 object-contain object-center w-full m-auto max-h-screen-75" width="700" height="700" :loading="active !== index ? 'lazy' : 'eager'" :src="fullscreen ? image.full : image.img"
                                             x-transition:enter.opacity.duration.500ms x-transition:leave.opacity.duration.0ms x-show="active === index" />
                                       </template>
                                       <button type="button" class="absolute inset-0 w-full outline-offset-2" aria-label="Click to view image in fullscreen" x-ref="galleryFullscreenBtn" x-show="!fullscreen && images[active].type !== 'video'" x-cloak @click="openFullscreen($root)" @keydown.enter="openFullscreen($root)"></button>
                                       <button type="button" class="group absolute inset-0 w-full outline-offset-2 grid place-items-center" aria-label="Click to play video" x-show="images[active].type === 'video' && !activeVideoType" x-cloak @click="activateVideo()" @keydown.enter="activateVideo()">
                                          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="stroke-white/75 fill-black/20 transition ease-in group-hover:scale-110 md:w-24 md:h-24" width="44" height="44" aria-hidden="true">
                                             <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                                          </svg>
                                       </button>
                                       <div class="absolute inset-0 w-full h-full bg-white nonmobile" x-transition:enter.opacity.duration.500ms x-transition:leave.opacity.duration.0ms x-show="images[active].type === 'video' && activeVideoType === 'youtube'" x-cloak>
                                          <div id="youtube-player" class="w-full h-full"></div>
                                       </div>
                                       <div class="absolute inset-0 w-full h-full bg-white" x-transition:enter.opacity.duration.500ms x-transition:leave.opacity.duration.0ms x-show="images[active].type === 'video' && activeVideoType === 'vimeo'" x-cloak>
                                          <div id="vimeo-player" class="w-full h-full"></div>
                                       </div>
                                    </div>
                                 </div>
                                 <div @resize.window.debounce="calcResize();">
                                    <div id="thumbs" class="flex justify-center items-center lg:absolute lg:inset-y-0 lg:col-start-1 lg:flex-col" :class="{ 'mx-6 lg:mx-[var(--thumbs-gap)] lg:my-6': fullscreen }" x-show="images.length > 1" x-cloak>
                                       <button type="button" aria-label="Previous" tabindex="-1" class="text-black md:p-2 outline-none focus:outline-none flex-none" :class="{ 'opacity-25 pointer-events-none' : isSliderStart, 'hidden' : !isSlider }" aria-hidden="true" @click="scrollPrevious">
                                          <svg xmlns="http://www.w3.org/2000/svg" version="1.2" viewBox="0 0 18 12" width="24" height="24" class="hidden lg:block" role="img">
                                             <path stroke="#000" d="m2.3 11.3l-1-1.1 8-8.6 8 8.6-0.9 1.1-7.1-7.6z" />
                                             <title>chevron-up</title>
                                          </svg>
                                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="block lg:hidden" width="24" height="24" role="img">
                                             <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                             <title>chevron-left</title>
                                          </svg>
                                       </button>
                                       <div class="js_thumbs_slides thumbs-wrapper group outline-none relative overflow-auto overscroll-contain js_slides snap
                                          flex lg:flex-col                                                                     " x-ref="jsThumbSlides" @scroll.debounce="calcScrollStartEnd(); calcActive($event)">
                                          <template x-for="(image, index) in images" :key="index">
                                             <div class="js_thumbs_slide flex shrink-0 mb-2 mr-[var(--thumbs-gap)] last:mr-0 lg:mb-[var(--thumbs-gap)] lg:last:mb-0 lg:mr-0">
                                                <button type="button" @click.prevent="setActive(index);" class="relative block border-2 border-gray-300 hover:border-bronze-10 focus:border-bronze-10" :class="{
                                                   'border-bronze-10 group-focus:border-gray-500': active === index,
                                                   'border-gray-300': active !== index
                                                   }">
                                                   <span class="sr-only">
                                                   View larger image </span>
                                                   <span class="absolute inset-0 grid place-items-center" x-show="image.type === 'video'">
                                                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="stroke-white/75 fill-black/20" width="44" height="44" aria-hidden="true">
                                                         <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                      </svg>
                                                   </span>
                                                   <img :src="image.thumb" :alt="hyva.str('%1 thumbnail', image.caption) || 'Caspen\u0020Sobrado\u00206\u0020Light\u0020Pendant\u0020Light\u0020Antique\u0020Brass\u0020Opal\u0020Glass\u0020thumbnail'" :title="hyva.str('%1 thumbnail', image.caption) || 'Caspen\u0020Sobrado\u00206\u0020Light\u0020Pendant\u0020Light\u0020Antique\u0020Brass\u0020Opal\u0020Glass\u0020thumbnail'"
                                                      width="90" height="90" loading="lazy" />
                                                </button>
                                             </div>
                                          </template>
                                       </div>
                                       <button type="button" x-show="images.length > 1" x-cloak aria-label="Next" tabindex="-1" class="text-black md:p-2 outline-none focus:outline-none flex-none" :class="{ 'opacity-25 pointer-events-none' : isSliderEnd, 'hidden' : !isSlider }" aria-hidden="true"
                                          @click="scrollNext">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 17.406 11.087" stroke="#000" class="hidden lg:block" role="img">
                                             <path id="Icon_ion-ios-arrow-right_19" data-name="Icon ion-ios-arrow-right" d="M10.625,7.347l1.037-.972,8.585,8.019-8.585,8.019-1.037-.967,7.542-7.051Z" transform="translate(23.096 -9.892) rotate(90)" stroke-width="1" />
                                             <title>chevron-down</title>
                                          </svg>
                                          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="block lg:hidden" width="24" height="24" role="img">
                                             <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                             <title>chevron-right</title>
                                          </svg>
                                       </button>
                                    </div>
                                 </div>
                                 <div class="absolute top-0 right-0 pt-4 pr-4">
                                    <button @click="closeFullScreen($root)" type="button" class="hidden text-gray-500 p-3 hover:text-gray-600 focus:text-gray-600
                                       transition ease-in-out duration-150" :class="{ 'hidden': !fullscreen, 'block': fullscreen }" aria-label="Close&#x20;fullscreen">
                                       <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="24" height="24" aria-hidden="true">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                       </svg>
                                    </button>
                                 </div>
                              </div>
                           </div>
                           <script>
                              function initGallery () {
                                  let touchXDown, touchYDown;
                              
                                  return {
                                      "active": 0,
                                      "videoData": {},
                                      "activeVideoType": false,
                                      "autoplayVideo": false,
                                      "loopVideo": false,
                                      "relatedVideos": false,
                                      "vimeoPlayer": null,
                                      "fullscreen": false,
                                      "isSlider": false,
                                      "images": [
                                        {
                                            "thumb": "{{ asset($product->image) }}",  // replace 'thumb' with the correct field name for the thumbnail
                                            "img": "{{ asset($product->image) }}",      // replace 'img' with the correct field name for the main image
                                            "full": "{{ asset($product->image) }}",    // replace 'full' with the correct field name for the full image
                                            "caption": "{{ $product->image }}",     // replace 'caption' with the correct field name for the caption (if any)
                                            "position": 0,
                                            "isMain": true, // set 'isMain' to true for the first image, false otherwise
                                            "type": "image",
                                            "videoUrl": null,
                                            "thumb_webp": "{{ asset($product->image) }}", // replace with your webp field (if available)
                                            "img_webp": "{{ asset($product->image) }}",     // replace with your webp field (if available)
                                            "full_webp": "{{ asset($product->image) }}"    // replace with your webp field (if available)
                                        },
                                        @forelse($product->gallery as $image)
                                            {
                                                "thumb": "{{ asset($image->image) }}",  // replace 'thumb' with the correct field name for the thumbnail
                                                "img": "{{ asset($image->image) }}",      // replace 'img' with the correct field name for the main image
                                                "full": "{{ asset($image->image) }}",    // replace 'full' with the correct field name for the full image
                                                "caption": "{{ $image->image }}",     // replace 'caption' with the correct field name for the caption (if any)
                                                "position": "{{ $loop->index }}",       // dynamically set the position based on the loop index
                                                "isMain": false, // set 'isMain' to true for the first image, false otherwise
                                                "type": "image",
                                                "videoUrl": null,
                                                "thumb_webp": "{{ asset($image->image) }}", // replace with your webp field (if available)
                                                "img_webp": "{{ asset($image->image) }}",     // replace with your webp field (if available)
                                                "full_webp": "{{ asset($image->image) }}"    // replace with your webp field (if available)
                                            }@if(!$loop->last),@endif
                                        @empty
                                        @endforelse
                                      ],
                                      "appendOnReceiveImages": true,
                                      "activeSlide": 0,
                                      "isSliderStart": true,
                                      "isSliderEnd": true,
                                      "itemCount": 0,
                                      "pageSize": 4,
                                      "pageFillers": 0,
                                      "focusTrapListener": null,
                                      init() {
                                          this.itemCount = this.images.length;
                                          this.initActive();
                                          this.$nextTick(() => {
                                              this.calcIsSlider();
                                          });
                              
                                          this.$watch('fullscreen', open => {
                                              this.scrollLock(open);
                                              this.$nextTick(() => {
                                                  this.calcIsSlider();
                                                  this.calcPageSize();
                                                  this.calcScrollStartEnd();
                                              });
                                          });
                              
                                          this.$watch('isSlider', isSlider => {
                                              this.$nextTick(() => {
                                                  if (!isSlider) return;
                                                  this.calcPageSize();
                                                  this.calcScrollStartEnd();
                                              });
                                          });
                                      },
                                      receiveImages(images) {
                                          if (this.appendOnReceiveImages) {
                                              const initialUrls = this.initialImages.map(image => image.full);
                                              const newImages = images.filter(image => ! initialUrls.includes(image.full));
                                              this.images = [].concat(this.initialImages, newImages);
                                              this.setActive(newImages.length ? this.initialImages.length : 0);
                                          } else {
                                              this.images = images;
                                              this.setActiveAndScrollTo(0);
                                          }
                              
                                          this.$nextTick(() => {
                                              this.calcIsSlider();
                                              this.scrollTo(this.active);
                                          });
                              
                                          this.itemCount = this.images.length;
                                      },
                                      resetGallery() {
                                          this.images = this.initialImages;
                                          this.itemCount = this.images.length;
                                          this.initActive();
                                          this.calcIsSlider();
                              
                                          this.$nextTick(() => {
                                              this.scrollTo(this.active);
                                          });
                                      },
                                      initActive() {
                                          let active = this.images.findIndex(function(image) {
                                              return image.isMain === true
                                          });
                                          if (active === -1) {
                                              active = 0;
                                          }
                                          this.setActive(active);
                                      },
                                      setActive(index) {
                                          if (index < 0) return;
                                          if (index === this.itemCount) return;
                                          this.active = index;
                                          this.activeVideoType = false;
                                          if (window.youtubePlayer) {
                                              window.youtubePlayer.stopVideo();
                                          }
                                          if (this.vimeoPlayer) {
                                              this.vimeoPlayer.contentWindow.postMessage(JSON.stringify({"method": "pause"}), "*");
                                          }
                                          if (this.images[index].type === 'video' && this.autoplayVideo) {
                                              this.activateVideo();
                                          }
                                      },
                                      activateVideo() {
                                          const videoData = this.getVideoData();
                              
                                          if (!videoData) { return }
                              
                                          this.activeVideoType = videoData.type;
                              
                                          if (videoData.type === "youtube") {
                                              if (!window.youtubePlayer) {
                                                  this.initYoutubeAPI(videoData);
                                              } else {
                                                  window.youtubePlayer.loadVideoById(videoData.id);
                                              }
                              
                                          } else if (videoData.type === "vimeo") {
                                              this.initVimeoVideo(videoData);
                                          }
                                      },
                                      getVideoData() {
                                          const videoUrl = this.images[this.active] && this.images[this.active].videoUrl;
                              
                                          if (!videoUrl) { return }
                              
                                          let id,
                                              type,
                                              youtubeRegex,
                                              vimeoRegex,
                                              useYoutubeNoCookie = false;
                              
                                          if (videoUrl.match(/youtube\.com|youtu\.be|youtube-nocookie.com/)) {
                                              id = videoUrl.replace(/^\/(embed\/|v\/)?/, '').replace(/\/.*/, '');
                                              type = 'youtube';
                              
                                              youtubeRegex = /^.*(?:(?:youtu\.be\/|v\/|vi\/|u\/\w\/|embed\/)|(?:(?:watch)?\?v(?:i)?=|\&v(?:i)?=))([^#\&\?]*).*/;
                                              id = videoUrl.match(youtubeRegex)[1];
                              
                                              if (videoUrl.match(/youtube-nocookie.com/)) {
                                                  useYoutubeNoCookie = true;
                                              }
                                          } else if (videoUrl.match(/vimeo\.com/)) {
                                              type = 'vimeo';
                                              vimeoRegex = new RegExp(['https?:\\/\\/(?:www\\.|player\\.)?vimeo.com\\/(?:channels\\/(?:\\w+\\/)',
                                                  '?|groups\\/([^\\/]*)\\/videos\\/|album\\/(\\d+)\\/video\\/|video\\/|)(\\d+)(?:$|\\/|\\?)'
                                              ].join(''));
                                              id = videoUrl.match(vimeoRegex)[3];
                                          }
                              
                                          return id ? {
                                              id: id, type: type, useYoutubeNoCookie: useYoutubeNoCookie
                                          } : false;
                                      },
                                      initYoutubeAPI(videoData) {
                                          if (document.getElementById('loadYoutubeAPI')) {
                                              return;
                                          }
                                          const params = {
                                              "autoplay": true
                                          };
                                          const loadYoutubeAPI = document.createElement('script');
                                          loadYoutubeAPI.src = 'https://www.youtube.com/iframe_api';
                                          loadYoutubeAPI.id = 'loadYoutubeAPI';
                                          const firstScriptTag = document.getElementsByTagName('script')[0];
                                          firstScriptTag.parentNode.insertBefore(loadYoutubeAPI, firstScriptTag);
                              
                                          const host = (videoData.useYoutubeNoCookie) ?
                                              'https://www.youtube-nocookie.com' :
                                              'https://www.youtube.com';
                              
                                          if (!this.relatedVideos) {
                                              params.rel = 0;
                                          }
                                          const fireYoutubeAPI = document.createElement('script');
                                          fireYoutubeAPI.innerHTML = `function onYouTubeIframeAPIReady() {
                                              window.youtubePlayer = new YT.Player('youtube-player', {
                                                  host: '${host}',
                                                  videoId: '${videoData.id}',
                                                  playerVars: ${JSON.stringify(params)},
                                              });
                                          }`;
                                          firstScriptTag.parentNode.insertBefore(fireYoutubeAPI, firstScriptTag);
                                      },
                                      initVimeoVideo(videoData) {
                                          let additionalParams = '&autoplay=1';
                                          let src = '';
                              
                                          const timestamp = new Date().getTime();
                                          const vimeoContainer = document.getElementById("vimeo-player");
                                          const videoId = videoData.id;
                              
                                          if (!vimeoContainer || !videoId) return;
                              
                                          if (this.loopVideo) {
                                              additionalParams += '&loop=1';
                                          }
                                          src = 'https://player.vimeo.com/video/' +
                                              videoId + '?api=1&player_id=vimeo' +
                                              videoId +
                                              timestamp +
                                              additionalParams;
                                          vimeoContainer.innerHTML =
                                              `<iframe id="${'vimeo' + videoId + timestamp}"
                                                  src="${src}"
                                                  width="640" height="360"
                                                  webkitallowfullscreen
                                                  mozallowfullscreen
                                                  allowfullscreen
                                                  referrerPolicy="origin"
                                                  allow="autoplay"
                                                  class="object-center w-full h-full object-fit"
                                               />`;
                              
                                          this.vimeoPlayer = vimeoContainer.childNodes[0];
                                      },
                                      getSlider() {
                                          return this.$refs.jsThumbSlides;
                                      },
                                      getSliderSize() {
                                          let sliderGap = 0;
                                          let sliderSize = 0;
                                          let slideElSize = 0;
                              
                                          const slider = this.getSlider();
                                          const slideEl = slider && slider.querySelector('.js_thumbs_slide');
                                          if (slideEl) {
                                              const slideElMr = parseInt(window.getComputedStyle(slideEl).marginRight);
                                              const slideElMb = parseInt(window.getComputedStyle(slideEl).marginBottom);
                              
                                              sliderGap = slideElMr > slideElMb ? slideElMr : slideElMb;
                                              sliderSize = slider.offsetHeight > slider.offsetWidth ? slider.offsetHeight : slider.offsetWidth;
                                              slideElSize = slideEl.offsetHeight > slideEl.offsetWidth ? slideEl.offsetHeight : slideEl.offsetWidth;
                                          };
                              
                                          return { sliderGap, sliderSize, slideElSize }
                                      },
                                      calcPageSize() {
                                          const slider = this.getSlider();
                                          if (!slider) return;
                              
                                          const { sliderGap, sliderSize, slideElSize } = this.getSliderSize();
                                          this.itemCount = slider.querySelectorAll('.js_thumbs_slide').length;
                                          this.pageSize = Math.round(sliderSize / (slideElSize + sliderGap));
                                          this.pageFillers = (this.pageSize * Math.ceil(this.itemCount / this.pageSize)) - this.itemCount;
                                      },
                                      calcIsSlider() {
                                          const slider = this.getSlider();
                                          if (!slider) return;
                              
                                          const { sliderGap, sliderSize, slideElSize } = this.getSliderSize();
                                          const itemCountTotalSize = (this.itemCount * (slideElSize + sliderGap)) - sliderGap;
                                          const totalSizeDiff = sliderSize - itemCountTotalSize;
                              
                                          // If value is a float, add -1 for the offset
                                          const pixelOffset = Number.isInteger(window.devicePixelRatio) ? 0 : -1;
                                          this.isSlider = totalSizeDiff < pixelOffset;
                                      },
                                      calcScrollStartEnd() {
                                          const slider = this.getSlider();
                                          if (slider) {
                                              const sliderWidth = slider.scrollWidth;
                                              const sliderHeight = slider.scrollHeight;
                              
                                              this.isSliderStart = sliderHeight > sliderWidth
                                                  ? slider.scrollTop === 0
                                                  : slider.scrollLeft === 0;
                              
                                              this.isSliderEnd = sliderHeight > sliderWidth
                                                  ? Math.ceil(slider.scrollTop + slider.offsetHeight) >= sliderHeight
                                                  : Math.ceil(slider.scrollLeft + slider.offsetWidth) >= sliderWidth;
                                          }
                                      },
                                      calcActive() {
                                          const slider = this.getSlider();
                                          if (slider) {
                                              const sliderWidth = slider.scrollWidth;
                                              const sliderHeight = slider.scrollHeight;
                                              const sliderItems = this.itemCount + this.pageFillers;
                                              const calculatedActiveSlide = sliderHeight > sliderWidth
                                                  ? slider.scrollTop / (slider.scrollHeight / sliderItems)
                                                  : slider.scrollLeft / (slider.scrollWidth / sliderItems);
                                              this.activeSlide = Math.round(calculatedActiveSlide / this.pageSize) * this.pageSize;
                                          }
                                      },
                                      calcResize() {
                                          this.calcActive();
                                          this.calcPageSize();
                                          this.calcIsSlider();
                                          this.calcScrollStartEnd();
                                      },
                                      scrollPrevious() {
                                          if (this.isSliderStart) return;
                                          this.scrollTo(this.activeSlide - this.pageSize);
                                      },
                                      scrollNext() {
                                          if (this.isSliderEnd) return;
                                          this.scrollTo(this.activeSlide + this.pageSize);
                                      },
                                      scrollTo(idx) {
                                          const slider = this.getSlider();
                                          if (slider) {
                                              const slideWidth = slider.scrollWidth / (this.itemCount + this.pageFillers);
                                              const slideHeight = slider.scrollHeight / (this.itemCount + this.pageFillers);
                              
                                              if (slideHeight > slideWidth) {
                                                  slider.scrollTop = Math.floor(slideHeight) * idx;
                                              } else {
                                                  slider.scrollLeft = Math.floor(slideWidth) * idx;
                                              }
                              
                                              this.activeSlide = idx;
                                          }
                                      },
                                      setActiveAndScrollTo(index) {
                                          this.setActive(index)
                                          if (this.isSlider) {
                                              this.scrollTo(index);
                                          }
                                      },
                                      eventListeners: {
                                          ['@keydown.window.escape']() {
                                              if (!this.fullscreen) return;
                                              this.closeFullScreen()
                                          },
                                          ['@update-gallery.window'](event) {
                                              this.receiveImages(event.detail);
                                          },
                                          ['@reset-gallery.window'](event) {
                                              this.resetGallery();
                                          },
                                          ['@keyup.arrow-right.window']() {
                                              if (!this.fullscreen) return;
                                              this.nextItem();
                                          },
                                          ['@keyup.arrow-left.window']() {
                                              if (!this.fullscreen) return;
                                              this.previousItem();
                                          },
                                      },
                                      scrollLock(use = true) {
                                          document.body.style.overflow = use ? "hidden" : "";
                                      },
                                      openFullscreen() {
                                          this.fullscreen = true;
                              
                                          hyva.trapFocus(this.$root);
                                      },
                                      closeFullScreen(setFocusTo = this.$refs.galleryFullscreenBtn) {
                                          this.fullscreen = false;
                                          hyva.releaseFocus(this.$root);
                                          this.$nextTick(() => {
                                              this.calcPageSize();
                                              setFocusTo && setFocusTo.focus()
                                          });
                                      },
                                      handleTouchStart(event) {
                                          if (this.images.length <= 1) {
                                              return;
                                          }
                              
                                          const firstTouch = event.touches[0];
                              
                                          touchXDown = firstTouch.clientX;
                                          touchYDown = firstTouch.clientY;
                                      },
                                      handleTouchMove(event) {
                                          if (this.images.length <= 1 || !touchXDown || !touchYDown) {
                                              return;
                                          }
                              
                                          const xDiff = touchXDown - event.touches[0].clientX;
                                          const yDiff = touchYDown - event.touches[0].clientY;
                              
                                          if (Math.abs(xDiff) > Math.abs(yDiff)) {
                                              const newIndex = xDiff > 0 ?  this.getNextIndex() : this.getPreviousIndex();
                                              this.setActiveAndScrollTo(newIndex)
                                          }
                                          touchXDown = touchYDown = null;
                                      },
                                      getPreviousIndex() {
                                          return this.active > 0 ? this.active - 1 : this.itemCount - 1;
                                      },
                                      getNextIndex() {
                                          return this.active + 1 === this.itemCount ? 0 : this.active + 1;
                                      },
                                      previousItem() {
                                          if (this.active === 0) return;
                                          this.setActiveAndScrollTo(this.active - 1);
                                      },
                                      nextItem() {
                                          if ((this.active + 1) === this.itemCount) return;
                                          this.setActiveAndScrollTo(this.active + 1);
                                      },
                                  }
                               }
                           </script>
                           <div class="w-full">
                              <div class="border-b border-gray-30">
                                 <div class=" flex xs:flex-row flex-col-reverse xs:justify-between gap-4 xs:items-center py-4">
                                    <div class="brand-logo flex items-center justify-between xs:justify-start gap-2">
                                       
                                       <div class="sku">
                                          <span class="uppercase sm:text-[15px] text-black">SKU: {{ $product->sku }}</span>
                                       </div>
                                    </div>
                                    <div class="question-box cursor-pointer">
                                       <div data-content-type="html" data-appearance="default" data-element="main" data-decoded="true">
                                          <klevu-init>
                                             <klevu-product-query pqa-widget-id="pqa-7cedf3a2-1760-4100-9e44-5f6ed8690d84">
                                             </klevu-product-query>
                                          </klevu-init>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="see-more">
                                    <div class="flex xs:flex-row flex-col flex-wrap xs:gap-y-3 gap-y-1 xxs:mb-5 mb-4 sm:-mx-3 xs:-mx-2">
                                        @if($product->brand)
                                            <a class="xs:border-r border-black last:border-0 leading-5 sm:px-3 xs:px-2 hover:text-bronze-10" href="{{ route('brands', ['filter' => $product->brand->brand_slug]) }}">{{ $product->brand->brand_title }}</a>
                                        @else
                                            <a class="xs:border-r border-black last:border-0 leading-5 sm:px-3 xs:px-2 hover:text-bronze-10" href="{{ route('brands') }}">Brands</a>
                                        @endif
                                        @if($product->category)
                                            <a class="xs:border-r border-black last:border-0 leading-5 sm:px-3 xs:px-2 hover:text-bronze-10" href="{{ route('categories', ['slug' => $product->category->cat_slug]) }}">{{ $product->category->cat_title }}</a>
                                        @endif
                                        @if($product->subCategory)
                                            <a class="xs:border-r border-black last:border-0 leading-5 sm:px-3 xs:px-2 hover:text-bronze-10" href="{{ route('categories', ['slug' => $product->subCategory->subcat_slug]) }}">{{ $product->subCategory->subcat_title }}</a>
                                        @endif
                                    </div>
                                 </div>
                              </div>
                              <div class="flex flex-row-reverse mt-6 mb-4 justify-between gap-1">
                                 <div class="booster-logo">
                                    <!-- <img class="sm:w-28 xxs:w-24 w-[50px] ml-auto" width="100" height="100" src="https://www.keslighting.co.uk/static/version1722338656/frontend/KesLighting/upgrade/en_GB/images/38YEAR-STAMP-KES-DarkBlue-250x250.svg" alt="Confidence Boost Logo"> -->
                                 </div>
                                 <div class="items-center justify-between gap-x-1 gap-y-3">
                                    @if($product->quantity - $product->total_purchased)
                                        <p class="align-middle available flex font-bold gap-x-[5px] items-center lg:gap-x-2 stock text-kes-green uppercase text-lg xl:text-xl" title="In&#x20;Stock">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 19.1 17.5" style="enable-background:new 0 0 19.1 17.5;" xml:space="preserve" class="h-auto w-auto" width="22" height="22"
                                                aria-hidden="true">
                                                <g id="Group_913" transform="translate(-22.248 -32.375)">
                                                    <path id="Path_1170" fill="#5EC169" d="M39.4,39.7c-0.2-0.1-0.4-0.2-0.6-0.2c-0.5,0.1-0.8,0.5-0.7,0.9c0,0.2,0,0.5,0,0.7   c0,3.9-3.2,7.1-7.1,7.1c-3.9,0-7.1-3.2-7.1-7.1S27.1,34,31,34c1.4,0,2.7,0.4,3.8,1.1c0.4,0.2,0.9,0.1,1.1-0.3   c0.2-0.4,0.1-0.9-0.2-1.1c0,0,0,0,0,0c-4.1-2.6-9.5-1.4-12.1,2.6s-1.4,9.5,2.6,12.1s9.5,1.4,12.1-2.6c0.9-1.4,1.4-3,1.4-4.7   c0-0.3,0-0.6,0-0.9C39.7,40,39.6,39.8,39.4,39.7z"/>
                                                    <path id="Path_1171" fill="#5EC169" d="M41.1,33.4c-0.3-0.3-0.9-0.3-1.2,0L32,41.3l-2.9-2.9c-0.3-0.3-0.9-0.3-1.2,0   c-0.3,0.3-0.3,0.8,0,1.1l3.5,3.5c0.2,0.2,0.4,0.2,0.6,0.2c0.2,0,0.4-0.1,0.6-0.2l8.5-8.5C41.4,34.2,41.4,33.7,41.1,33.4L41.1,33.4z   " />
                                                </g>
                                            </svg>
                                            <span>In Stock</span>
                                        </p>
                                    @else
                                        <p class="align-middle available flex font-bold gap-x-[5px] items-center lg:gap-x-2 stock text-gray-400 uppercase text-lg xl:text-xl" title="Out of Stock">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-auto w-auto" width="22" height="22" aria-hidden="true">
                                                <circle cx="12" cy="12" r="10" stroke="#A0A0A0"></circle>
                                                <line x1="15" y1="9" x2="9" y2="15" stroke="#A0A0A0"></line>
                                                <line x1="9" y1="9" x2="15" y2="15" stroke="#A0A0A0"></line>
                                            </svg>
                                            <span>Out of Stock</span>
                                        </p>
                                    @endif

                                    <div class="price-container">
                                        <!-- <div class="-mx-1.5 flex flex-col font-semibold mb-1 sm:-mx-2.5 sm:leading-6 sm:text-xl text-base text-gray-20 xs:flex-row xs:items-center xss:-mx-2">
                                            <div class="old-price flex !mr-0 xs:border-r border-gray-20 last:border-0 px-1.5 xxs:px-2 sm:px-2.5">
                                                <span id="product-price-23196" class="price-wrapper">
                                                RRP:<span class="price bg-red" x-html="hyva.formatPrice(295.080001 + getCustomOptionPrice())">£295.08</span>
                                                </span>
                                            </div>
                                        </div> -->
                                        <div class="final-price inline-block">
                                            <span class="price-label block mb-1">
                                            </span>
                                            <span class="flex gap-3 items-center">
                                            <span class="bg-kes-red text-white sm:text-sm text-xs inline-block px-2 py-1 font-semibold">Price</span>
                                            <span id="product-price-23196" class="price-wrapper font-semibold text-2xl md:text-3xl xl:text-4xl text-bronze-10 block">
                                            <span class="price">₵{{ $product->price }}</span>
                                            </span>
                                            </span>
                                            <meta content="137.004001">
                                            <meta content="GBP">
                                        </div>
                                        <!-- <div class="mt-1.5 border border-gray-20 rounded-2xl px-2.5 py-0.5 font-semibold sm:leading-6 sm:text-xl text-base text-gray-20">
                                            You Save:                    £158.08                    (-54%)                
                                        </div> -->
                                    </div>

                                    <div class="xs:text-lg text-base">
                                       <p class="flex font-semibold md:gap-2.5 gap-2 mt-3 items-start text-kes-green">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="35" height="30" viewBox="0 0 47.245 32.452" class="fill-kes-green -mt-0.5" aria-hidden="true">
                                             <g id="Group_477_2" data-name="Group 477" transform="translate(0)">
                                                <path id="Path_137_2" data-name="Path 137" d="M303.1,210.6l-3.184-4.1.094-.048-4.344-8.641a1.044,1.044,0,0,0-.929-.576h-6.96v-1.994a1.048,1.048,0,0,0-1.043-1.043H261.419a1.044,1.044,0,0,0,0,2.088h24.274v11.105a1.046,1.046,0,0,0,1.043,1.043h12.046l2.45,3.163v8.472h-3.707l-.089-.36a5.6,5.6,0,0,0-10.866,0l-.091.36h-9.712l-.089-.36a5.6,5.6,0,0,0-10.866,0l-.091.36h-4.3a1.043,1.043,0,1,0,0,2.086h4.319l.094.355a5.6,5.6,0,0,0,10.819,0l.094-.353H286.5l.094.355a5.6,5.6,0,0,0,10.819,0l.094-.353h4.776a1.044,1.044,0,0,0,1.042-1.043v-9.872A1.041,1.041,0,0,0,303.1,210.6Zm-5.478-4.25H287.78v-7.029h6.313Zm-2.11,14.714A3.514,3.514,0,1,1,292,217.552,3.525,3.525,0,0,1,295.515,221.066Zm-20.76,0a3.512,3.512,0,1,1-3.512-3.515A3.522,3.522,0,0,1,274.755,221.066Z"
                                                   transform="translate(-256.076 -194.2)" />
                                                <path id="Path_138_2" data-name="Path 138" d="M272.487,203.574h0a1.047,1.047,0,0,0-1.043-1.043H258.483a1.044,1.044,0,0,0,0,2.088h12.961A1.048,1.048,0,0,0,272.487,203.574Z" transform="translate(-257.441 -190.327)" />
                                                <path id="Path_139_2" data-name="Path 139" d="M268.206,207.65a1.044,1.044,0,0,0-1.043-1.043h-6.987a1.043,1.043,0,0,0,0,2.086h6.987A1.046,1.046,0,0,0,268.206,207.65Z" transform="translate(-256.655 -188.43)" />
                                                <path id="Path_140_2" data-name="Path 140" d="M260.175,200.537h12.963a1.043,1.043,0,1,0,0-2.086h-12.96a1.043,1.043,0,0,0,0,2.086Z" transform="translate(-256.654 -192.223)" />
                                             </g>
                                          </svg>
                                          <span class="leading-6 w-[calc(100%_-_26px)]">Delivery Charges Applicable</span>
                                       </p>
                                    </div>
                                 </div>
                              </div>
                              <form id="product_addtocart_form" method="POST" action="{{ route('cart.add') }}">
                                 @csrf
                              <div class="flex items-end my-6">
                                 <input type="hidden" name="product_id" value="{{ $product->id }}">
                                 <div x-data="{ qty: 1 }" x-init="$dispatch('update-qty-22971', qty)">
                                    <div class="border border-gray-30 flex xs:w-32 w-[90px] h-12 md:h-14">
                                       <label for="qty[22971]" class="sr-only">
                                       Quantity </label>
                                       <span class="bg-gray-40 cursor-pointer flex flex-1 items-center justify-center px-1 select-none" @click="if (qty > 1) { qty = qty - 1 }">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="9" height="2" viewBox="0 0 8.453 1.406" aria-hidden="true">
                                             <path id="Path_22_4" data-name="Path 22" d="M91.744,56.093H100.2V57.5H91.744Z" transform="translate(-91.744 -56.093)" />
                                          </svg>
                                       </span>
                                       <input name="qty" @private-content-loaded.window="onGetCartData($event.detail, $dispatch)" id="qty[22971]" form="product_addtocart_form" type="number" pattern="[0-9]{0,5}" inputmode="numeric" min="1" max="10000" :value="qty" value="1" class="h-full xl:h-auto z-[1] qty [-moz-appearance:textfield] [&::-webkit-inner-spin-button]:hidden appearance-none text-center 
                                          invalid:ring-2 invalid:ring-red-500 border-1 border-t-0 border-b-0 border-gray-30 text-blue-10 px-1 text-base flex-1 xs:w-14 w-9" x-model.number="qty" @input="$dispatch('update-qty-22971', qty)" />
                                       <span class="bg-gray-40 cursor-pointer flex flex-1 items-center justify-center px-1 select-none" @click="qty = qty + 1">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="9" height="9" viewBox="0 0 8.5 8.5" aria-hidden="true">
                                             <path id="Path_21_2" data-name="Path 21" d="M57.316,67.25V63.714H53.78V62.286h3.536V58.75h1.428v3.536H62.28v1.428H58.744V67.25Z" transform="translate(-53.78 -58.75)" />
                                          </svg>
                                       </span>
                                    </div>
                                 </div>
                                <button type="button" form="product_addtocart_form" title="Add to Basket" class="btn btn-primary lg:text-lg h-12 md:h-14 mx-2 md:mx-4 justify-center w-full px-1" id="product-addtocart-button">
                                    <span class="relative z-10">Add to Basket</span>
                                </button>
                              </form>

                                <dialog id="product-dialog" style="z-index: 100" class="w-[736px] rounded-lg bg-white p-0 shadow-xl backdrop:bg-black/75 backdrop:backdrop-blur-sm">
                                    <div class="px-3 md:px-5">
                                        <div class="flex gap-6 justify-between items-center px-2 py-4 border-b border-gray-30">
                                            <span class="md:text-xl text-lg font-bold">
                                                The product was added to your basket. 
                                            </span>
                                            <button title="Close fullscreen" aria-label="Close fullscreen" type="button" class="hover:text-slate-500 close-dialog-button">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="23" height="23" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="px-3 md:px-5">
                                            <div class="flex gap-3 sm:gap-x-6 items-start px-2 py-4 border-b border-gray-30">
                                                <div class="shrink-0 max-w-[theme(spacing.20)] sm:max-w-[theme(spacing.32)]">
                                                    <img loading="lazy" src="" width="78" height="78" alt="Product &quot;Caspen Skyhill 13 Light Crystal Pendant Light in Polished Chrome&quot;">
                                                </div>
                                                <div class="grow flex gap-3 flex-col sm:flex-row">
                                                    <div class="grow">
                                                        <p class="font-semibold leading-6 text-blue-10 md:text-lg"></p>
                                                        <dl class="table mt-2 text-sm">
                                                            <div class="table-row text-gray-20">
                                                                <dt class="table-cell pb-1.5 pr-2 uppercase">
                                                                     
                                                                </dt>
                                                                <dd class="table-cell pb-1.5"></dd>
                                                            </div>
                                                        </dl>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex flex-col sm:flex-row lg:justify-end md:gap-4 gap-3 md:p-6 p-4 bg-gray-70">
                                            <a href="{{ route('cart-items') }}" id="view-basket-button" class="btn btn-secondary disabled:opacity-70 justify-center before:absolute before:bg-white before:duration-1000 before:ease before:h-14 before:opacity-10 before:right-0 before:rotate-6 before:top-0 before:translate-x-12 before:w-6 hover:before:-translate-x-[35rem] hover:bg-kes-green hover:border-kes-green hover:text-white overflow-hidden relative transition-all">View Your Basket</a>
                                            <a href="javascript:void(0)" class="btn btn-primary close-dialog-button justify-center before:absolute before:bg-white before:duration-1000 before:ease before:h-14 before:opacity-10 before:right-0 before:rotate-6 before:top-0 before:translate-x-12 before:w-6 hover:before:-translate-x-[35rem] hover:bg-kes-green hover:border-kes-green hover:text-white overflow-hidden relative transition-all">Close</a>
                                        </div>
                                    </div>
                                </dialog>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        // Get elements
                                        const dialog = document.getElementById('product-dialog');
                                        const closeButtons = document.getElementsByClassName('close-dialog-button');

                                        // Function to close the dialog
                                        function closeDialog() {
                                            dialog.close();
                                        }
                                        Array.from(closeButtons).forEach(button => {
                                            button.addEventListener('click', closeDialog);
                                        });

                                        // Close dialog if clicking outside of it
                                        dialog.addEventListener('click', function(event) {
                                            if (event.target === dialog) {
                                                closeDialog();
                                            }
                                        });

                                        // Optional: Add event listeners to other buttons
                                        document.getElementById('continue-shopping-button').addEventListener('click', closeDialog);
                                        document.getElementById('view-basket-button').addEventListener('click', function(event) {
                                            event.preventDefault();
                                            closeDialog();
                                            window.location.href = event.target.href;
                                        });
                                        document.getElementById('checkout-button').addEventListener('click', function(event) {
                                            event.preventDefault();
                                            closeDialog();
                                            window.location.href = event.target.href;
                                        });
                                    });
                                </script>

                                 <div class="flex">
                                    <button title="Wish&#x20;List" aria-label="Wish&#x20;List" class="group/wishlist md:w-14 w-12 h-12 md:h-14 inline-flex items-center justify-center border border-bronze-10 hover:bg-bronze-10">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="26" height="24" viewBox="0 0 12.594 11.312" class="w-7 h-6 group-hover/wishlist:fill-white fill-bronze-10" role="img">
                                          <g id="Icon_akar-heart_2" data-name="Icon akar-heart" transform="translate(-606.976 -278.715)">
                                             <path id="Path_29_4" data-name="Path 29" d="M613.273,290.027a.954.954,0,0,1-.494-.138c-5.05-3.105-5.8-6.264-5.8-7.871a3.323,3.323,0,0,1,3.318-3.3h.016a4.387,4.387,0,0,1,2.962,1.577,4.394,4.394,0,0,1,2.965-1.577h.017a3.322,3.322,0,0,1,3.316,3.3c0,1.609-.753,4.768-5.8,7.871A.956.956,0,0,1,613.273,290.027Zm-2.979-10.578a2.588,2.588,0,0,0-2.584,2.571c0,1.452.708,4.326,5.451,7.242a.219.219,0,0,0,.225,0c4.742-2.915,5.45-5.789,5.45-7.244a2.587,2.587,0,0,0-2.584-2.569h-.013c-1.439,0-2.662,1.616-2.674,1.632a.368.368,0,0,1-.294.148h0a.368.368,0,0,1-.294-.148c-.012-.016-1.238-1.632-2.668-1.632Z"
                                                />
                                          </g>
                                          <title>heart</title>
                                       </svg>
                                    </button>
                                 </div>
                              </div>
                              <div class="w-full flex justify-center items-center mb-7 payment-icons">
                                 <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Layer_1" data-name="Layer 1" viewBox="0 0 38 24" class="sm:h-8 sm:w-12 h-6 w-10 mr-2" width="24" height="24" role="img">
                                    <defs>
                                       <style>
                                          .vis-1{fill:none;}.vis-2{clip-path:url(#clip-path);}.vis-3{isolation:isolate;opacity:0.07;}.vis-4{fill:#fff;}.vis-5{fill:#142688;}
                                       </style>
                                       <clipPath id="clip-path">
                                          <rect class="vis-1" x="-328.18" width="374" height="24" />
                                       </clipPath>
                                    </defs>
                                    <g class="vis-2">
                                       <path class="vis-3" d="M35,0H3A3.06,3.06,0,0,0,1.84.21,3,3,0,0,0,.21,1.84,3.06,3.06,0,0,0,0,3V21a3,3,0,0,0,3,3H35a2.93,2.93,0,0,0,3-3V3a3,3,0,0,0-3-3Z" />
                                       <path class="vis-4" d="M35,1a2,2,0,0,1,2,2V21a2,2,0,0,1-2,2H3a2,2,0,0,1-2-2V3A2,2,0,0,1,3,1Z" />
                                       <path class="vis-5" d="M28.3,10.12H28a13.06,13.06,0,0,0-1,3h1.9A21.82,21.82,0,0,0,28.3,10.12ZM31.2,16H29.5c-.1,0-.1,0-.2-.1l-.2-.9-.1-.2H26.6c-.1,0-.2,0-.2.2l-.3.9a.11.11,0,0,1,0,.07A.09.09,0,0,1,26,16H23.9l.2-.5L27,8.72c0-.5.3-.7.8-.7h1.5c.1,0,.2,0,.2.2l1.4,6.5a4.45,4.45,0,0,1,.2,1.1C31.2,15.92,31.2,15.92,31.2,16Zm-13.4-.3.4-1.8a.3.3,0,0,1,.2.1,4,4,0,0,0,2.1.4,1.94,1.94,0,0,0,.7-.2c.5-.2.5-.7.1-1.1a5.1,5.1,0,0,0-.8-.5,4.37,4.37,0,0,1-1.1-.7,2.12,2.12,0,0,1-.55-.68,2.09,2.09,0,0,1-.21-.85,2,2,0,0,1,.66-1.57c.6-.4.9-.8,1.7-.8a12.87,12.87,0,0,1,3.1.2h.1a11.33,11.33,0,0,1-.4,1.7,4.13,4.13,0,0,0-1.5-.4,2.74,2.74,0,0,0-.9.1.54.54,0,0,0-.22.05.65.65,0,0,0-.18.15.36.36,0,0,0-.11.16.41.41,0,0,0,0,.19.43.43,0,0,0,0,.19.42.42,0,0,0,.11.16l.5.4a11.72,11.72,0,0,1,1.1.6,2.18,2.18,0,0,1,.7.58,2.24,2.24,0,0,1,.4.82,2.17,2.17,0,0,1-.9,2.3,1.87,1.87,0,0,1-.63.45,1.74,1.74,0,0,1-.77.15,11.76,11.76,0,0,1-3.4-.2C17.9,15.82,17.9,15.82,17.8,15.72Zm-3.5.3a3.78,3.78,0,0,1,.2-1c.5-2.2,1-4.5,1.4-6.7.1-.2.1-.3.3-.3H18a32,32,0,0,1-.7,3.2c-.3,1.5-.6,3-1,4.5,0,.2-.1.2-.3.2ZM5,8.22c0-.1.2-.2.3-.2H8.7a1,1,0,0,1,.65.21,1,1,0,0,1,.35.59l.9,4.4c0,.1,0,.1.1.2a.1.1,0,0,1,.1-.1l2.1-5.1c-.1-.1,0-.2.1-.2h2.1c0,.1,0,.1-.1.2l-3.1,7.3c-.1.2-.1.3-.2.4s-.3,0-.5,0H9.7c-.1,0-.2,0-.2-.2L7.9,9.52a2,2,0,0,0-.9-.6,6.67,6.67,0,0,0-1.9-.5Z"
                                          />
                                    </g>
                                    <title>visa</title>
                                 </svg>
                                 <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" id="Layer_1" data-name="Layer 1" viewBox="0 0 38 24" class="sm:h-8 sm:w-12 h-6 w-10 mr-2" width="24" height="24" role="img">
                                    <defs>
                                       <style>
                                          .msc-1{fill:none;}.msc-2{clip-path:url(#clip-path_2);}.msc-3{isolation:isolate;opacity:0.07;}.msc-4{fill:#fff;}.msc-5{fill:#eb001b;}.msc-6{fill:#f79e1b;}.msc-7{fill:#ff5f00;}
                                       </style>
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
                                       <style>
                                          .amex-1{fill:none;}.amex-2{clip-path:url(#clip-path_3);}.amex-3{isolation:isolate;opacity:0.07;}.amex-4{fill:#006fcf;}.amex-5{fill:#fff;}
                                       </style>
                                       <clipPath id="clip-path_3">
                                          <rect class="amex-1" x="-96.18" width="374" height="24" />
                                       </clipPath>
                                    </defs>
                                    <g class="amex-2">
                                       <path class="amex-3" d="M35,0H3A3.06,3.06,0,0,0,1.84.21,3,3,0,0,0,.21,1.84,3.06,3.06,0,0,0,0,3V21a3,3,0,0,0,3,3H35a2.93,2.93,0,0,0,3-3V3a3,3,0,0,0-3-3Z" />
                                       <path class="amex-4" d="M35,1a2,2,0,0,1,2,2V21a2,2,0,0,1-2,2H3a2,2,0,0,1-2-2V3A2,2,0,0,1,3,1Z" />
                                       <path class="amex-5" d="M9,10.27l.77,1.87H8.2ZM25,10.35h-3v.82H25v1.24H22.07v.92h3v.74l2.08-2.24L25.05,9.49ZM11,8h4l.88,1.93L16.69,8H27.06l1.07,1.19L29.25,8H34l-3.52,3.85L34,15.68H29.14l-1.08-1.19-1.12,1.19H10l-.5-1.19H8.4l-.5,1.19H4L7.28,8H11Zm8.66,1.07H17.41l-1.5,3.54L14.28,9.08H12.06v4.81L10,9.08H8L5.62,14.6H7.18l.49-1.19h2.6l.5,1.19h2.72V10.66l1.74,3.94h1.19l1.74-3.93V14.6h1.46l0-5.52ZM29,11.85l2.54-2.77H29.69l-1.6,1.73L26.54,9.08H20.65V14.6h5.81l1.61-1.74,1.55,1.74H31.5L29,11.85Z"
                                          />
                                       <path class="amex-1" d="M34.82,1a2,2,0,0,1,1.41.59A2.05,2.05,0,0,1,36.82,3V21a2.05,2.05,0,0,1-.59,1.41,2,2,0,0,1-1.41.59h-32a2,2,0,0,1-2-2V3a2,2,0,0,1,2-2Z" />
                                    </g>
                                    <title>amex</title>
                                 </svg>
                                 <svg xmlns="http://www.w3.org/2000/svg" id="Group_350" data-name="Group 350" width="24" height="24" viewBox="0 0 39.959 25.582" class="sm:h-8 sm:w-12 h-6 w-10 mr-2" role="img">
                                    <g id="Group_46" data-name="Group 46" transform="translate(0)">
                                       <path id="Path_76" data-name="Path 76" d="M86.637,8H53.424c-.115,0-.23,0-.345.005a4.946,4.946,0,0,0-.751.066,2.522,2.522,0,0,0-.714.236,2.392,2.392,0,0,0-1.05,1.05,2.522,2.522,0,0,0-.236.714,4.944,4.944,0,0,0-.066.751c0,.115,0,.23-.005.345V30.413c0,.115,0,.23.005.345a4.944,4.944,0,0,0,.066.751,2.522,2.522,0,0,0,.236.714,2.388,2.388,0,0,0,1.05,1.05,2.531,2.531,0,0,0,.715.237,5.055,5.055,0,0,0,.75.065c.115,0,.23,0,.345.005H87.047c.114,0,.229,0,.345-.005a5.082,5.082,0,0,0,.751-.065,2.524,2.524,0,0,0,.714-.237,2.4,2.4,0,0,0,1.05-1.05,2.522,2.522,0,0,0,.236-.714,5.083,5.083,0,0,0,.066-.751c0-.115,0-.23.005-.345V11.169c0-.115,0-.23-.005-.345a5.084,5.084,0,0,0-.066-.751,2.522,2.522,0,0,0-.236-.714,2.4,2.4,0,0,0-1.05-1.05,2.522,2.522,0,0,0-.714-.236,4.946,4.946,0,0,0-.751-.066C87.278,8,87.162,8,87.047,8h-.41"
                                          transform="translate(-50.256 -8)" />
                                    </g>
                                    <g id="Group_47" data-name="Group 47" transform="translate(0.853 0.853)">
                                       <path id="Path_77" data-name="Path 77" d="M86.584,8.8h.4c.109,0,.219,0,.328,0a4.3,4.3,0,0,1,.624.053,1.683,1.683,0,0,1,.479.157,1.536,1.536,0,0,1,.677.678,1.608,1.608,0,0,1,.156.479,4.232,4.232,0,0,1,.053.622c0,.109,0,.217,0,.328,0,.134,0,.269,0,.4V30.357c0,.109,0,.217-.005.325a4.312,4.312,0,0,1-.053.626,1.665,1.665,0,0,1-.156.476,1.544,1.544,0,0,1-.678.678,1.672,1.672,0,0,1-.475.156,4.368,4.368,0,0,1-.621.053c-.111,0-.221,0-.334,0H53.375c-.109,0-.217,0-.326,0a4.174,4.174,0,0,1-.624-.053,1.655,1.655,0,0,1-.48-.157,1.516,1.516,0,0,1-.391-.285,1.54,1.54,0,0,1-.285-.392,1.647,1.647,0,0,1-.156-.479,4.014,4.014,0,0,1-.053-.623c0-.11,0-.219-.005-.327V11.122c0-.109,0-.219.005-.327a4.178,4.178,0,0,1,.053-.625,1.679,1.679,0,0,1,.156-.479,1.557,1.557,0,0,1,.677-.677,1.69,1.69,0,0,1,.479-.156,4.31,4.31,0,0,1,.624-.053c.11,0,.219,0,.327,0H86.584"
                                          transform="translate(-51.056 -8.8)" fill="#fff" />
                                    </g>
                                    <g id="Group_48" data-name="Group 48" transform="translate(9.028 7.062)">
                                       <path id="Path_78" data-name="Path 78" d="M60.2,16.2a2.208,2.208,0,0,0,.507-1.574,2.188,2.188,0,0,0-1.451.751,2.065,2.065,0,0,0-.52,1.513A1.828,1.828,0,0,0,60.2,16.2" transform="translate(-58.726 -14.625)" />
                                    </g>
                                    <g id="Group_49" data-name="Group 49" transform="translate(5.066 9.431)">
                                       <path id="Path_79" data-name="Path 79" d="M60.948,16.851c-.809-.048-1.5.458-1.882.458s-.977-.434-1.617-.422a2.382,2.382,0,0,0-2.026,1.231c-.869,1.5-.229,3.718.615,4.937.41.6.9,1.267,1.556,1.243.616-.023.857-.4,1.6-.4s.966.4,1.617.386c.676-.012,1.1-.6,1.508-1.208A5.31,5.31,0,0,0,63,21.692,2.192,2.192,0,0,1,61.684,19.7a2.235,2.235,0,0,1,1.062-1.871,2.31,2.31,0,0,0-1.8-.977"
                                          transform="translate(-55.008 -16.848)" />
                                    </g>
                                    <g id="Group_50" data-name="Group 50" transform="translate(15.67 7.752)">
                                       <path id="Path_80" data-name="Path 80" d="M68.352,15.273a2.841,2.841,0,0,1,2.981,2.974,2.874,2.874,0,0,1-3.025,2.988H66.363v3.093H64.957V15.273h3.395m-1.989,4.782h1.613a1.7,1.7,0,0,0,1.92-1.8,1.689,1.689,0,0,0-1.913-1.8H66.363v3.6h0" transform="translate(-64.957 -15.273)"
                                          />
                                    </g>
                                    <g id="Group_51" data-name="Group 51" transform="translate(22.395 10.156)">
                                       <path id="Path_81" data-name="Path 81" d="M71.266,22.3c0-1.161.885-1.826,2.516-1.927l1.751-.107v-.5c0-.733-.483-1.135-1.343-1.135a1.234,1.234,0,0,0-1.33.922H71.592c.037-1.174,1.142-2.026,2.636-2.026,1.606,0,2.654.84,2.654,2.146v4.506h-1.3V23.093h-.031a2.315,2.315,0,0,1-2.071,1.155A1.989,1.989,0,0,1,71.266,22.3m4.267-.584v-.508l-1.563.1c-.878.056-1.337.383-1.337.953,0,.552.478.91,1.224.91a1.533,1.533,0,0,0,1.676-1.456"
                                          transform="translate(-71.266 -17.528)" />
                                    </g>
                                    <g id="Group_52" data-name="Group 52" transform="translate(28.553 10.237)">
                                       <path id="Path_82" data-name="Path 82" d="M77.7,26.6V25.511a4.041,4.041,0,0,0,.415.025,1.118,1.118,0,0,0,1.185-.941l.126-.4L77.043,17.6h1.468l1.658,5.347H80.2L81.856,17.6h1.432l-2.467,6.922c-.565,1.587-1.211,2.108-2.58,2.108A3.781,3.781,0,0,1,77.7,26.6h0"
                                          transform="translate(-77.043 -17.604)" />
                                    </g>
                                    <title>applypay</title>
                                 </svg>
                                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 41.451 25.72" class="sm:h-8 sm:w-12 h-6 w-10" role="img">
                                    <g id="Group_351" data-name="Group 351" transform="translate(-0.624 -0.624)">
                                       <g id="Group_75" data-name="Group 75" transform="translate(1.124 1.124)">
                                          <path id="Path_96" data-name="Path 96" d="M257.2,9a2.254,2.254,0,0,1,2.247,2.247V31.473A2.254,2.254,0,0,1,257.2,33.72H221.247A2.254,2.254,0,0,1,219,31.473V11.247A2.254,2.254,0,0,1,221.247,9H257.2" transform="translate(-219 -9)" fill="#fff" stroke="#ccc"
                                             stroke-width="1" />
                                       </g>
                                       <g id="Group_76" data-name="Group 76" transform="translate(19.186 8.175)">
                                          <path id="Path_97" data-name="Path 97" d="M236.219,20.557v3.6h-1.144V15.276H238.1a2.75,2.75,0,0,1,1.963.778,2.563,2.563,0,0,1,.124,3.623l-.124.13a2.75,2.75,0,0,1-1.963.757l-1.88-.007m0-4.193V19.5h1.908a1.515,1.515,0,0,0,1.129-.454,1.563,1.563,0,0,0-1.129-2.645l-1.908-.034m7.286,1.515a2.852,2.852,0,0,1,2,.689,2.4,2.4,0,0,1,.73,1.853v3.746h-1.089v-.861h-.055a2.167,2.167,0,0,1-1.88,1.046,2.438,2.438,0,0,1-1.681-.6,1.873,1.873,0,0,1-.689-1.488,1.806,1.806,0,0,1,.689-1.5,3.084,3.084,0,0,1,1.908-.579,3.358,3.358,0,0,1,1.674.372v-.234a1.274,1.274,0,0,0-.469-1.012,1.593,1.593,0,0,0-1.1-.413,1.736,1.736,0,0,0-1.482.806l-1.006-.634a2.8,2.8,0,0,1,2.452-1.191h0m-1.446,4.414a.889.889,0,0,0,.379.744,1.376,1.376,0,0,0,.882.3,1.838,1.838,0,0,0,1.288-.53,1.7,1.7,0,0,0,.537-1.239,2.3,2.3,0,0,0-1.488-.42,1.934,1.934,0,0,0-1.157.33.991.991,0,0,0-.44.82h0m10.434-4.214-3.809,8.753h-1.178l1.439-3.065-2.5-5.688h1.239l1.811,4.365,1.763-4.365h1.233"
                                             transform="translate(-235.075 -15.276)" fill="#5f6368" />
                                       </g>
                                       <g id="Group_77" data-name="Group 77" transform="translate(10.813 11.646)">
                                          <path id="Path_98" data-name="Path 98" d="M232.525,19.4a6.5,6.5,0,0,0-.082-1.034h-4.82v1.963h2.754a2.356,2.356,0,0,1-1.011,1.543v1.274h1.645a4.981,4.981,0,0,0,1.515-3.746h0" transform="translate(-227.623 -18.364)" fill="#4285f4" />
                                       </g>
                                       <g id="Group_78" data-name="Group 78" transform="translate(6.267 13.533)">
                                          <path id="Path_99" data-name="Path 99" d="M228.13,24.176a4.892,4.892,0,0,0,3.382-1.233l-1.647-1.281a3.093,3.093,0,0,1-4.6-1.618h-1.688v1.315a5.091,5.091,0,0,0,4.553,2.817h0" transform="translate(-223.577 -20.044)" fill="#34a853" />
                                       </g>
                                       <g id="Group_80" data-name="Group 80" transform="translate(6.267 7.465)">
                                          <path id="Path_101" data-name="Path 101" d="M228.13,16.662a2.757,2.757,0,0,1,1.955.764l1.461-1.453a4.912,4.912,0,0,0-3.444-1.329,5.091,5.091,0,0,0-4.525,2.809l1.688,1.316a3.051,3.051,0,0,1,2.865-2.107h0" transform="translate(-223.577 -14.643)" fill="#ea4335"
                                             />
                                       </g>
                                    </g>
                                    <title>gpay</title>
                                 </svg>
                              </div>
                              <div class="xl:py-9 lg:py-6 py-4 border-b border-t border-gray-30">
                                 <div class="flex top-bar xl:leading-6 leading-[22px] text-sm">
                                    <div class="flex-grow flex gap-2 xl:gap-3.5 items-center section-item md:border-r last:border-0 md:border-gray-30 justify-center">
                                       <svg xmlns="http://www.w3.org/2000/svg" width="47" height="32" viewBox="0 0 47.245 32.452" aria-hidden="true">
                                          <g id="Group_477_3" data-name="Group 477" transform="translate(0)">
                                             <path id="Path_137_3" data-name="Path 137" d="M303.1,210.6l-3.184-4.1.094-.048-4.344-8.641a1.044,1.044,0,0,0-.929-.576h-6.96v-1.994a1.048,1.048,0,0,0-1.043-1.043H261.419a1.044,1.044,0,0,0,0,2.088h24.274v11.105a1.046,1.046,0,0,0,1.043,1.043h12.046l2.45,3.163v8.472h-3.707l-.089-.36a5.6,5.6,0,0,0-10.866,0l-.091.36h-9.712l-.089-.36a5.6,5.6,0,0,0-10.866,0l-.091.36h-4.3a1.043,1.043,0,1,0,0,2.086h4.319l.094.355a5.6,5.6,0,0,0,10.819,0l.094-.353H286.5l.094.355a5.6,5.6,0,0,0,10.819,0l.094-.353h4.776a1.044,1.044,0,0,0,1.042-1.043v-9.872A1.041,1.041,0,0,0,303.1,210.6Zm-5.478-4.25H287.78v-7.029h6.313Zm-2.11,14.714A3.514,3.514,0,1,1,292,217.552,3.525,3.525,0,0,1,295.515,221.066Zm-20.76,0a3.512,3.512,0,1,1-3.512-3.515A3.522,3.522,0,0,1,274.755,221.066Z"
                                                transform="translate(-256.076 -194.2)" fill="#c19b5b" />
                                             <path id="Path_138_3" data-name="Path 138" d="M272.487,203.574h0a1.047,1.047,0,0,0-1.043-1.043H258.483a1.044,1.044,0,0,0,0,2.088h12.961A1.048,1.048,0,0,0,272.487,203.574Z" transform="translate(-257.441 -190.327)" fill="#c19b5b" />
                                             <path id="Path_139_3" data-name="Path 139" d="M268.206,207.65a1.044,1.044,0,0,0-1.043-1.043h-6.987a1.043,1.043,0,0,0,0,2.086h6.987A1.046,1.046,0,0,0,268.206,207.65Z" transform="translate(-256.655 -188.43)" fill="#c19b5b" />
                                             <path id="Path_140_3" data-name="Path 140" d="M260.175,200.537h12.963a1.043,1.043,0,1,0,0-2.086h-12.96a1.043,1.043,0,0,0,0,2.086Z" transform="translate(-256.654 -192.223)" fill="#c19b5b" />
                                          </g>
                                       </svg>
                                       <div>
                                          <strong class="block">FREE DELIVERY WITHIN GHANA</strong>
                                          <!-- <span class="block">on Orders Over £99</span> -->
                                       </div>
                                    </div>
                                    <div class="hidden md:flex-grow md:flex gap-2 xl:gap-3.5 items-center section-item md:border-r last:border-0 md:border-gray-30 justify-center">
                                       <svg xmlns="http://www.w3.org/2000/svg" id="Group_465" data-name="Group 465" width="40" height="38" viewBox="0 0 40.16 37.705" aria-hidden="true">
                                          <path id="Path_38_2" data-name="Path 38" d="M294.339,292.16h-.014a7.87,7.87,0,0,0-4.385,1.329,7.752,7.752,0,0,0-2.735,3.205l-.329.716-.565-1.377a.891.891,0,0,0-1.648.678l1.4,3.418a.892.892,0,0,0,1.157.488l.006,0,3.42-1.4a.891.891,0,0,0-.678-1.648l-1.5.615.381-.8a5.986,5.986,0,0,1,2.078-2.4,6.107,6.107,0,0,1,3.4-1.028h.012a6.1,6.1,0,1,1-.012,12.191h-.058a5.981,5.981,0,0,1-4.25-1.787.891.891,0,0,0-1.267,1.251,7.742,7.742,0,0,0,5.505,2.317h.043a7.894,7.894,0,0,0,5.607-2.318,7.784,7.784,0,0,0,2.317-5.55A7.91,7.91,0,0,0,294.339,292.16Z"
                                             transform="translate(-262.068 -270.211)" fill="#c19b5b" />
                                          <path id="Path_39_2" data-name="Path 39" d="M43.679,69.721a.892.892,0,0,0-.89-.89H26.6a3.225,3.225,0,0,1-3.221-3.221V40.939A3.225,3.225,0,0,1,26.6,37.718h6.079v7.944a1.633,1.633,0,0,0,1.631,1.631h9.252a1.633,1.633,0,0,0,1.631-1.631V37.718h6.079a3.225,3.225,0,0,1,3.221,3.221v13.13a.892.892,0,0,0,.891.89h0a.892.892,0,0,0,.89-.89V40.939a5.008,5.008,0,0,0-5-5H26.6a5.008,5.008,0,0,0-5,5V65.609a5.008,5.008,0,0,0,5,5H42.789A.892.892,0,0,0,43.679,69.721Zm-9.216-32h8.95v7.793h-8.95Z"
                                             transform="translate(-21.6 -35.936)" fill="#c19b5b" />
                                          <path id="Path_40_2" data-name="Path 40" d="M96.175,351.36h-5.1a.891.891,0,0,0,0,1.782h5.1a.891.891,0,0,0,0-1.782Z" transform="translate(-84.313 -324.34)" fill="#c19b5b" />
                                       </svg>
                                       <div>
                                          <strong class="block">30 DAYS</strong>
                                          <span class="block">Returns Policy</span>
                                       </div>
                                    </div>
                                    <div class="hidden md:flex-grow md:flex gap-2 xl:gap-3.5 items-center section-item md:border-r last:border-0 md:border-gray-30 justify-center">
                                       <svg xmlns="http://www.w3.org/2000/svg" id="Group_467" data-name="Group 467" width="34" height="40" viewBox="0 0 34.063 39.961" aria-hidden="true">
                                          <path id="Path_131" data-name="Path 131" d="M80.829,230.419v13.806c0,15.576,16.6,19.857,16.77,19.9l.262.064.263-.064c.167-.041,16.768-4.321,16.768-19.9V230.419L97.86,224.226Zm31.866,13.806c0,12.8-12.272,16.96-14.732,17.664l-.1.028-.1-.028c-2.461-.73-14.741-5.012-14.741-17.664V231.957l14.834-5.392,14.834,5.392Z"
                                             transform="translate(-80.829 -224.226)" fill="#c19b5b" />
                                          <path id="Path_132" data-name="Path 132" d="M87.964,239.793l-1.554,1.554,6.323,6.323,12.117-12.118L103.3,234,92.734,244.562Z" transform="translate(-78.6 -220.323)" fill="#c19b5b" />
                                       </svg>
                                       <div>
                                          <strong class="block">12 MONTH</strong>
                                          <span class="block">Warranty</span>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div>
                                @if($product->description)
                                 <div class="border-b border-gray-30 py-4 xl:py-6 tabcontent w-full" x-data="{ open : false }">
                                    <div class="tabheader relative cursor-pointer uppercase w-full">
                                       <h2 href="#" class="font-sans block font-semibold text-black text-lg xl:text-xl" aria-label="Open Tab Content" @click.prevent="open = !open">
                                          Description 
                                       </h2>
                                       <div class="absolute right-0 top-1.5 transform" x-cloak @click.prevent="open = !open" :class="{ '-rotate-180' : open }">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="13" viewBox="0 0 16.366 9.819" fill="#000" aria-hidden="true">
                                             <path id="Icon_ion-ios-arrow-right_20" data-name="Icon ion-ios-arrow-right" d="M10.625,7.367l1.059-.992,8.761,8.183-8.761,8.183-1.059-.987,7.7-7.2Z" transform="translate(22.741 -10.625) rotate(90)" />
                                          </svg>
                                       </div>
                                    </div>
                                    <div class="category-desc value order-1 w-full pt-5 lg:order-2 lg:w-full lg:border-0" x-cloak x-show="open">
                                        <?= $product->description ?>
                                    </div>
                                 </div>
                                @endif
                                @if($product->specification)
                                 <div class="border-b border-gray-30 py-4 xl:py-6 tabcontent w-full" x-data="{ open : false }">
                                    <div class="tabheader relative cursor-pointer uppercase w-full">
                                       <h3 href="#" class="font-sans block font-semibold text-black text-lg xl:text-xl" aria-label="Open Tab Content" @click.prevent="open = !open">
                                          Specification 
                                       </h3>
                                       <div class="absolute right-0 top-1.5 transform" x-cloak @click.prevent="open = !open" :class="{ '-rotate-180' : open }">
                                          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="13" viewBox="0 0 16.366 9.819" fill="#000" aria-hidden="true">
                                             <path id="Icon_ion-ios-arrow-right_21" data-name="Icon ion-ios-arrow-right" d="M10.625,7.367l1.059-.992,8.761,8.183-8.761,8.183-1.059-.987,7.7-7.2Z" transform="translate(22.741 -10.625) rotate(90)" />
                                          </svg>
                                       </div>
                                    </div>
                                    <div class="category-desc value order-2 w-full pt-5 lg:order-2 lg:w-full lg:border-0" x-cloak x-show="open">
                                        <?= $product->specification ?>
                                    </div>
                                 </div>
                                @endif
                              </div>
                              <div class="flex justify-end">
                              </div>
                           </div>
                        </div>
                     </div>
                  </section>
                  <section>
                     <div class="product-options-bottom container flex flex-col md:flex-row flex-no-wrap gap-4">
                     </div>
                  </section>
                  <section>
                  </section>
               </div>
              @if(count($product->variations) > 0)
               <div class="bg-container xl:mt-20 xl:mb-28 lg:my-20 md:my-14 my-12" x-data x-cloak>
                  <div class="flex flex-col-reverse md:flex-row">
                     <div class="my-auto md:w-1/2 2xl:pl-[calc((100%_-_1500px)_/_2)] xl:pl-[calc((100%_-_1280px)_/_2)] lg:pl-[calc((100%_-_1024px)_/_2)] xl:py-12 md:py-10 sm:py-12 py-10">
                        <div class="xl:px-[30px] lg:px-5 px-[18px]">
                           <h3 class="mb-4 lg:mb-6 sm:text-[28px] text-[26px] md:text-3xl lg:text-[33px] xl:text-4xl lg:leading-[44px] md:leading-[40px] xl:leading-[50px]">
                              Details 
                           </h3>
                           <div class="table-wrapper max-w-prose overflow-x-auto" id="product-attributes">
                              <table class="additional-attributes w-full text-blue-10 xl:text-[15px]">
                                @foreach($product->variations as $variation)
                                 <tr class>
                                    <th style="width: unset;" class="col label w-1/2 py-0.5 product-attribute-label text-left align-top" scope="row">{{ $variation->label }}</th>
                                    <td style="width: unset;" class="col data w-1/2 py-0.5 pl-1 product-attribute-value" data-th="Dimensions">{{ $variation->value }}</td>
                                 </tr>
                                 @endforeach
                              </table>
                           </div>
                        </div>
                     </div>
                     <div class="md:w-1/2">
                        <img  src="{{ asset($product->image) }}" alt="Caspen Sobrado 6 Light Pendant Light Antique Brass Opal Glass" width="960px" height="586px" class="h-full object-cover object-center" />
                     </div>
                  </div>
               </div>
               @else
               <br>
               @endif
               
               @include('public.slider-products', ['products' => $related_products])
              
               @include('public.promise')
            </div>
         </div>
      </main>   
@endsection
@section('scripts')
   <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.0/dist/alpine.min.js" defer></script>
   <script>
       document.getElementById('product-addtocart-button').addEventListener('click', function (e) {
        e.preventDefault();
        
        // Get the form element
        var form = document.getElementById('product_addtocart_form');
        var formData = new FormData(form);

        var quantityInput = form.querySelector('input[name="qty"]');
        var availableQuantity = {{ $product->quantity - $product->total_purchased }};

        if (parseInt(quantityInput.value) > availableQuantity) {
            alert('Error: Quantity exceeds available stock. Available: ' + availableQuantity);
            return;
        }

        // Send the AJAX request to add to cart
        fetch("{{ route('cart.add') }}", {
            method: 'POST',
            body: formData,
            
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const item = data.data;
                document.querySelector('#product-dialog img').src = item.attributes.image;
                document.querySelector('#product-dialog img').alt = item.name;
                document.querySelector('#product-dialog .font-semibold').textContent = item.name;
                document.querySelector('#product-dialog .table-cell:nth-child(2)').textContent = item.attributes.sku;
                
                document.getElementsByClassName('cartItemsCount')[0].innerHTML = data.count;

                const openButton = document.getElementById('product-addtocart-button');
                const dialog = document.getElementById('product-dialog');
                dialog.showModal();
            } else {
                alert('Failed to add product to cart');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
   </script>
@endsection