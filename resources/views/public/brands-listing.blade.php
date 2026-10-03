@forelse($brands as $brand)
    <li>
        <form method="post" action="{{ route('brands', ['filter' => $brand->brand_slug]) }}" class="item product product-item product_addtocart_form flex flex-col w-full relative h-full ">
            <a href="{{ route('brands', ['filter' => $brand->brand_slug]) }}" title="{{ $brand->brand_title }}" class="product photo product-item-photo block mx-auto  "
            tabindex="-1">
                <img class="product-image-photo" src="{{ asset($brand->brand_image) }}" width="360" height="360" title="{{ $brand->brand_title }}"
                />
            </a>
            <div class="product-info flex flex-col grow">
                <div class="xl:pt-6 md:pt-4 pt-3 ">
                    <a class="product-item-link leading-7 text-black" href="{{ route('brands', ['filter' => $brand->brand_slug]) }}">{{ $brand->brand_title }}</a>
                </div>
            </div>
        </form>
    </li>
@empty
    @include('public.no-item', ['title' => 'No items found with relevant filter criteria'])
@endforelse