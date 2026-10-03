@extends('admin.layout.layout')
@section('content')
<h2 class="content-heading">All Packages</h2>
@if (session('package_created'))
<div class="alert alert-success alert-dismissable" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
    <h3 class="alert-heading font-size-h4 font-w400">Success</h3>
    <p class="mb-0">{{ session('package_created') }}!</p>
</div>
@endif
@if (session('package_updated'))
<div class="alert alert-warning alert-dismissable" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
    <h3 class="alert-heading font-size-h4 font-w400">Success</h3>
    <p class="mb-0">{{ session('package_updated') }}!</p>
</div>
@endif
@if (session('package_deleted'))
<div class="alert alert-info alert-dismissable" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">×</span>
    </button>
    <h3 class="alert-heading font-size-h4 font-w400">Success</h3>
    <p class="mb-0">{{ session('package_deleted') }}!</p>
</div>
@endif
<div class="block">
    <div class="block-header block-header-default">
        <h3 class="block-title">All Packages <small>(Detail)</small></h3>
    </div>
    <div class="block-content block-content-full">
       <table class="table table-bordered table-striped table-vcenter js-dataTable-full">
            <thead>
                <tr>
                    <th class="text-center">#</th>
                    <th class="d-none d-sm-table-cell">Package</th>
                    <th class="d-none d-sm-table-cell">Sub-Category</th>
                    <th class="d-none d-sm-table-cell">Type</th>
                    <th class="d-none d-sm-table-cell" style="width: 15%;">No. of ads</th>
                    <th class="d-none d-sm-table-cell" style="width: 15%;">Days</th>
                    <th class="d-none d-sm-table-cell" style="width: 15%;">Price</th>
                    <th class="text-center" style="width: 15%;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; ?>@foreach($packages  as $package)
                <tr>
                    <td class="text-center">{{ $no++ }}</td>
                    <td>{{ ucwords($package->package) }}</td>
                    <td class="font-w600">{{ ucwords($package->subcategory->subcat_title) }}</td>
                    <td class="d-none d-sm-table-cell">{{ ucwords(str_replace('_',' ',$package->type)) }}</td>
                    <td class="d-none d-sm-table-cell">{{ $package->no_of_ads }}</td>
                    <td class="d-none d-sm-table-cell">{{ $package->days }}</td>
                    <td class="d-none d-sm-table-cell">{{ $package->price }}</td>
                    <td class="text-center">
                    <a href="{{ route('edit.package',$package->id) }}"><button type="button" class="btn btn-sm btn-circle btn-alt-info mr-5 mb-5">
                            <i class="fa fa-pencil"></i>
                        </button></a>
                        <a href="{{ route('delete.package',$package->id) }}"><button type="button" class="btn btn-sm btn-circle btn-alt-danger mr-5 mb-5">
                            <i class="fa fa-times"></i>
                        </button></a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
@section('scripts')
    <script></script>
@endsection
