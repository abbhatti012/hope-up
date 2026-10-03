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
                     <span class="text-black" aria-current="page">Our Showroom</span>
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
                  <span class="base" data-ui-id="page-title-wrapper">Our Showroom</span> 
               </h1>
            </div>
            <div class="column main">
                <style>#html-body [data-pb-style=P4A94RK]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=HHIOHGU],#html-body [data-pb-style=QHDJKSE],#html-body [data-pb-style=YGOAAH4]{width:33.3333%;align-self:stretch}#html-body [data-pb-style=HHIOHGU],#html-body [data-pb-style=MC5U3H8],#html-body [data-pb-style=QHDJKSE],#html-body [data-pb-style=YGOAAH4]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}</style>
                <div data-content-type="row" data-appearance="contained" data-element="main">
                    <div data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="P4A94RK">
                    <div class="pagebuilder-column-group" style="display: flex;" data-content-type="column-group" data-grid-size="12" data-element="main">
                        <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="HHIOHGU">
                            <div data-content-type="text" data-appearance="default" data-element="main">
                                <p><strong>Luminant Lighting</strong><br>67 NII NORTEI NYANCHI ST. DZORWULU<br>DIGITAL ADDRESS: GA-120-2542<br>GHANA, ACCRA</p>
                            </div>
                        </div>
                        <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="YGOAAH4">
                            <div data-content-type="text" data-appearance="default" data-element="main">
                                <p><strong>Mobile:</strong> +233 (0) 504 051 792<br><strong>Tel:</strong> +447931833307<br><strong>E-mail: </strong><a href="mailto:info@luminantghana.com">info@luminantghana.com</a><br></p>
                            </div>
                        </div>
                        <div class="pagebuilder-column" data-content-type="column" data-appearance="full-height" data-background-images="{}" data-element="main" data-pb-style="QHDJKSE">
                            <div data-content-type="text" data-appearance="default" data-element="main">
                                <p><strong>--Phone--</strong><br><strong>Ghana: </strong>+233 (0) 302 765981<br><strong>Ivory Coast: </strong>0022559004009 </p>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
                <div data-content-type="row" data-appearance="contained" data-element="main">
                    <div data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="MC5U3H8">
                    <div data-content-type="text" data-appearance="default" data-element="main">
                        <div class="widget block block-static-block">
                            <div data-content-type="html" data-appearance="default" data-element="main" data-decoded="true">
                                <h2 style="font-family:sefif;font-style:italic">Our Showroom</h2>
                                <div>
                                    <div class="grid12-6">
                                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3970.6917411274944!2d-0.19988192411705863!3d5.612461433077499!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xfdf9bb03c039f8b%3A0x48d99a802afcd873!2sLuminant%20Electricals!5e0!3m2!1sen!2sgh!4v1712704513633!5m2!1sen!2sgh" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
         </main>
@endsection