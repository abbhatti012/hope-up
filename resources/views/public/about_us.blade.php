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
                    <span class="text-black" aria-current="page">About Us</span>
                </li>
                <li class="item flex md:hidden">
                    <a href="/" class="underline md:no-underline" title="Home">
                    Back to <span class="uppercase">Home</span></a>
                </li>
            </ul>
        </div>
    </nav>
    <style>
        .columns {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
        }
    </style>
    <main id="maincontent" class="page-main" x-data="accountForm()">
        <div class="container flex flex-col md:flex-row flex-wrap font-semibold mb-5 md:mb-7 mt-7 sm:mt-8 lg:mt-10">
            
        </div>
        <div class="flex flex-col md:flex-row columns">
            <div class="sidebar sidebar-main md:w-2/3">
                <div class="block account-nav filter-option py-4 mb-6 md:mb-0">
                    <button type="button" class="title account-nav-title flex justify-between items-center hover:text-secondary-darker w-full">
                        <span class="text-lg title">About Us</span>
                    </button>
                    <div class="delimiter border-b border-container w-full mt-4 mb-3 hidden md:block"></div>
                    <div class="content account-nav-content" id="account-nav">
                        <strong>
                            {{ $setting->about_title }}
                        </strong>
                        <p>
                            {{ $setting->about_description }}
                        </p>
                        
                    </div>
                </div>
            </div>

            <div class="column main md:w-2/3">
                <iframe class="w-full h-full md:h-full" src="{{{ $setting->about_video }}}" frameborder="0" allowfullscreen></iframe>
            </div>
        </div>
        <div class="columns flex flex-col md:flex-row">
            <div class="column-main md:w-1/2">
                <div class="account-nav filter-option py-4 mb-6 md:mb-0" >
                    <button type="button" class="title account-nav-title flex justify-between items-center hover:text-secondary-darker w-full">
                        <span class="text-lg title">CORE VALUES</span>
                    </button>
                    <div class="delimiter border-b border-container w-full mt-4 mb-3 hidden md:block"></div>
                    <div class="content account-nav-content" id="account-nav">
                        <div>
                            <strong>
                                <i class="fas fa-book"></i> {{ $setting->about_title1 }}
                            </strong>
                            <p>
                            {{ $setting->about_des1 }}
                            </p>
                        </div>
                        <div>
                            <strong>
                                <i class="fas fa-book"></i> {{ $setting->about_title2 }}
                            </strong>
                            <p>
                            {{ $setting->about_des2 }}
                            </p>
                        </div>

                        <div>
                            <strong>
                                <i class="fas fa-book"></i> {{ $setting->about_title3 }}
                            </strong>
                            <p>
                            {{ $setting->about_des3 }}
                            </p>
                        </div>
                        
                        <div>
                            <strong>
                                <i class="fas fa-book"></i> {{ $setting->about_title4 }}
                            </strong>
                            <p>
                            {{ $setting->about_des4 }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sidebar sidebar-main md:w-1/2">
                <div class="account-nav filter-option py-4 mb-6 md:mb-0" >
                    <button type="button" class="title account-nav-title flex justify-between items-center hover:text-secondary-darker w-full">
                        <span class="text-lg title">Our Promise</span>
                    </button>
                    <div class="delimiter border-b border-container w-full mt-4 mb-3 hidden md:block"></div>
                    <div class="content account-nav-content" id="account-nav">
                        <div>
                            <strong>
                                <i class="fas fa-book"></i> {{ $setting->about_title5 }}
                            </strong>
                            <p>
                            {{ $setting->about_des5 }}
                            </p>
                        </div>
                        <div>
                            <strong>
                                <i class="fas fa-book"></i> {{ $setting->about_title6 }}
                            </strong>
                            <p>
                            {{ $setting->about_des6 }}
                            </p>
                        </div>
                        <div>
                            <strong>
                                <i class="fas fa-book"></i> {{ $setting->about_title7 }}
                            </strong>
                            <p>
                            {{ $setting->about_des7 }}
                            </p>
                        </div>    
                        <div>
                            <strong>
                                <i class="fas fa-book"></i> {{ $setting->about_title8 }}
                            </strong>
                            <p>
                            {{ $setting->about_des8 }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>
        .account-nav-content strong i {
            margin-right: 5px;
            color: #ffA500;
            content: "\e96b";
        }
        .col, .columns, .gallery-item{
            padding: 0 50px 30px !important;
        }
    </style>
@endsection