@extends('public.layout.layout')
@section('content')
<script src="//unpkg.com/alpinejs" defer></script>
<style>
    .form-edit-account .form-input {
        width: 100%;
    }
</style>
<?php

$countries = [
    ['code' => 'AF', 'name' => 'Afghanistan'],
    ['code' => 'AL', 'name' => 'Albania'],
    ['code' => 'DZ', 'name' => 'Algeria'],
    ['code' => 'AS', 'name' => 'American Samoa'],
    ['code' => 'AD', 'name' => 'Andorra'],
    ['code' => 'AO', 'name' => 'Angola'],
    ['code' => 'AI', 'name' => 'Anguilla'],
    ['code' => 'AQ', 'name' => 'Antarctica'],
    ['code' => 'AG', 'name' => 'Antigua and Barbuda'],
    ['code' => 'AR', 'name' => 'Argentina'],
    ['code' => 'AM', 'name' => 'Armenia'],
    ['code' => 'AW', 'name' => 'Aruba'],
    ['code' => 'AU', 'name' => 'Australia'],
    ['code' => 'AT', 'name' => 'Austria'],
    ['code' => 'AZ', 'name' => 'Azerbaijan'],
    ['code' => 'BS', 'name' => 'Bahamas'],
    ['code' => 'BH', 'name' => 'Bahrain'],
    ['code' => 'BD', 'name' => 'Bangladesh'],
    ['code' => 'BB', 'name' => 'Barbados'],
    ['code' => 'BY', 'name' => 'Belarus'],
    ['code' => 'BE', 'name' => 'Belgium'],
    ['code' => 'BZ', 'name' => 'Belize'],
    ['code' => 'BJ', 'name' => 'Benin'],
    ['code' => 'BM', 'name' => 'Bermuda'],
    ['code' => 'BT', 'name' => 'Bhutan'],
    ['code' => 'BO', 'name' => 'Bolivia'],
    ['code' => 'BA', 'name' => 'Bosnia and Herzegovina'],
    ['code' => 'BW', 'name' => 'Botswana'],
    ['code' => 'BR', 'name' => 'Brazil'],
    ['code' => 'IO', 'name' => 'British Indian Ocean Territory'],
    ['code' => 'VG', 'name' => 'British Virgin Islands'],
    ['code' => 'BN', 'name' => 'Brunei'],
    ['code' => 'BG', 'name' => 'Bulgaria'],
    ['code' => 'BF', 'name' => 'Burkina Faso'],
    ['code' => 'BI', 'name' => 'Burundi'],
    ['code' => 'CV', 'name' => 'Cabo Verde'],
    ['code' => 'KH', 'name' => 'Cambodia'],
    ['code' => 'CM', 'name' => 'Cameroon'],
    ['code' => 'CA', 'name' => 'Canada'],
    ['code' => 'KY', 'name' => 'Cayman Islands'],
    ['code' => 'CF', 'name' => 'Central African Republic'],
    ['code' => 'TD', 'name' => 'Chad'],
    ['code' => 'CL', 'name' => 'Chile'],
    ['code' => 'CN', 'name' => 'China'],
    ['code' => 'CO', 'name' => 'Colombia'],
    ['code' => 'KM', 'name' => 'Comoros'],
    ['code' => 'CG', 'name' => 'Congo (Brazzaville)'],
    ['code' => 'CD', 'name' => 'Congo (Kinshasa)'],
    ['code' => 'CK', 'name' => 'Cook Islands'],
    ['code' => 'CR', 'name' => 'Costa Rica'],
    ['code' => 'HR', 'name' => 'Croatia'],
    ['code' => 'CU', 'name' => 'Cuba'],
    ['code' => 'CW', 'name' => 'Curaçao'],
    ['code' => 'CY', 'name' => 'Cyprus'],
    ['code' => 'CZ', 'name' => 'Czech Republic'],
    ['code' => 'DK', 'name' => 'Denmark'],
    ['code' => 'DJ', 'name' => 'Djibouti'],
    ['code' => 'DM', 'name' => 'Dominica'],
    ['code' => 'DO', 'name' => 'Dominican Republic'],
    ['code' => 'EC', 'name' => 'Ecuador'],
    ['code' => 'EG', 'name' => 'Egypt'],
    ['code' => 'SV', 'name' => 'El Salvador'],
    ['code' => 'GQ', 'name' => 'Equatorial Guinea'],
    ['code' => 'ER', 'name' => 'Eritrea'],
    ['code' => 'EE', 'name' => 'Estonia'],
    ['code' => 'SZ', 'name' => 'Eswatini'],
    ['code' => 'ET', 'name' => 'Ethiopia'],
    ['code' => 'FJ', 'name' => 'Fiji'],
    ['code' => 'FI', 'name' => 'Finland'],
    ['code' => 'FR', 'name' => 'France'],
    ['code' => 'GF', 'name' => 'French Guiana'],
    ['code' => 'PF', 'name' => 'French Polynesia'],
    ['code' => 'GA', 'name' => 'Gabon'],
    ['code' => 'GM', 'name' => 'Gambia'],
    ['code' => 'GE', 'name' => 'Georgia'],
    ['code' => 'DE', 'name' => 'Germany'],
    ['code' => 'GH', 'name' => 'Ghana'],
    ['code' => 'GI', 'name' => 'Gibraltar'],
    ['code' => 'GR', 'name' => 'Greece'],
    ['code' => 'GL', 'name' => 'Greenland'],
    ['code' => 'GD', 'name' => 'Grenada'],
    ['code' => 'GP', 'name' => 'Guadeloupe'],
    ['code' => 'GU', 'name' => 'Guam'],
    ['code' => 'GT', 'name' => 'Guatemala'],
    ['code' => 'GN', 'name' => 'Guinea'],
    ['code' => 'GW', 'name' => 'Guinea-Bissau'],
    ['code' => 'GY', 'name' => 'Guyana'],
    ['code' => 'HT', 'name' => 'Haiti'],
    ['code' => 'HN', 'name' => 'Honduras'],
    ['code' => 'HK', 'name' => 'Hong Kong'],
    ['code' => 'HU', 'name' => 'Hungary'],
    ['code' => 'IS', 'name' => 'Iceland'],
    ['code' => 'IN', 'name' => 'India'],
    ['code' => 'ID', 'name' => 'Indonesia'],
    ['code' => 'IR', 'name' => 'Iran'],
    ['code' => 'IQ', 'name' => 'Iraq'],
    ['code' => 'IE', 'name' => 'Ireland'],
    ['code' => 'IL', 'name' => 'Israel'],
    ['code' => 'IT', 'name' => 'Italy'],
    ['code' => 'JM', 'name' => 'Jamaica'],
    ['code' => 'JP', 'name' => 'Japan'],
    ['code' => 'JO', 'name' => 'Jordan'],
    ['code' => 'KZ', 'name' => 'Kazakhstan'],
    ['code' => 'KE', 'name' => 'Kenya'],
    ['code' => 'KI', 'name' => 'Kiribati'],
    ['code' => 'KR', 'name' => 'Korea, South'],
    ['code' => 'KW', 'name' => 'Kuwait'],
    ['code' => 'KG', 'name' => 'Kyrgyzstan'],
    ['code' => 'LA', 'name' => 'Laos'],
    ['code' => 'LV', 'name' => 'Latvia'],
    ['code' => 'LB', 'name' => 'Lebanon'],
    ['code' => 'LS', 'name' => 'Lesotho'],
    ['code' => 'LR', 'name' => 'Liberia'],
    ['code' => 'LY', 'name' => 'Libya'],
    ['code' => 'LI', 'name' => 'Liechtenstein'],
    ['code' => 'LT', 'name' => 'Lithuania'],
    ['code' => 'LU', 'name' => 'Luxembourg'],
    ['code' => 'MO', 'name' => 'Macau'],
    ['code' => 'MG', 'name' => 'Madagascar'],
    ['code' => 'MW', 'name' => 'Malawi'],
    ['code' => 'MY', 'name' => 'Malaysia'],
    ['code' => 'MV', 'name' => 'Maldives'],
    ['code' => 'ML', 'name' => 'Mali'],
    ['code' => 'MT', 'name' => 'Malta'],
    ['code' => 'MH', 'name' => 'Marshall Islands'],
    ['code' => 'MQ', 'name' => 'Martinique'],
    ['code' => 'MR', 'name' => 'Mauritania'],
    ['code' => 'MU', 'name' => 'Mauritius'],
    ['code' => 'MX', 'name' => 'Mexico'],
    ['code' => 'FM', 'name' => 'Micronesia'],
    ['code' => 'MD', 'name' => 'Moldova'],
    ['code' => 'MC', 'name' => 'Monaco'],
    ['code' => 'MN', 'name' => 'Mongolia'],
    ['code' => 'ME', 'name' => 'Montenegro'],
    ['code' => 'MS', 'name' => 'Montserrat'],
    ['code' => 'MA', 'name' => 'Morocco'],
    ['code' => 'MZ', 'name' => 'Mozambique'],
    ['code' => 'MM', 'name' => 'Myanmar'],
    ['code' => 'NA', 'name' => 'Namibia'],
    ['code' => 'NR', 'name' => 'Nauru'],
    ['code' => 'NP', 'name' => 'Nepal'],
    ['code' => 'NL', 'name' => 'Netherlands'],
    ['code' => 'NC', 'name' => 'New Caledonia'],
    ['code' => 'NZ', 'name' => 'New Zealand'],
    ['code' => 'NI', 'name' => 'Nicaragua'],
    ['code' => 'NE', 'name' => 'Niger'],
    ['code' => 'NG', 'name' => 'Nigeria'],
    ['code' => 'MK', 'name' => 'North Macedonia'],
    ['code' => 'NO', 'name' => 'Norway'],
    ['code' => 'OM', 'name' => 'Oman'],
    ['code' => 'PK', 'name' => 'Pakistan'],
    ['code' => 'PW', 'name' => 'Palau'],
    ['code' => 'PS', 'name' => 'Palestine'],
    ['code' => 'PA', 'name' => 'Panama'],
    ['code' => 'PG', 'name' => 'Papua New Guinea'],
    ['code' => 'PY', 'name' => 'Paraguay'],
    ['code' => 'PE', 'name' => 'Peru'],
    ['code' => 'PH', 'name' => 'Philippines'],
    ['code' => 'PL', 'name' => 'Poland'],
    ['code' => 'PT', 'name' => 'Portugal'],
    ['code' => 'PR', 'name' => 'Puerto Rico'],
    ['code' => 'QA', 'name' => 'Qatar'],
    ['code' => 'RE', 'name' => 'Réunion'],
    ['code' => 'RO', 'name' => 'Romania'],
    ['code' => 'RU', 'name' => 'Russia'],
    ['code' => 'RW', 'name' => 'Rwanda'],
    ['code' => 'KN', 'name' => 'Saint Kitts and Nevis'],
    ['code' => 'LC', 'name' => 'Saint Lucia'],
    ['code' => 'VC', 'name' => 'Saint Vincent and the Grenadines'],
    ['code' => 'WS', 'name' => 'Samoa'],
    ['code' => 'SM', 'name' => 'San Marino'],
    ['code' => 'ST', 'name' => 'São Tomé and Príncipe'],
    ['code' => 'SA', 'name' => 'Saudi Arabia'],
    ['code' => 'SN', 'name' => 'Senegal'],
    ['code' => 'RS', 'name' => 'Serbia'],
    ['code' => 'SC', 'name' => 'Seychelles'],
    ['code' => 'SL', 'name' => 'Sierra Leone'],
    ['code' => 'SG', 'name' => 'Singapore'],
    ['code' => 'SX', 'name' => 'Sint Maarten'],
    ['code' => 'SK', 'name' => 'Slovakia'],
    ['code' => 'SI', 'name' => 'Slovenia'],
    ['code' => 'SB', 'name' => 'Solomon Islands'],
    ['code' => 'SO', 'name' => 'Somalia'],
    ['code' => 'ZA', 'name' => 'South Africa'],
    ['code' => 'KR', 'name' => 'South Korea'],
    ['code' => 'SS', 'name' => 'South Sudan'],
    ['code' => 'ES', 'name' => 'Spain'],
    ['code' => 'LK', 'name' => 'Sri Lanka'],
    ['code' => 'SD', 'name' => 'Sudan'],
    ['code' => 'SR', 'name' => 'Suriname'],
    ['code' => 'SE', 'name' => 'Sweden'],
    ['code' => 'CH', 'name' => 'Switzerland'],
    ['code' => 'SY', 'name' => 'Syria'],
    ['code' => 'TW', 'name' => 'Taiwan'],
    ['code' => 'TJ', 'name' => 'Tajikistan'],
    ['code' => 'TZ', 'name' => 'Tanzania'],
    ['code' => 'TH', 'name' => 'Thailand'],
    ['code' => 'TL', 'name' => 'Timor-Leste'],
    ['code' => 'TG', 'name' => 'Togo'],
    ['code' => 'TK', 'name' => 'Tokelau'],
    ['code' => 'TO', 'name' => 'Tonga'],
    ['code' => 'TT', 'name' => 'Trinidad and Tobago'],
    ['code' => 'TN', 'name' => 'Tunisia'],
    ['code' => 'TR', 'name' => 'Turkey'],
    ['code' => 'TM', 'name' => 'Turkmenistan'],
    ['code' => 'TV', 'name' => 'Tuvalu'],
    ['code' => 'UG', 'name' => 'Uganda'],
    ['code' => 'UA', 'name' => 'Ukraine'],
    ['code' => 'AE', 'name' => 'United Arab Emirates'],
    ['code' => 'GB', 'name' => 'United Kingdom'],
    ['code' => 'US', 'name' => 'United States'],
    ['code' => 'UY', 'name' => 'Uruguay'],
    ['code' => 'UZ', 'name' => 'Uzbekistan'],
    ['code' => 'VU', 'name' => 'Vanuatu'],
    ['code' => 'VE', 'name' => 'Venezuela'],
    ['code' => 'VN', 'name' => 'Vietnam'],
    ['code' => 'WF', 'name' => 'Wallis and Futuna'],
    ['code' => 'YE', 'name' => 'Yemen'],
    ['code' => 'ZM', 'name' => 'Zambia'],
    ['code' => 'ZW', 'name' => 'Zimbabwe'],
];

?>
<main id="maincontent" class="page-main" x-data="accountForm()">
    <div class="container flex flex-col md:flex-row flex-wrap font-semibold mb-5 md:mb-7 mt-7 sm:mt-8 lg:mt-10">
        <h1 class="page-title title-font mb-0">
            <span class="base" data-ui-id="page-title-wrapper">My Account</span>
        </h1>
    </div>
    <div class="columns">
        <aside class="sidebar sidebar-main">
            <div class="block account-nav card filter-option py-4 mb-6 md:mb-0" x-data="initAccountNavigation()" x-init="checkIsMobileResolution()" @resize.window.debounce="checkIsMobileResolution()" @visibilitychange.window.debounce="checkIsMobileResolution()">
                <button type="button" class="title account-nav-title flex justify-between items-center hover:text-secondary-darker w-full">
                    <span class="text-lg title">My Account</span>
                    <span class="px-1 py-1 md:hidden" x-ref="AccountNavigationMobileToggleIcon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 17.406 11.087" stroke="#000" class="transition-transform duration-300 ease-in-out transform rotate-180" :class="{ 'rotate-180': blockOpen }" aria-hidden="true" focusable="false">
                            <path id="Icon_ion-ios-arrow-right_15" data-name="Icon ion-ios-arrow-right" d="M10.625,7.347l1.037-.972,8.585,8.019-8.585,8.019-1.037-.967,7.542-7.051Z" transform="translate(23.096 -9.892) rotate(90)" stroke-width="1" />
                        </svg>
                    </span>
                </button>
                <div :class="{ 'hidden': !blockOpen }" class="delimiter border-b border-container w-full mt-4 mb-3 hidden md:block"></div>
                <div class="content account-nav-content hidden md:block" :class="{ 'hidden': !blockOpen }" id="account-nav">
                    @include('public.aside-menu')
                </div>
            </div>
        </aside>
        <div class="column main">
            @if ($errors->any())
                <div class="bg-red-500 text-white p-4 rounded-md mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            @if (session('success'))
                <div class="bg-green-500 text-white p-4 rounded-md mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if($address)
            <form action="{{ route('billing-information') }}" id="shipping" class="form-address-edit card" method="POST" novalidate>
                @csrf
                <div class="grid grid-cols-12 gap-x-4">
                    <div class="col-span-12 field-wrapper field-type-text field field-reserved field-firstname md&#x3A;col-span-12" wire:key="field-wrapper-shipping-address-firstname">
                        <div class="w-full font-medium text-gray-700 required">
                        <label for="shipping-firstname" class="label label-firstname">
                        <span>Email</span>
                        </label>
                        <div class="flex items-center gap-4">
                            <input class="block w-full form-input grow renderer-text firstname" value="{{ old('email', $address->email) }}" type="email" id="email" name="email" placeholder="Enter Email" />
                        </div>
                        @error('email')<div class="text-red-600">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-span-12 field-wrapper field-type-text field field-reserved field-firstname md&#x3A;col-span-12" wire:key="field-wrapper-shipping-address-firstname">
                        <div class="w-full font-medium text-gray-700 required">
                        <label for="shipping-firstname" class="label label-firstname">
                        <span>First Name</span>
                        </label>
                        <div class="flex items-center gap-4">
                            <input class="block w-full form-input grow renderer-text firstname" type="text" value="{{ old('firstname', $address->firstname) }}" id="shipping-firstname" name="firstname"placeholder="First Name" />
                        </div>
                        @error('firstname')<div class="text-red-600">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-span-12 field-wrapper field-type-text field field-reserved field-lastname md&#x3A;col-span-12" wire:key="field-wrapper-shipping-address-lastname">
                        <div class="w-full font-medium text-gray-700 required">
                        <label for="shipping-lastname" class="label label-lastname">
                        <span>
                        Last Name </span>
                        </label>
                        <div class="flex items-center gap-4">
                            <input class="block w-full form-input grow renderer-text lastname" type="text" value="{{ old('lastname', $address->lastname) }}" id="shipping-lastname" name="lastname" data-attribute-address="true" data-magewire-is-valid="1" data-validate="&#x7B;&quot;magewire&quot;&#x3A;true&#x7D;" data-autosave wire:loading.class="loading" wire:loading.attr="disabled" wire:target="lastname" wire:model.defer="address.lastname" placeholder="Last Name" />
                        </div>
                        @error('lastname')<div class="text-red-600">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-span-12 field-wrapper field-type-tel field field-reserved field-telephone md&#x3A;col-span-12" wire:key="field-wrapper-shipping-address-telephone">
                        <div class="w-full font-medium text-gray-700 required">
                        <label for="shipping-telephone" class="label label-telephone">
                        <span>
                        Phone Number </span>
                        </label>
                            <div class="flex items-center gap-4">
                                <input class="block w-full form-input grow renderer-text telephone" type="tel" data-form="shipping" value="{{ old('telephone', $address->telephone) }}" id="shipping-telephone" name="telephone" data-attribute-address="true" data-magewire-is-valid="1" data-validate="&#x7B;&quot;magewire&quot;&#x3A;true&#x7D;" data-autosave wire:loading.class="loading" wire:loading.attr="disabled" wire:target="telephone" wire:model.defer="address.telephone" placeholder="Phone Number" />
                                <div class="flex gap-2 items-center tooltip" x-data="{ tooltipVisible: false }" role="tooltip">
                                    <div x-on:mouseenter.self="tooltipVisible = true" x-on:mouseleave.self="tooltipVisible = false" x-on:click.outside.away="tooltipVisible = false" class="relative hover:cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8 opacity-75 hover:opacity-100 mt-1 icon" width="24" height="24" role="img">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        <title>question-mark-circle</title>
                                        </svg>
                                        <template x-if="tooltipVisible">
                                        <div class="absolute -right-3 px-3 pt-3 w-80">
                                            <div class="shadow-lg px-3 py-2 bg-gray-500 relative text-white rounded-md">
                                                <div class="h-3 w-3 bg-gray-500 rotate-45 transform origin-bottom-left absolute right-3 -top-3"></div>
                                                For delivery questions. 
                                            </div>
                                        </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        @error('telephone')<div class="text-red-600">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-span-12 group group-street field-wrapper field-type-text field field-reserved field-street md&#x3A;col-span-12" wire:key="field-wrapper-shipping-address-street-0">
                        <div class="w-full font-medium text-gray-700 required">
                        <label for="shipping-street-0" class="label label-street">
                        <span>
                        Street Address </span>
                        </label>
                        <div class="space-y-2">
                            <div class="flex items-center gap-4">
                                <input class="block w-full form-input grow renderer-text street" type="text" data-form="shipping" value="{{ old('streetAddress', $address->street_address) }}" name="streetAddress" placeholder="Street Address" />
                            </div>
                            @error('streetAddress')<div class="text-red-600">{{ $message }}</div>@enderror
                        </div>
                        </div>
                    </div>
                    <div class="col-span-12 field-wrapper field-type-text field field-reserved field-city md&#x3A;col-span-12" wire:key="field-wrapper-shipping-address-city">
                        <div class="w-full font-medium text-gray-700 required">
                        <label for="shipping-city" class="label label-city">
                        <span>
                        City </span>
                        </label>
                        <div class="flex items-center gap-4">
                            <input class="block w-full form-input grow renderer-text city" type="text" id="shipping-city" name="city" value="{{ old('city', $address->city) }}" placeholder="City" />
                        </div>
                        @error('city')<div class="text-red-600">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-span-12 field-wrapper field-type-text field field-reserved field-postcode md&#x3A;col-span-12" wire:key="field-wrapper-shipping-address-postcode">
                        <div class="w-full font-medium text-gray-700">
                        <label for="shipping-postcode" class="label label-postcode">
                        <span>
                        Postcode </span>
                        </label>
                        <div class="flex items-center gap-4">
                            <input class="block w-full form-input grow renderer-text postcode" type="text" id="shipping-postcode" name="postcode" value="{{ $address->postcode }}" placeholder="Postcode" />
                        </div>
                        </div>
                    </div>
                    <div class="col-span-12 field-wrapper field-type-select field field-reserved field-country md&#x3A;col-span-12" wire:key="field-wrapper-shipping-address-country">
                        <div class="block font-medium text-gray-700 required">
                        <label for="shipping-country" class="label label-country">
                        <span>
                        Country </span>
                        </label>
                        <div class="flex items-center gap-4">
                        <select class="block w-full form-input renderer-select country" name="country" data-form="shipping" data-attribute="country">
                            @foreach($countries as $country)
                                <option value="{{ $country['code'] }}" <?php if($country['code'] === $address->country) { echo 'selected';} ?>>{{ $country['name'] }}</option>
                            @endforeach
                        </select>

                        </div>
                        @error('country')<div class="text-red-600">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                <div class="xs:mt-0 mt-4 xs:w-auto w-full">
                    <button type="submit" class="btn btn-primary checkout justify-center before:absolute before:bg-white before:duration-1000 before:ease before:h-14 before:opacity-10 before:right-0 before:rotate-6 before:top-0 before:translate-x-12 before:w-6 hover:before:-translate-x-[35rem] relative transition-all hover:bg-kes-green hover:border-kes-green hover:text-white overflow-hidden" id="checkout-link-button">
                        Submit
                    </button>
                </div>
            </form>
            @else
            <form action="{{ route('billing-information') }}" id="shipping" class="form-address-edit card" method="POST" novalidate>
                @csrf
                <div class="grid grid-cols-12 gap-x-4">
                    <div class="col-span-12 field-wrapper field-type-text field field-reserved field-firstname md&#x3A;col-span-12" wire:key="field-wrapper-shipping-address-firstname">
                        <div class="w-full font-medium text-gray-700 required">
                        <label for="shipping-firstname" class="label label-firstname">
                        <span>Email</span>
                        </label>
                        <div class="flex items-center gap-4">
                            <input class="block w-full form-input grow renderer-text firstname" value="{{ old('email') }}" type="email" id="email" name="email" placeholder="Enter Email" />
                        </div>
                        @error('email')<div class="text-red-600">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-span-12 field-wrapper field-type-text field field-reserved field-firstname md&#x3A;col-span-12" wire:key="field-wrapper-shipping-address-firstname">
                        <div class="w-full font-medium text-gray-700 required">
                        <label for="shipping-firstname" class="label label-firstname">
                        <span>First Name</span>
                        </label>
                        <div class="flex items-center gap-4">
                            <input class="block w-full form-input grow renderer-text firstname" type="text" value="{{ old('firstname') }}" id="shipping-firstname" name="firstname"placeholder="First Name" />
                        </div>
                        @error('firstname')<div class="text-red-600">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-span-12 field-wrapper field-type-text field field-reserved field-lastname md&#x3A;col-span-12" wire:key="field-wrapper-shipping-address-lastname">
                        <div class="w-full font-medium text-gray-700 required">
                        <label for="shipping-lastname" class="label label-lastname">
                        <span>
                        Last Name </span>
                        </label>
                        <div class="flex items-center gap-4">
                            <input class="block w-full form-input grow renderer-text lastname" type="text" value="{{ old('lastname') }}" id="shipping-lastname" name="lastname" data-attribute-address="true" data-magewire-is-valid="1" data-validate="&#x7B;&quot;magewire&quot;&#x3A;true&#x7D;" data-autosave wire:loading.class="loading" wire:loading.attr="disabled" wire:target="lastname" wire:model.defer="address.lastname" placeholder="Last Name" />
                        </div>
                        @error('lastname')<div class="text-red-600">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-span-12 field-wrapper field-type-tel field field-reserved field-telephone md&#x3A;col-span-12" wire:key="field-wrapper-shipping-address-telephone">
                        <div class="w-full font-medium text-gray-700 required">
                        <label for="shipping-telephone" class="label label-telephone">
                        <span>
                        Phone Number </span>
                        </label>
                            <div class="flex items-center gap-4">
                                <input class="block w-full form-input grow renderer-text telephone" type="tel" data-form="shipping" value="{{ old('telephone') }}" id="shipping-telephone" name="telephone" data-attribute-address="true" data-magewire-is-valid="1" data-validate="&#x7B;&quot;magewire&quot;&#x3A;true&#x7D;" data-autosave wire:loading.class="loading" wire:loading.attr="disabled" wire:target="telephone" wire:model.defer="address.telephone" placeholder="Phone Number" />
                                <div class="flex gap-2 items-center tooltip" x-data="{ tooltipVisible: false }" role="tooltip">
                                    <div x-on:mouseenter.self="tooltipVisible = true" x-on:mouseleave.self="tooltipVisible = false" x-on:click.outside.away="tooltipVisible = false" class="relative hover:cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8 opacity-75 hover:opacity-100 mt-1 icon" width="24" height="24" role="img">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        <title>question-mark-circle</title>
                                        </svg>
                                        <template x-if="tooltipVisible">
                                        <div class="absolute -right-3 px-3 pt-3 w-80">
                                            <div class="shadow-lg px-3 py-2 bg-gray-500 relative text-white rounded-md">
                                                <div class="h-3 w-3 bg-gray-500 rotate-45 transform origin-bottom-left absolute right-3 -top-3"></div>
                                                For delivery questions. 
                                            </div>
                                        </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        @error('telephone')<div class="text-red-600">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-span-12 group group-street field-wrapper field-type-text field field-reserved field-street md&#x3A;col-span-12" wire:key="field-wrapper-shipping-address-street-0">
                        <div class="w-full font-medium text-gray-700 required">
                        <label for="shipping-street-0" class="label label-street">
                        <span>
                        Street Address </span>
                        </label>
                        <div class="space-y-2">
                            <div class="flex items-center gap-4">
                                <input class="block w-full form-input grow renderer-text street" type="text" data-form="shipping" value="{{ old('streetAddress') }}" name="streetAddress" placeholder="Street Address" />
                            </div>
                            @error('streetAddress')<div class="text-red-600">{{ $message }}</div>@enderror
                        </div>
                        </div>
                    </div>
                    <div class="col-span-12 field-wrapper field-type-text field field-reserved field-city md&#x3A;col-span-12" wire:key="field-wrapper-shipping-address-city">
                        <div class="w-full font-medium text-gray-700 required">
                        <label for="shipping-city" class="label label-city">
                        <span>
                        City </span>
                        </label>
                        <div class="flex items-center gap-4">
                            <input class="block w-full form-input grow renderer-text city" type="text" id="shipping-city" name="city" value="{{ old('city') }}" placeholder="City" />
                        </div>
                        @error('city')<div class="text-red-600">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-span-12 field-wrapper field-type-text field field-reserved field-postcode md&#x3A;col-span-12" wire:key="field-wrapper-shipping-address-postcode">
                        <div class="w-full font-medium text-gray-700">
                        <label for="shipping-postcode" class="label label-postcode">
                        <span>
                        Postcode </span>
                        </label>
                        <div class="flex items-center gap-4">
                            <input class="block w-full form-input grow renderer-text postcode" type="text" id="shipping-postcode" name="postcode" placeholder="Postcode" />
                        </div>
                        </div>
                    </div>
                    <div class="col-span-12 field-wrapper field-type-select field field-reserved field-country md&#x3A;col-span-12" wire:key="field-wrapper-shipping-address-country">
                        <div class="block font-medium text-gray-700 required">
                        <label for="shipping-country" class="label label-country">
                        <span>
                        Country </span>
                        </label>
                        <div class="flex items-center gap-4">
                        <select class="block w-full form-input renderer-select country" type="select" data-form="shipping" data-attribute="country" data-order="15" data-level="0" required autocomplete="country" id="shipping-country" name="country" data-attribute-address="true" data-magewire-is-valid="1" data-validate="&#x7B;&quot;magewire&quot;&#x3A;true&#x7D;" wire:loading.class="loading" wire:loading.attr="disabled">
                            <option disabled selected>--Select a country--</option>
                            <option value="AF">Afghanistan</option>
                            <option value="AL">Albania</option>
                            <option value="DZ">Algeria</option>
                            <option value="AS">American Samoa</option>
                            <option value="AD">Andorra</option>
                            <option value="AO">Angola</option>
                            <option value="AI">Anguilla</option>
                            <option value="AQ">Antarctica</option>
                            <option value="AG">Antigua and Barbuda</option>
                            <option value="AR">Argentina</option>
                            <option value="AM">Armenia</option>
                            <option value="AW">Aruba</option>
                            <option value="AU">Australia</option>
                            <option value="AT">Austria</option>
                            <option value="AZ">Azerbaijan</option>
                            <option value="BS">Bahamas</option>
                            <option value="BH">Bahrain</option>
                            <option value="BD">Bangladesh</option>
                            <option value="BB">Barbados</option>
                            <option value="BY">Belarus</option>
                            <option value="BE">Belgium</option>
                            <option value="BZ">Belize</option>
                            <option value="BJ">Benin</option>
                            <option value="BM">Bermuda</option>
                            <option value="BT">Bhutan</option>
                            <option value="BO">Bolivia</option>
                            <option value="BA">Bosnia and Herzegovina</option>
                            <option value="BW">Botswana</option>
                            <option value="BR">Brazil</option>
                            <option value="IO">British Indian Ocean Territory</option>
                            <option value="VG">British Virgin Islands</option>
                            <option value="BN">Brunei</option>
                            <option value="BG">Bulgaria</option>
                            <option value="BF">Burkina Faso</option>
                            <option value="BI">Burundi</option>
                            <option value="CV">Cabo Verde</option>
                            <option value="KH">Cambodia</option>
                            <option value="CM">Cameroon</option>
                            <option value="CA">Canada</option>
                            <option value="KY">Cayman Islands</option>
                            <option value="CF">Central African Republic</option>
                            <option value="TD">Chad</option>
                            <option value="CL">Chile</option>
                            <option value="CN">China</option>
                            <option value="CO">Colombia</option>
                            <option value="KM">Comoros</option>
                            <option value="CG">Congo (Brazzaville)</option>
                            <option value="CD">Congo (Kinshasa)</option>
                            <option value="CK">Cook Islands</option>
                            <option value="CR">Costa Rica</option>
                            <option value="HR">Croatia</option>
                            <option value="CU">Cuba</option>
                            <option value="CW">Curaçao</option>
                            <option value="CY">Cyprus</option>
                            <option value="CZ">Czech Republic</option>
                            <option value="DK">Denmark</option>
                            <option value="DJ">Djibouti</option>
                            <option value="DM">Dominica</option>
                            <option value="DO">Dominican Republic</option>
                            <option value="EC">Ecuador</option>
                            <option value="EG">Egypt</option>
                            <option value="SV">El Salvador</option>
                            <option value="GQ">Equatorial Guinea</option>
                            <option value="ER">Eritrea</option>
                            <option value="EE">Estonia</option>
                            <option value="SZ">Eswatini</option>
                            <option value="ET">Ethiopia</option>
                            <option value="FJ">Fiji</option>
                            <option value="FI">Finland</option>
                            <option value="FR">France</option>
                            <option value="GF">French Guiana</option>
                            <option value="PF">French Polynesia</option>
                            <option value="GA">Gabon</option>
                            <option value="GM">Gambia</option>
                            <option value="GE">Georgia</option>
                            <option value="DE">Germany</option>
                            <option value="GH">Ghana</option>
                            <option value="GI">Gibraltar</option>
                            <option value="GR">Greece</option>
                            <option value="GL">Greenland</option>
                            <option value="GD">Grenada</option>
                            <option value="GP">Guadeloupe</option>
                            <option value="GU">Guam</option>
                            <option value="GT">Guatemala</option>
                            <option value="GN">Guinea</option>
                            <option value="GW">Guinea-Bissau</option>
                            <option value="GY">Guyana</option>
                            <option value="HT">Haiti</option>
                            <option value="HN">Honduras</option>
                            <option value="HK">Hong Kong</option>
                            <option value="HU">Hungary</option>
                            <option value="IS">Iceland</option>
                            <option value="IN">India</option>
                            <option value="ID">Indonesia</option>
                            <option value="IR">Iran</option>
                            <option value="IQ">Iraq</option>
                            <option value="IE">Ireland</option>
                            <option value="IL">Israel</option>
                            <option value="IT">Italy</option>
                            <option value="JM">Jamaica</option>
                            <option value="JP">Japan</option>
                            <option value="JO">Jordan</option>
                            <option value="KZ">Kazakhstan</option>
                            <option value="KE">Kenya</option>
                            <option value="KI">Kiribati</option>
                            <option value="KR">Korea, South</option>
                            <option value="KW">Kuwait</option>
                            <option value="KG">Kyrgyzstan</option>
                            <option value="LA">Laos</option>
                            <option value="LV">Latvia</option>
                            <option value="LB">Lebanon</option>
                            <option value="LS">Lesotho</option>
                            <option value="LR">Liberia</option>
                            <option value="LY">Libya</option>
                            <option value="LI">Liechtenstein</option>
                            <option value="LT">Lithuania</option>
                            <option value="LU">Luxembourg</option>
                            <option value="MO">Macau</option>
                            <option value="MG">Madagascar</option>
                            <option value="MW">Malawi</option>
                            <option value="MY">Malaysia</option>
                            <option value="MV">Maldives</option>
                            <option value="ML">Mali</option>
                            <option value="MT">Malta</option>
                            <option value="MH">Marshall Islands</option>
                            <option value="MQ">Martinique</option>
                            <option value="MR">Mauritania</option>
                            <option value="MU">Mauritius</option>
                            <option value="MX">Mexico</option>
                            <option value="FM">Micronesia</option>
                            <option value="MD">Moldova</option>
                            <option value="MC">Monaco</option>
                            <option value="MN">Mongolia</option>
                            <option value="ME">Montenegro</option>
                            <option value="MS">Montserrat</option>
                            <option value="MA">Morocco</option>
                            <option value="MZ">Mozambique</option>
                            <option value="MM">Myanmar</option>
                            <option value="NA">Namibia</option>
                            <option value="NR">Nauru</option>
                            <option value="NP">Nepal</option>
                            <option value="NL">Netherlands</option>
                            <option value="NC">New Caledonia</option>
                            <option value="NZ">New Zealand</option>
                            <option value="NI">Nicaragua</option>
                            <option value="NE">Niger</option>
                            <option value="NG">Nigeria</option>
                            <option value="MK">North Macedonia</option>
                            <option value="NO">Norway</option>
                            <option value="OM">Oman</option>
                            <option value="PK">Pakistan</option>
                            <option value="PW">Palau</option>
                            <option value="PS">Palestine</option>
                            <option value="PA">Panama</option>
                            <option value="PG">Papua New Guinea</option>
                            <option value="PY">Paraguay</option>
                            <option value="PE">Peru</option>
                            <option value="PH">Philippines</option>
                            <option value="PL">Poland</option>
                            <option value="PT">Portugal</option>
                            <option value="PR">Puerto Rico</option>
                            <option value="QA">Qatar</option>
                            <option value="RE">Réunion</option>
                            <option value="RO">Romania</option>
                            <option value="RU">Russia</option>
                            <option value="RW">Rwanda</option>
                            <option value="KN">Saint Kitts and Nevis</option>
                            <option value="LC">Saint Lucia</option>
                            <option value="VC">Saint Vincent and the Grenadines</option>
                            <option value="WS">Samoa</option>
                            <option value="SM">San Marino</option>
                            <option value="ST">São Tomé and Príncipe</option>
                            <option value="SA">Saudi Arabia</option>
                            <option value="SN">Senegal</option>
                            <option value="RS">Serbia</option>
                            <option value="SC">Seychelles</option>
                            <option value="SL">Sierra Leone</option>
                            <option value="SG">Singapore</option>
                            <option value="SX">Sint Maarten</option>
                            <option value="SK">Slovakia</option>
                            <option value="SI">Slovenia</option>
                            <option value="SB">Solomon Islands</option>
                            <option value="SO">Somalia</option>
                            <option value="ZA">South Africa</option>
                            <option value="KR">South Korea</option>
                            <option value="SS">South Sudan</option>
                            <option value="ES">Spain</option>
                            <option value="LK">Sri Lanka</option>
                            <option value="SD">Sudan</option>
                            <option value="SR">Suriname</option>
                            <option value="SE">Sweden</option>
                            <option value="CH">Switzerland</option>
                            <option value="SY">Syria</option>
                            <option value="TW">Taiwan</option>
                            <option value="TJ">Tajikistan</option>
                            <option value="TZ">Tanzania</option>
                            <option value="TH">Thailand</option>
                            <option value="TL">Timor-Leste</option>
                            <option value="TG">Togo</option>
                            <option value="TK">Tokelau</option>
                            <option value="TO">Tonga</option>
                            <option value="TT">Trinidad and Tobago</option>
                            <option value="TN">Tunisia</option>
                            <option value="TR">Turkey</option>
                            <option value="TM">Turkmenistan</option>
                            <option value="TV">Tuvalu</option>
                            <option value="UG">Uganda</option>
                            <option value="UA">Ukraine</option>
                            <option value="AE">United Arab Emirates</option>
                            <option value="GB">United Kingdom</option>
                            <option value="US">United States</option>
                            <option value="UY">Uruguay</option>
                            <option value="UZ">Uzbekistan</option>
                            <option value="VU">Vanuatu</option>
                            <option value="VE">Venezuela</option>
                            <option value="VN">Vietnam</option>
                            <option value="WF">Wallis and Futuna</option>
                            <option value="YE">Yemen</option>
                            <option value="ZM">Zambia</option>
                            <option value="ZW">Zimbabwe</option>
                        </select>
                        </div>
                        @error('country')<div class="text-red-600">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>
                <div class="xs:mt-0 mt-4 xs:w-auto w-full">
                    <button type="submit" class="btn btn-primary checkout justify-center before:absolute before:bg-white before:duration-1000 before:ease before:h-14 before:opacity-10 before:right-0 before:rotate-6 before:top-0 before:translate-x-12 before:w-6 hover:before:-translate-x-[35rem] relative transition-all hover:bg-kes-green hover:border-kes-green hover:text-white overflow-hidden" id="checkout-link-button">
                        Submit
                    </button>
                </div>
            </form>
            @endif
        </div>
    </div>
</main>

@endsection
