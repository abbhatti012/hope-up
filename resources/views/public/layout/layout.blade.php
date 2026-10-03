<!doctype html>
<html lang="en">
   <head>
      <meta charset="utf-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1" />
      <title>Luminent Electricalsting</title>
      <link rel="stylesheet" type="text/css" media="all" href="{{ asset('public_assets/style.css') }}" />
      <link rel="stylesheet" type="text/css" media="all" href="{{ asset('public_assets/slider.css') }}" />
      <link rel="stylesheet" type="text/css" media="all" href="{{ asset('public_assets/custom-style.css') }}" />
      <script type="text/javascript" src="{{ asset('public_assets/jquery.min.js') }}" id="jquery-core-js"></script>
      <link rel="stylesheet" type="text/css" media="all" href="{{ asset('public_assets/header.css') }}" />
      <link rel="stylesheet" type="text/css" media="all" href="{{ asset('public_assets/slider.css') }}" />
    
      <link rel="icon" type="image/x-icon" href="{{ asset('public_assets/media/favicon.png') }}" />
      <link rel="shortcut icon" type="image/x-icon" href="{{ asset('public_assets/media/favicon.png') }}" />
      <script type="text/javascript" src="{{ asset('public_assets/bootstrap.min.js') }}" async></script>

      <script src="{{ asset('public_assets/alpine.min.js') }}" defer></script>
      <script rel="stylesheet" src="{{ asset('public_assets/slider.js') }}"></script>
   </head>
  <style>
    .autocomplete-suggestions {
        background-color: white;
        border: 1px solid #ddd;
        border-radius: 5px; /* Rounded corners */
        max-height: 300px; /* Limit the height */
        overflow-y: auto; /* Enable scrolling */
        z-index: 9999; /* Positioning above other elements */
    }

    .suggestion {
        padding: 10px 15px; /* Padding around each suggestion */
        cursor: pointer; /* Pointer cursor for clickable suggestions */
        transition: background-color 0.3s; /* Transition for hover effect */
    }

    .suggestion:hover {
        background-color: #f0f0f0; /* Change background on hover */
    }

    .suggestion + .suggestion {
        border-top: 1px solid #ddd; /* Separator line between suggestions */
    }

  </style>
    @if (request()->is('/'))
      <body id="html-body" class="cms-home mst-nav__theme-hyva-reset mst-nav__theme-hyva-default cms-index-index page-layout-1column">
    @elseif(request()->is('product/*') || request()->is('product-detail/*'))
      <body id="html-body" class="catalog-product-view product-caspen-sobrado-6-light-pendant-ceiling-fitting-matt-antique-brass-plate-opal-glass-cas-605049 categorypath-shop-by-room-living-room-lights-pendants category-pendants mst-nav__theme-hyva-reset mst-nav__theme-hyva-default page-layout-1column">
    @elseif(request()->is('cart-items*'))
      <!-- <body id="html-body" class="mst-nav__theme-hyva-reset mst-nav__theme-hyva-default mst-nav__theme-keslighting-upgrade checkout-cart-index page-layout-1column"> -->
      <body class="checkout-default checkout-kes mst-nav__theme-hyva-reset mst-nav__theme-hyva-default mst-nav__theme-keslighting-upgrade hyva_checkout-index-index page-layout-1column" id="html-body">
    @elseif(request()->is('checkout*'))
      <body class="checkout-default checkout-kes mst-nav__theme-hyva-reset mst-nav__theme-hyva-default mst-nav__theme-keslighting-upgrade hyva_checkout-index-index page-layout-1column" id="html-body">
    @else
      <body id="html-body" class="page-with-filter page-products categorypath-outdoor-lighting-ceiling-modern category-modern mst-nav__theme-hyva-reset mst-nav__theme-hyva-default catalog-category-view page-layout-2columns-left">
    @endif

      <div class="page-wrapper">
        @include('public.layout.header')

        @yield('content')
        
        @include('public.layout.footer')
        </div>
   </body>
</html>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const searchField = document.getElementById('product-search');
    const suggestionsBox = document.getElementById('suggestions');

    searchField.addEventListener('input', async () => {
        const query = searchField.value;

        // Clear previous suggestions
        suggestionsBox.innerHTML = '';

        if (query.length < 3) {
            suggestionsBox.style.display = 'none'; // Hide if input is too short
            return;
        }

        // Fetch suggestions from the server
        const response = await fetch(`/api/products/search?q=${query}`); // Adjust this URL as per your routing
        const products = await response.json();

        if (products.length > 0) {
            products.forEach(product => {
                const suggestion = document.createElement('div');
                suggestion.className = 'suggestion';
                suggestion.innerText = product.title; // Adjust based on your product object structure
                suggestion.dataset.slug = product.slug; // Save the slug for redirection
                suggestion.style.cursor = 'pointer';
                suggestion.style.color = 'black';

                // Click event for suggestion
                suggestion.addEventListener('click', () => {
                    window.location.href = `/product-detail/${product.slug}`; // Adjust route as needed
                });

                suggestionsBox.appendChild(suggestion);
            });

            suggestionsBox.style.display = 'block'; // Show suggestions
        } else {
            suggestionsBox.style.display = 'none'; // Hide if no products found
        }
    });

    // Hide suggestions when clicking outside
    document.addEventListener('click', (event) => {
        if (!suggestionsBox.contains(event.target) && event.target !== searchField) {
            suggestionsBox.style.display = 'none';
        }
    });
});

document.getElementById('subscribe-button').addEventListener('click', function() {
    const email = document.getElementById('newsletter-subscribe').value;
    const messageDiv = document.getElementById('message');
    
    if (!email) {
        messageDiv.textContent = 'Please enter a valid email address.';
        return;
    }

    fetch('/insert-subscribers', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        body: JSON.stringify({ email })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            messageDiv.textContent = 'Thank you for subscribing!';
            messageDiv.classList.remove('text-red-500');
            messageDiv.classList.add('text-green-500');
          } else {
            messageDiv.textContent = data.message;
            messageDiv.classList.remove('text-green-500');
            messageDiv.classList.add('text-red-500');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        messageDiv.textContent = 'Something went wrong. Please try again.';
    });
});

</script>
@yield('scripts')
