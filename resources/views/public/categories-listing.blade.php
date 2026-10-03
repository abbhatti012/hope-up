@forelse($subCategories as $sub)
    <li>
        <a href="?filter={{ $sub->subcat_slug }}" title="{{ $sub->subcat_title }}" class="product photo product-item-photo block mx-auto"
           tabindex="-1">
            <img style="width: 100%; height: auto; max-height: 300px; object-fit: cover;" class="product-image-photo" src="{{ asset($sub->subcat_image) }}" width="360" height="360" title="{{ $sub->subcat_title }}" />
        </a>
        <div class="product-info flex flex-col grow">
            <div class="xl:pt-6 md:pt-4 pt-3 ">
                <a class="product-item-link leading-7 text-black" href="?filter={{ $sub->subcat_slug }}">{{ $sub->subcat_title }}</a>
            </div>
        </div>
    </li>
@empty
    @include('public.no-item', ['title' => 'No items found with relevant filter criteria'])
@endforelse
