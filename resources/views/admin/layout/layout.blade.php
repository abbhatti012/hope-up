<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hope Up - Trusted Medical & Healthcare Services</title>

    <meta name="description" content="Hope Up is a trusted online platform connecting patients with qualified doctors and offering reliable medical services, appointments, and health management tools.">
    <meta name="keywords" content="Hope Up, doctor website, medical services, healthcare, online appointments, health platform, telemedicine">
    <meta name="author" content="Hope Up Team">
    <meta property="og:title" content="Hope Up - Your Trusted Medical Service">
    <meta property="og:description" content="Book appointments, consult doctors, and manage your health with Hope Up's reliable healthcare platform.">
    <meta property="og:type" content="website">
    <meta property="og:url" content="http://doctor.luminantghana.com/">
    <meta property="og:image" content="{{asset('assets/images/logo1.svg')}}">

    <link rel="shortcut icon" href="{{asset('assets/images/favicon.svg')}}">

    <link rel="stylesheet" href="{{asset('assets/fonts/remix/remixicon.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/main.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/vendor/overlay-scroll/OverlayScrollbars.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">

    <meta name="token" content="{{ csrf_token() }}">

    @yield('links')
    @yield('css')
</head>
<body>
    <!-- <div id="loading-wrapper">
      <div class='spin-wrapper'>
        <div class='spin'>
          <div class='inner'></div>
        </div>
        <div class='spin'>
          <div class='inner'></div>
        </div>
        <div class='spin'>
          <div class='inner'></div>
        </div>
        <div class='spin'>
          <div class='inner'></div>
        </div>
        <div class='spin'>
          <div class='inner'></div>
        </div>
        <div class='spin'>
          <div class='inner'></div>
        </div>
      </div>
    </div> -->

    <div class="page-wrapper">

      @include('admin.layout.header')

      <div class="main-container">
        @include('admin/layout/sidebar')

        <div class="app-container">
            @yield('content')
            <div class="app-footer bg-white">
                <span>© Hope Up admin 2025</span>
            </div>
        </div>
      </div>

    </div>

    <script src="{{asset('assets/js/jquery.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('assets/js/moment.min.js')}}"></script>
    <script src="{{asset('assets/vendor/overlay-scroll/jquery.overlayScrollbars.min.js')}}"></script>
    <script src="{{asset('assets/vendor/overlay-scroll/custom-scrollbar.js')}}"></script>
    <script src="{{asset('assets/js/custom.js')}}"></script>
    <script src="{{asset('assets/js/admin-search.js')}}"></script>

    @yield('scripts')

    <script>
        let timeout;
        const searchInput = document.getElementById('searchInput');

        searchInput.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(function() {
                searchInput.form.submit();
            }, 700);
        });
    </script>
    
    <!-- Toast Container -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div class="toast-container">
            <!-- Toasts will be inserted here by JavaScript -->
        </div>
    </div>
</body>    
</html>
