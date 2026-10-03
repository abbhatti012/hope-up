@extends('public.layout.layout')
@section('content')
<script src="//unpkg.com/alpinejs" defer></script>
<style>
    .form-edit-account .form-input {
        width: 100%;
    }
</style>
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
            <form class="form form-edit-account card" action="{{ route('account.update') }}" method="post" id="form-validate" enctype="multipart/form-data" autocomplete="off" novalidate="">
                @csrf
                @method('PUT')
                
                <div class="field field-reserved">
                    <label class="label" for="name">
                        <span>Name</span>
                    </label>
                    <div class="control">
                        <input type="text" name="name" id="name" required value="{{ old('name', $user->name) }}" title="Name" class="form-input">
                    </div>
                </div>

                <div class="field choice">
                    <input type="checkbox" name="change_email" id="change-email" value="1" title="Change Email" @change="toggleEmailFields" class="checkbox">
                    <label class="label" for="change-email">
                        <span>Change Email</span>
                    </label>
                </div>
                
                <template x-if="showEmailField">
                    <div class="field field-reserved email required !mt-4">
                        <label class="label" for="email">
                            <span>Email</span>
                        </label>
                        <div class="control">
                            <input type="email" name="email" id="email" required value="{{ old('email', $user->email) }}" title="Email" class="form-input">
                        </div>
                    </div>
                </template>

                <div class="field choice">
                    <input type="checkbox" name="change_password" id="change-password" value="1" title="Change Password" @change="togglePasswordFields" class="checkbox">
                    <label class="label" for="change-password">
                        <span>Change Password</span>
                    </label>
                </div>

                <template x-if="showPasswordFields">
                    <div>
                    <div class="field field-reserved password current required">
                    <label class="label" for="current-password">
                        <span>Current Password</span>
                    </label>
                    <div class="control flex items-center">
                        <input :type="showPasswordCurrent ? 'text' : 'password'" name="current_password" id="current-password" required class="form-input" autocomplete="off">
                        <button type="button" @click="showPasswordCurrent = !showPasswordCurrent" class="pl-4 self-stretch" :aria-label="showPasswordCurrent ? 'Hide Password' : 'Show Password'">
                            <template x-if="!showPasswordCurrent">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                </svg>
                            </template>
                            <template x-if="showPasswordCurrent">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                    <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd"></path>
                                    <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z"></path>
                                </svg>
                            </template>
                        </button>
                    </div>
                </div>

                <div class="field field-reserved password required">
                    <label class="label" for="new-password">
                        <span>New Password</span>
                    </label>
                    <div class="control flex items-center">
                        <input :type="showPasswordNew ? 'text' : 'password'" name="new_password" id="new-password" required minlength="8" class="form-input" autocomplete="off">
                        <button type="button" @click="showPasswordNew = !showPasswordNew" class="pl-4 self-stretch" :aria-label="showPasswordNew ? 'Hide Password' : 'Show Password'">
                            <template x-if="!showPasswordNew">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                </svg>
                            </template>
                            <template x-if="showPasswordNew">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                    <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd"></path>
                                    <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z"></path>
                                </svg>
                            </template>
                        </button>
                    </div>
                </div>

                <div class="field field-reserved required">
                    <label class="label" for="password-confirmation">
                        <span>Confirm New Password</span>
                    </label>
                    <div class="control flex items-center">
                        <input :type="showPasswordConfirm ? 'text' : 'password'" name="password_confirmation" id="password-confirmation" required class="form-input" autocomplete="off">
                        <button type="button" @click="showPasswordConfirm = !showPasswordConfirm" class="pl-4 self-stretch" :aria-label="showPasswordConfirm ? 'Hide Password' : 'Show Password'">
                            <template x-if="!showPasswordConfirm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                </svg>
                            </template>
                            <template x-if="showPasswordConfirm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                    <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd"></path>
                                    <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z"></path>
                                </svg>
                            </template>
                        </button>
                    </div>
                </div>
                    </div>
                </template>

                <div class="actions-toolbar">
                    <div class="primary">
                        <button type="submit" class="action save primary" title="Save">
                            <span>Save</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

@endsection
@section('scripts')
<script>
    function accountForm() {
        return {
            showEmailField: false,
            showPasswordFields: false,
            showPasswordCurrent: false,
            showPasswordNew: false,
            showPasswordConfirm: false,

            toggleEmailFields() {
                this.showEmailField = !this.showEmailField;
            },
            togglePasswordFields() {
                this.showPasswordFields = !this.showPasswordFields;
            },
        }
    }
</script>
@endsection