@extends('admin.layout.layout')

@section('content')
<div class="app-body">
    <div class="row gx-3">
        <div class="col-xl-12">
            <div class="card mb-3">
                <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">
                        <i class="ri-user-3-line me-2 text-primary"></i>All User Answers
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>View Answers</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        <a href="{{ route('patients.show', $user->id) }}" target="_blank" class="text-decoration-underline text-primary">
                                            {{ $user->first_name }} {{ $user->last_name }}
                                        </a>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <a href="{{ route('user.answers', $user->id) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="ri-eye-line"></i> View Answers
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 