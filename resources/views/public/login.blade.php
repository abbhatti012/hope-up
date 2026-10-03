@extends('public.layout.layout')
@section('content')
    @include('public.info')
    <main id="maincontent" class="page-main">
        <div class="container flex flex-col md:flex-row flex-wrap font-semibold mb-5 md:mb-7 mt-7 sm:mt-8 lg:mt-10">
            <h1 class="page-title title-font mb-0">
                <span class="base" data-ui-id="page-title-wrapper">Customer Login</span> 
            </h1>
        </div>
        <div class="columns">
            <div class="column main">
                <div id="customer-login-container" class="login-container">
                    <div class="card">
                    <div aria-labelledby="block-customer-login-heading">
                        <form class="form form-login" action="./" method="post" x-data="initCustomerLoginForm()" @submit.prevent="submitForm()" id="customer-login-form">
                            <fieldset class="fieldset login">
                                <legend class="mb-3">
                                <h2 class="text-xl font-semibold title-font text-primary">
                                    Login 
                                </h2>
                                </legend>
                                <div class="text-secondary-darker mb-6">
                                If you have an account, sign in with your email address. 
                                </div>
                                <div class="field email required mb-4">
                                <label class="label" for="email">
                                <span>Email</span>
                                </label>
                                <div class="control">
                                    <input name="login[username]" class="form-input" required="" value="" autocomplete="off" id="email" type="email" title="Email">
                                </div>
                                </div>
                                <div class="field password required">
                                <label for="pass" class="label">
                                <span>Password</span>
                                </label>
                                <div class="control flex items-center">
                                    <input name="login[password]" class="form-input" required="" :type="showPassword ? 'text' : 'password'" autocomplete="off" id="pass" title="Password" type="password">
                                    <button type="button" x-on:click="showPassword = !showPassword" :aria-pressed="showPassword ? 'true' : 'false'" class="pl-4 py-3" :aria-label="showPassword ? 'Hide\u0020Password' : 'Show\u0020Password'" aria-pressed="false" aria-label="Show Password">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5" width="24" height="24" role="img">
                                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                                            <title>eye</title>
                                        </svg>
                                    </button>
                                </div>
                                </div>
                                <div class="actions-toolbar flex justify-between pt-6 pb-2 items-center">
                                <button type="submit" class="btn btn-primary disabled:opacity-75" name="send">
                                <span>Sign In</span></button>
                                <a class="underline hover:text-bronze-10" href="./forgot-password.php"><span>Forgot Your Password?</span>
                                </a>
                                </div>
                                <div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    </div>
                    <div class="block-new-customer card">
                    <div class="block-title">
                        <h2 class="text-xl font-semibold title-font mb-3 text-primary" role="heading" aria-level="2">New Customers</h2>
                    </div>
                    <div class="block-content" aria-labelledby="block-new-customer-heading">
                        <p>
                            Creating an account has many benefits: check out faster, keep more than one address, track orders and more. 
                        </p>
                    </div>
                    <div class="actions-toolbar pt-6 pb-2 flex self-end">
                        <a href="./register.php" class="btn btn-primary"><span>Create an Account</span></a>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection