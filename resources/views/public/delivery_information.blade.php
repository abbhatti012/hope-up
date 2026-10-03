@extends('public.layout.layout')
@section('content')
<nav class="breadcrumbs block" aria-label="Breadcrumb">
            <div class="container">
               <ul class="items list-reset pt-5 md:pt-6 lg:pt-7 mb-0 rounded flex flex-wrap text-gray-105 text-sm">
                  <li class="item hidden md:flex home">
                     <a href="./" title="Go to Home Page" class="hover:text-black">Home</a>
                  </li>
                  <li class="item hidden md:flex cms_page">
                     <span aria-hidden="true" class="separator my-auto px-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="6" height="10" viewBox="0 0 8.848 14.746" fill="#fff" class="fill-black" aria-hidden="true">
                           <path id="Icon_ion-ios-arrow-right_15" data-name="Icon ion-ios-arrow-right" d="M10.625,7.269l.954-.894,7.894,7.373-7.894,7.373-.954-.889,6.935-6.484Z" transform="translate(-10.625 -6.375)"></path>
                        </svg>
                     </span>
                     <span class="text-black" aria-current="page">Delivery Information</span>
                  </li>
                  <li class="item flex md:hidden">
                     <a href="./" class="underline md:no-underline" title="Home">
                     Back to <span class="uppercase">Home</span></a>
                  </li>
               </ul>
            </div>
         </nav>
        
         <main id="maincontent" class="page-main">
            <div class="container flex flex-col md:flex-row flex-wrap font-semibold mb-5 md:mb-7 mt-7 sm:mt-8 lg:mt-10">
               <h1 class="page-title title-font mb-0">
                  <span class="base" data-ui-id="page-title-wrapper">Delivery Information</span> 
               </h1>
            </div>
            <div class="column main">
                <style>#html-body [data-pb-style=P4A94RK]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}#html-body [data-pb-style=HHIOHGU],#html-body [data-pb-style=QHDJKSE],#html-body [data-pb-style=YGOAAH4]{width:33.3333%;align-self:stretch}#html-body [data-pb-style=HHIOHGU],#html-body [data-pb-style=MC5U3H8],#html-body [data-pb-style=QHDJKSE],#html-body [data-pb-style=YGOAAH4]{justify-content:flex-start;display:flex;flex-direction:column;background-position:left top;background-size:cover;background-repeat:no-repeat;background-attachment:scroll}</style>
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
                <div data-content-type="row" data-appearance="contained" data-element="main">
                    <div data-enable-parallax="0" data-parallax-speed="0.5" data-background-images="{}" data-background-type="image" data-video-loop="true" data-video-play-only-visible="true" data-video-lazy-load="true" data-video-fallback-src="" data-element="inner" data-pb-style="MC5U3H8">
                        <div data-content-type="text" data-appearance="default" data-element="main">
                            <p>All of our parcels are shipped using Interlinks Predict Service. Tracking information is updated on you order when your order is ready for dispatch. The Predict service includes an on the day up date with a one hour delivery slot, you will receive an email or if you supply a mobile number a SMS. If the slot provided is not convenient just reply to the message using the instructions provided.</p>
                            <br>
                            <p>Individual dispatch estimates can be found on each product page; we check and update our stock levels regularly. If an item is out of stock it will be available for back order and we will ship it as soon as it becomes available. We will email you with an estimated date we expect the item to be back in stock when you place your order.</p>
                            <br>
                            <p>Once your item is ready for dispatch we use a one day delivery service (two days for offshore).</p>
                            <br>
                            <strong>We aim to dispatch all orders within 5-7 working days from receipt of your order. Some items do take a little longer to dispatch, especially when they are made to order or if they are being sourced direct from our suppliers in Europe. Please contact us if you need confirmation or estimated delivery times for these items.</strong>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
         </main>
@endsection