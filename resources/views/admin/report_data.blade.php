<?php $no = 1; ?>@foreach($posts  as $post)
<tr>
    <td class="text-center">{{ $no++ }}</td>
    <td class="font-w600">{{ $post->user->name }}</td>
    <td class="font-w600">{{ $post->post_title }}</td>
    <td class="font-w600">{{ $post->category->cat_title }}</td>
    <td class="font-w600">{{ $post->subcategory->subcat_title }}</td>
    <td class="font-w600">{{ $post->city->city_title }}</td>
    <td class="font-w600">{{ $post->post_price }}</td>
    <td class="font-w600">{{ $post->state }}</td>
    <td class="d-none d-sm-table-cell text-center">
    <div class="btn-group">
        @if($post->is_featured)
        <button type="button" class="js-swal-confirm btn btn-lg btn-circle btn-alt-danger mr-5 mb-5 remove_featured_posts" title="Remove From Featured Ads" id="" data-post_id="{{ $post->post_id }} ">
            <i class="fa fa-close"></i>
        </button>
        @else
        <button type="button" class="js-swal-confirm btn btn-lg btn-circle btn-alt-success mr-5 mb-5 add_to_featured_posts" title="Add To Featured Ads" id="" data-post_id="{{ $post->post_id }}">
            <i class="fa fa-check-square-o"></i>
        </button>
        @endif
    </div>
    <span class="css-control-indicator"></span> <span ><?php if($post->is_featured){echo '<span class="badge badge-success">Featured</span>';}else{echo '<span class="badge badge-info">Not Featured</span>';} ?></span>
    </td>
    <td class="d-none d-sm-table-cell text-center">
        <div class="btn-group">
            @if($post->is_active)
            <button type="button" class="js-swal-confirm btn btn-lg btn-circle btn-alt-danger mr-5 mb-5 mark_unactive" title="Mark as Active Ad" id="" data-post_id="{{ $post->post_id }} ">
                <i class="fa fa-close"></i>
            </button>
            @else
            <button type="button" class="js-swal-confirm btn btn-lg btn-circle btn-alt-success mr-5 mb-5 mark_active" title="Block Ad" id="" data-post_id="{{ $post->post_id }}">
                <i class="fa fa-check-square-o"></i>
            </button>
            @endif
        </div>
        <span class="css-control-indicator"></span> <span ><?php if($post->is_active){echo '<span class="badge badge-success">Active</span>';}else{echo '<span class="badge badge-info">Pending</span>';} ?></span>
        </td>
    <td class="text-center">
        <a href="{{ url('edit-post/'.$post->post_slug) }}"><button type="button" title="Edit" class="btn btn-sm btn-circle btn-alt-success mr-5 mb-5">
            <i class="fa fa-pencil"></i>
        </button>
    </a>
        <button type="button" id="delete{{ $post->post_id }}" data-id = "{{ $post->post_id }}" title="Delete" class="btn btn-sm btn-circle btn-alt-danger mr-5 mb-5">
            <i class="fa fa-times"></i>
        </button>
    </td>
</tr>
@endforeach
