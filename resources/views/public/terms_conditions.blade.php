@extends('public.layout.layout')
@section('content')
<nav class="breadcrumbs block" aria-label="Breadcrumb">
            <div class="container">
               <ul class="items list-reset pt-5 md:pt-6 lg:pt-7 mb-0 rounded flex flex-wrap text-gray-105 text-sm">
                  <li class="item hidden md:flex home">
                     <a href="/" title="Go to Home Page" class="hover:text-black">Home</a>
                  </li>
                  <li class="item hidden md:flex cms_page">
                     <span aria-hidden="true" class="separator my-auto px-2">
                        <svg xmlns="https://www.w3.org/2000/svg" width="6" height="10" viewBox="0 0 8.848 14.746" fill="#fff" class="fill-black" aria-hidden="true">
                           <path id="Icon_ion-ios-arrow-right_15" data-name="Icon ion-ios-arrow-right" d="M10.625,7.269l.954-.894,7.894,7.373-7.894,7.373-.954-.889,6.935-6.484Z" transform="translate(-10.625 -6.375)"></path>
                        </svg>
                     </span>
                     <span class="text-black" aria-current="page">Terms & Conditions</span>
                  </li>
                  <li class="item flex md:hidden">
                     <a href="/" class="underline md:no-underline" title="Home">
                     Back to <span class="uppercase">Home</span></a>
                  </li>
               </ul>
            </div>
         </nav>
        
         <main id="maincontent" class="page-main">
            <div class="container flex flex-col md:flex-row flex-wrap font-semibold mb-5 md:mb-7 mt-7 sm:mt-8 lg:mt-10">
               <h1 class="page-title title-font mb-0">
                  <span class="base" data-ui-id="page-title-wrapper">Terms & Conditions</span> 
               </h1>
            </div>
            <div class="column main">
                <div data-content-type="row" data-appearance="contained" data-element="main">
                    <div data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="MC5U3H8">
                        <div class="entry-content">
                            <p><?= $setting->terms_and_conditions ?></p>
                        </div>
                    </div>
                </div>
            </div>
         </main>
@endsection