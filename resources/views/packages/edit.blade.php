@extends('admin.layout.layout')
@section('content')
<h2 class="content-heading">Create Package</h2>
<div class="row">
    <div class="col-md-12">
        <div class="block">
            <div class="block-header block-header-default">
                <h3 class="block-title">Details</h3>
            </div>
            <div class="block-content">
                <form action="{{ route('update.package') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <div class="col-lg-6" data-select2-id="9">
                            <div class="form-material">
                                <select class="js-select2 form-control" name="cat_id" id="cat_id"  data-placeholder="Choose Category.." required>
                                    <option></option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $category->id == $package->cat_id ? 'selected' : '' }}>{{ $category->cat_title }}</option>
                                    @endforeach
                                </select>
                                <label for="">Select Category</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-material">
                                <select class="js-select2 form-control" id="subcat_id" name="subcat_id" data-placeholder="Choose Sub-Category.." required>
                                    @foreach($subcategories as $subcategory)
                                    <option value="{{ $subcategory->subcat_id }}" {{ $subcategory->subcat_id == $package->subcat_id ? 'selected' : '' }}>{{ $subcategory->subcat_title }}</option>
                                 @endforeach
                                </select>
                                <label for="">Select Sub-Category</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6" data-select2-id="9">
                            <div class="form-material floating">
                                <input type="text" class="form-control" id="package" name="package" value="{{ $package->package }}" required>
                                <label for="package">Package Title</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-material">
                                <select class="js-select2 form-control" id="type" name="type" data-placeholder="Choose Package Type.."  required>
                                    <option></option>
                                    <option value="boost" {{ $package->type == 'boost' ? 'selected' : '' }}>Ads Boost</option>
                                    <option value="more_ads" {{ $package->type == 'more_ads' ? 'selected' : '' }}>More Ads</option>
                                    <option value="featured" {{ $package->type == 'featured' ? 'selected' : '' }}>Featured Ads</option>
                                </select>
                                <label for="">Select Package Type</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-4">
                            <div class="form-material floating">
                                <input type="number" class="form-control" id="no_of_ads" name="no_of_ads" value="{{ $package->no_of_ads }}" required>
                                <label for="no_of_ads">No. of Ads</label>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-material floating">
                                <input type="number" class="form-control" id="days" name="days" value="{{ $package->days }}" required>
                                <label for="days">Days</label>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-material floating">
                                <input type="number" class="form-control" id="price" name="price" value="{{ $package->price }}" required>
                                <label for="price">Price</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-md-9">
                            <a href="{{ route('all.packages') }}">
                                <button type="button" class="btn btn-alt-danger">Cancel</button>
                            </a>
                            <input type="hidden" name="id" value="{{ $package->id }}">
                            <button type="submit" class="btn btn-alt-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
$(document).on('change','#cat_id',function(){
    let cat_id = $(this).val();
    $.ajax({
        url:'{{ route("get.subcategories") }}',
        method:'post',
        dataType:'json',
        data:{
            cat_id:cat_id,
            _token: '{{csrf_token()}}'
        },
        success:function(response){
            $('#subcat_id').html(response);
        }
    });
});
</script>
@endsection
