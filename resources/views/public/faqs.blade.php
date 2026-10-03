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
                <svg xmlns="http://www.w3.org/2000/svg" width="6" height="10" viewBox="0 0 8.848 14.746" fill="#fff" class="fill-black" aria-hidden="true">
                    <path id="Icon_ion-ios-arrow-right_15" data-name="Icon ion-ios-arrow-right" d="M10.625,7.269l.954-.894,7.894,7.373-7.894,7.373-.954-.889,6.935-6.484Z" transform="translate(-10.625 -6.375)"></path>
                </svg>
                </span>
                <span class="text-black" aria-current="page">Frequently Asked Questions</span>
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
            <span class="base" data-ui-id="page-title-wrapper">Frequently Asked Questions</span> 
        </h1>
    </div>
    <div class="column main">
        <div data-content-type="row" data-appearance="contained" data-element="main">
            <div data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="MC5U3H8">
                <div data-content-type="text" data-appearance="default" data-element="main">
                <div data-content-type="row" data-appearance="contained" data-element="main">
                    <div data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src data-element="inner" data-pb-style="GWAO1NX">
                        <div data-content-type="html" data-appearance="default" data-element="main" data-decoded="true">
                            <section class="flex lg:flex-nowrap flex-wrap 2xl:gap-36 xl:gap-28 lg:gap-12 xl:py-24 lg:pb-20 md:pb-16 sm:pb-14 pb-12">
                                <div class="w-full lg:w-[calc(100%)]">
                                    <!-- <div class="border-b border-gray-30" x-data="{ open: false }">
                                        <button role="tab" @click="open = !open" class="group gap-2 flex w-full items-baseline justify-between !pt-0 xl:py-8 lg:py-7 md:py-6 py-5 font-semibold lg:text-[22px] md:text-xl xxs:text-lg text-base">
                                            <span class="text-left">What are the delivery charges for orders from the Online Shop?</span>
                                            <span x-cloak :class="{'rotate-180': open}" class="transition-transform">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="10" viewbox="0 0 16.366 9.819" fill="#000" class="md:h-3 md:w-5" aria-hidden="true">
                                                    <path id="Icon_ion-ios-arrow-right_15" data-name="Icon ion-ios-arrow-right" d="M10.625,7.367l1.059-.992,8.761,8.183-8.761,8.183-1.059-.987,7.7-7.2Z" transform="translate(22.741 -10.625) rotate(90)"></path>
                                                </svg>
                                            </span>
                                        </button>
                                        <div role="tabpanel" x-show="open" x-cloak>
                                            <div class="xl:pb-8 lg:pb-7 md:pb-6 pb-5">
                                            <style>
                                                table {
                                                    border-collapse: collapse;
                                                    width: 90%;
                                                    margin: auto;
                                                    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                                                }

                                                table th, table td {
                                                    border: 1px solid #ddd;
                                                    padding: 8px;
                                                    text-align: left;
                                                }

                                                table th {
                                                    background-color: #f4f4f4;
                                                }

                                                table tr:nth-child(even) {
                                                    background-color: #f9f9f9;
                                                }

                                                table tr:hover {
                                                    background-color: #f1f1f1;
                                                }

                                                table th, table td {
                                                    padding: 12px;
                                                    text-align: center;
                                                }
                                            </style>
                                            <table>
                                                <tbody>
                                                    <tr>
                                                        <td width="414"><strong>Destination.</strong></td>
                                                        <td width="104"><strong>Under £99.99</strong></td>
                                                        <td width="99"><strong>Over £99.99</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="414">Mainland UK excluding The Highlands</td>
                                                        <td width="104">£5.99</td>
                                                        <td width="99">Free</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="414">Offshore UK including Highlands<br>
                                                            -Channel Islands<br>
                                                            -Isle of Man<br>
                                                            -Isle of White<br>
                                                            -Scilly Isles<br>
                                                            -Scottish Islands<br>
                                                            -Scottish Highlands<br>
                                                            -Northern Island
                                                        </td>
                                                        <td width="104">£14.99</td>
                                                        <td width="99">£14.99</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="414">Europe<br>
                                                            -France<br>
                                                            -Germany<br>
                                                            -Italy<br>
                                                            -Spain
                                                        </td>
                                                        <td width="104">£14.99</td>
                                                        <td width="99">£14.99</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            </div>
                                        </div>
                                    </div> -->
                                    @foreach($faqs as $faq)
                                    <div class="border-b border-gray-30" x-data="{ open: false }">
                                        <button role="tab" @click="open = !open" class="group gap-2 flex w-full items-baseline justify-between xl:py-8 lg:py-7 md:py-6 py-5 font-semibold lg:text-[22px] md:text-xl xxs:text-lg text-base">
                                            <span class="text-left">{{ $faq->title }}</span>
                                            <span x-cloak :class="{'rotate-180': open}" class="transition-transform">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="10" viewbox="0 0 16.366 9.819" fill="#000" class="md:h-3 md:w-5" aria-hidden="true">
                                                    <path id="Icon_ion-ios-arrow-right_16" data-name="Icon ion-ios-arrow-right" d="M10.625,7.367l1.059-.992,8.761,8.183-8.761,8.183-1.059-.987,7.7-7.2Z" transform="translate(22.741 -10.625) rotate(90)"></path>
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
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    </main>
@endsection