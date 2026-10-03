@extends('admin.layout.layout')
@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @include('admin/notifications')
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">AdminCommission Settings</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.web-settings.update') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="admin_commission" class="form-label">Admin Commission (%)</label>
                            <input type="number" step="0.01" min="0" max="100" class="form-control" id="admin_commission" name="admin_commission" value="{{ old('admin_commission', $webSetting->admin_commission ?? '') }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 