@extends('admin.layouts.app')

@section('title', 'Manage Quizzes')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Quizzes</h3>
                    <a href="{{ route('quizzes.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create New Quiz
                    </a>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Questions</th>
                                    <th>Time Limit (mins)</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($quizzes as $quiz)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $quiz->title }}</td>
                                        <td>{{ $quiz->category }}</td>
                                        <td>{{ $quiz->questions_count ?? 0 }} / {{ $quiz->total_questions }}</td>
                                        <td>{{ $quiz->time_limit_minutes }}</td>
                                        <td>
                                            <span class="badge {{ $quiz->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $quiz->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('quizzes.questions', $quiz->id) }}" 
                                                   class="btn btn-sm btn-info" 
                                                   title="Manage Questions">
                                                    <i class="fas fa-question-circle"></i>
                                                </a>
                                                <a href="{{ route('quizzes.edit', $quiz->id) }}" 
                                                   class="btn btn-sm btn-primary" 
                                                   title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('quizzes.destroy', $quiz->id) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this quiz? This action cannot be undone.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No quizzes found. <a href="{{ route('quizzes.create') }}">Create one now</a></td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        {{ $quizzes->links() }}
                    </div>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</div>
<!-- /.container-fluid -->
@endsection

@push('styles')
<style>
    .table td {
        vertical-align: middle;
    }
    .btn-group .btn {
        margin-right: 3px;
    }
</style>
@endpush
