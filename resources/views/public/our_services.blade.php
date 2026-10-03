@extends('public.layout.layout')
@section('content')
    <style>
        .our-services .fas {
            color: #ffA500;
            font-size: 35px;
        }
        #account-nav .pd-20 {
            padding: 20px 0 0 0;
        }
    </style>
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
                    <span class="text-black" aria-current="page">Our Services</span>
                </li>
                <li class="item flex md:hidden">
                    <a href="/" class="underline md:no-underline" title="Home">
                    Back to <span class="uppercase">Home</span></a>
                </li>
            </ul>
        </div>
    </nav>
    <main id="maincontent" class="page-main" style="padding: 0 100px 30px;">      
        <div class="columns flex flex-col md:flex-row our-services">
            <div class="column-main md:w-1/2">
                <div class="account-nav filter-option py-4 mb-6 md:mb-0" >
                    <div class="content account-nav-content" id="account-nav">
                        <div class="pd-20">
                            <strong>
                                <i class="fas fa-gopuram"></i> {{ $setting->service_title1 }}
                            </strong>
                            <p>
                                {{ $setting->service_des1 }}
                            </p>
                        </div>
                        <div class="pd-20">
                            <strong>
                                <i class="fas fa-tools"></i> {{ $setting->service_title2 }}
                            </strong>
                            <p>
                            {{ $setting->service_des2 }}
                            </p>
                        </div>
                        <div class="pd-20">
                            <strong>
                                <i class="fas fa-hammer"></i> {{ $setting->service_title3 }}
                            </strong>
                            <p>
                            {{ $setting->service_des3 }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sidebar sidebar-main md:w-1/2">
                <div class="account-nav filter-option py-4 mb-6 md:mb-0" >
                    <div class="content account-nav-content" id="account-nav">
                        <div class="pd-20">
                            <strong>
                                <i class="fas fa-lightbulb"></i> {{ $setting->service_title4 }}
                            </strong>
                            <p>
                            {{ $setting->service_des4 }}
                            </p>
                        </div>
                        <div class="pd-20">
                            <strong>
                                <i class="fas fa-briefcase"></i> {{ $setting->service_title5 }}
                            </strong>
                            <p>
                            {{ $setting->service_des5 }}
                            </p>
                        </div>
                        <div class="pd-20">
                            <strong>
                                <i class="fas fa-broom"></i> {{ $setting->service_title6 }}
                            </strong>
                            <p>
                            {{ $setting->service_des6 }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <br>
            <center>
                <p>
                {{ $setting->service_description }}
                </p>
            </center>
    </main>
@endsection