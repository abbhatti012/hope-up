@forelse($products as $product)
<li>
    <form method="post" action="#" class="item product product-item product_addtocart_form flex flex-col w-full relative h-full ">
        <a href="{{ route('product', $product->slug) }}" title="{{ $product->title }}" class="product photo product-item-photo block mx-auto" tabindex="-1">
            <!-- <img style="height: auto; max-height: 300px; object-fit: fill; border-radius: 10px;" class="product-image-photo" src="{{ asset($product->image) }}" width="360" height="360" alt="{{ $product->title }}" title="{{ $product->title }}"/> -->
            <img class="product-image-photo responsive-image" src="{{ asset($product->image) }}" width="360" height="360" alt="{{ $product->title }}" title="{{ $product->title }}"/>
        </a>
        <div class="product-info flex flex-col grow">
            <div class="xl:pt-6 md:pt-4 pt-3 ">
                <a class="product-item-link leading-7 text-black" href="{{ route('product', $product->slug) }}">{{ Str::limit($product->title, 35, '...') }} </a>
            </div>
            <div class="pt-1 content-end flex flex-auto flex-wrap" x-data="initPriceBox__6728c7682613e()" @update-prices-23196.window="updatePrice($event.detail);" x-defer="interact">
                <div class="price-box price-final_price" data-role="priceBox" data-product-id="23196" data-price-box="product-id-23196">
                    <span class="old-price mr-2">
                    <span x-data="" class="price-container price-final_price tax weee">
                    <span class="price-label"></span>
                    <span class="price-wrapper " id="old-price-23196-1"><span class="price">₵{{ $product->old_price }}</span></span>
                    </span>
                    </span>
                    <span class="special-price">
                    <span class="price-container price-final_price tax weee" x-defer="interact">
                    <span class="price-label"></span>
                    <span class="price-wrapper " id="product-price-23196-1"><span class="price">₵{{ $product->price }}</span></span>
                    </span>
                    </span>
                </div>
            </div>
        </div>
    </form>
</li>
@empty
    @include('public.no-item', ['title' => 'No items found with relevant filter criteria'])
@endforelse

<style>
    .responsive-image {
        /* height: auto; */
        object-fit: fill;
        border-radius: 10px;
        height: 400px;
    }

    @media (max-width: 1198px) {
        .responsive-image {
            height: auto;
            max-height: 250px;
        }
    }

    @media (max-width: 768px) {
        .responsive-image {
            height: auto;
            max-height: 250px;
        }
    }

    @media (max-width: 576px) {
        .responsive-image {
            height: auto;
            max-height: 200px;
        }
    }

    @media (max-width: 400px) {
        .responsive-image {
            height: auto;
            max-height: 150px;
        }
    }

</style>