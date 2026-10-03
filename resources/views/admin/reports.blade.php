@extends('admin.layout.layout')
@section('content')
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">Filter Ads</h3>
    </div>
    <div class="block-content block-content-full">
        <div class="form-group row">
            <div class="col-lg-4">
                <div class="form-material">
                    <select class="js-select2 form-control" id="filterByCategory" name="category" style="width: 100%;" data-placeholder="Choose Category..">
                        <option></option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->cat_title }}</option>
                        @endforeach
                    </select>
                    <label for="">Filter By Category</label>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-material">
                    <select class="js-select2 form-control" id="filterByCity" name="category" style="width: 100%;" data-placeholder="Choose City..">
                        <option></option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}">{{ $city->city_title }}</option>
                        @endforeach
                    </select>
                    <label for="">Filter By Cities</label>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-material">
                    <select class="js-select2 form-control" id="filterByState" name="category" style="width: 100%;" data-placeholder="Choose State..">
                        <option></option>
                        @foreach($states as $state)
                            <option value="{{ $state->state }}">{{ $state->state }}</option>
                        @endforeach
                    </select>
                    <label for="">Filter By States</label>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-material">
                    <select class="js-select2 form-control" id="filterByUser" name="category" style="width: 100%;" data-placeholder="Choose User..">
                        <option></option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    <label for="">Filter By User</label>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-material">
                    <select class="js-select2 form-control" id="filterBy" name="category" style="width: 100%;" data-placeholder="Choose One..">
                        <option></option>
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="30">30</option>
                            <option value="40">40</option>
                            <option value="50">50</option>
                    </select>
                    <label for="">Filter By Pagination</label>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="form-material">
                    <select class="js-select2 form-control" id="filterByapproved" name="category" style="width: 100%;" data-placeholder="Choose One..">
                        <option></option>
                            <option value="1">Approved</option>
                            <option value="0">Non Approved</option>
                    </select>
                    <label for="">Filter By Approved/Non Approved</label>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="">Filter By Date</label>
            <div class="row col-lg-12">
                <div class="col-lg-5">
                    <div class="form-material">
                    <input type="text" class="js-flatpickr form-control bg-white" id="min_date" name="example-flatpickr-default" placeholder="From Date">
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="form-material">
                    <input type="text" class="js-flatpickr form-control bg-white" id="max_date" name="example-flatpickr-default" placeholder="To Date">
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-material">
                        <input type="button" id="get_date" value="submit" class="btn btn-primary">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="">Filter By Price Range</label>
            <div class="row col-lg-12">
                <div class="col-md-5">
                    <div class="form-material">
                        <input type="number" class="form-control" id="min_price" name="min_price">
                        <label for="min_price">Mminimum Price</label>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-material">
                        <input type="number" class="form-control" id="max_price" name="max_price">
                        <label for="max_price">Maximum Price</label>
                    </div>
                </div>
                <div class="col-lg-2">
                    <div class="form-material">
                        <input type="button" id="get_price" value="submit" class="btn btn-primary">
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">All Ads <small>(Details)</small> </h3>
    </div>
    <div class="block-content block-content-full">
       <table class="table table-bordered table-striped table-vcenter">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="d-none d-sm-table-cell">Ad By</th>
                    <th class="d-none d-sm-table-cell">Title</th>
                    <th class="d-none d-sm-table-cell">Category</th>
                    <th class="d-none d-sm-table-cell">Sub Category</th>
                    <th class="d-none d-sm-table-cell">City</th>
                    <th class="d-none d-sm-table-cell">Price</th>
                    <th class="d-none d-sm-table-cell">State</th>
                    <th class="d-none d-sm-table-cell" style="width: 15%;">Is Featured?</th>
                    <th class="d-none d-sm-table-cell" style="width: 15%;">Approve</th>
                    <th class="text-center" style="width: 15%;">Action</th>
                </tr>
            </thead>
            <tbody id="save_report_data">

            </tbody>
            <div class="row col-md-12">
                <div class="row-lg-3">
                    <div class="save_pagination_links"></div>
                </div>
                <div style="position: relative;left:400px" class="ml-300 row-lg-3">
                    <input type="text" class="form-control" id="search_result">
                </div>
            </div>
        </table>
        <div class="save_pagination_links"></div>
    </div>
</div>
<div id="save_cat"></div>
<div id="save_city"></div>
<div id="save_state"></div>
<div id="save_user"></div>
<div id="save_page"></div>
<div id="save_approved"></div>
@endsection

@section('scripts')
    <script>
        show_data(1);
        function show_data(page)
        {
            var cat = $('#save_cat').val();
            var city = $('#save_city').val();
            var state = $('#save_state').val();
            var user = $('#save_user').val();
            var min = $('#min_date').val();
            var max = $('#max_date').val();
            var min_price = $('#min_price').val();
            var max_price = $('#max_price').val();
            var pages = $('#save_page').val();
            var approved = $('#save_approved').val();
            var search = $('#search_result').val();
            $.ajax({
                type : 'get',
                url : "{{ url('get-report-data') }}?page="+page,
                data : {
                    cat : cat,
                    city : city,
                    state : state,
                    user : user,
                    min : min,
                    max : max,
                    pages : pages,
                    min_price : min_price,
                    max_price : max_price,
                    approved : approved,
                    search : search
                },
                success : function(data){
                    if(data){
                        $('#save_report_data').html(data.posts);
                        $('.save_pagination_links').html(data.links);
                    }
                }
            })
        }
        $(document).on('click', '.pagination a', function(event){
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            show_data(page);
        });
        $('#filterByCategory').on('change',function(){
            var cat = $(this).val();
            $('#save_cat').val(cat);
            show_data(1);
        });
        $('#filterByCity').on('change',function(){
            var city = $(this).val();
            $('#save_city').val(city);
            show_data(1);
        });
        $('#filterByState').on('change',function(){
            var state = $(this).val();
            $('#save_state').val(state);
            show_data(1);
        });
        $('#filterByUser').on('change',function(){
            var user = $(this).val();
            $('#save_user').val(user);
            show_data(1);
        });
        $('#filterBy').on('change',function(){
            var value = $(this).val();
            var page = $('#save_page').val(value);
            show_data(page);
        });
        $('#filterByapproved').on('change',function(){
            var value = $(this).val();
            $('#save_approved').val(value);
            show_data(1);
        });
        $('#get_date').on('click',function(){
            var min = $('#min_date').val();
            var max = $('#max_date').val();
            if(min == '' || max == ''){
                $.notify('Please Select both date first');
            } else {
                show_data(1);
            }
        });
        $('#get_price').on('click',function(){
            var min_price = $('#min_price').val();
            var max_price = $('#max_price').val();
            if(min_price == '' || max_price == ''){
                $.notify('Please Enter the price first');
            } else {
                show_data(1);
            }
        });
        $('#search_result').on('keyup',function(){
            var text = $(this).val();
            show_data(1);
        });
    </script>
@endsection
