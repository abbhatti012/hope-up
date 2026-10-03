@extends('admin.layout.layout')

@section('content')
    <div class="app-hero-header d-flex align-items-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
                <a href="{{ route('home') }}">Home</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('assessments.index') }}">Assessment Questions</a>
            </li>
            <li class="breadcrumb-item active">
                All Questions
            </li>
        </ol>
    </div>

    <div class="app-body">
        @include('admin/notifications')
        
        <div class="row gx-3">
            <div class="col-xl-12">
                <div class="card mb-3">
                    <div class="card-header bg-white border-bottom">
                        <div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
                            <h5 class="card-title mb-3 mb-md-0">
                                <i class="ri-question-line me-2 text-primary"></i>Assessment Questions
                                <span class="badge bg-soft-primary text-primary ms-2">{{ $questions->total() }} Total</span>
                            </h5>
                            <a href="{{ route('assessments.create') }}" class="btn btn-primary">
                                <i class="ri-add-line me-1"></i> Add Question
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Search and Filter Card -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body p-3">
                                <div class="row g-3">
                                    <div class="col-md-8">
                                        <form method="GET" action="{{ route('assessments.index') }}" class="search-form">
                                            <div class="input-group">
                                                <span class="input-group-text bg-white border-end-0">
                                                    <i class="ri-search-line text-muted"></i>
                                                </span>
                                                <input type="search" 
                                                       name="search" 
                                                       class="form-control border-start-0 ps-0" 
                                                       placeholder="Search questions by text..." 
                                                       value="{{ request()->search }}">
                                                @if(request()->search)
                                                <a href="{{ route('assessments.index') }}" class="btn btn-outline-secondary" type="button">
                                                    <i class="ri-close-line"></i>
                                                </a>
                                                @endif
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="ri-search-2-line me-1"></i> Search
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-4">
                                        <form method="GET" action="{{ route('assessments.index') }}">
                                            <div class="input-group">
                                                <span class="input-group-text bg-white border-end-0">
                                                    <i class="ri-list-settings-line text-muted"></i>
                                                </span>
                                                <select name="assessment_type" class="form-select" onchange="this.form.submit()">
                                                    <option value="">All Types</option>
                                                    <option value="pre" {{ request('assessment_type') == 'pre' ? 'selected' : '' }}>Pre-Assessment</option>
                                                    <option value="mid" {{ request('assessment_type') == 'mid' ? 'selected' : '' }}>Mid-Assessment</option>
                                                    <option value="post" {{ request('assessment_type') == 'post' ? 'selected' : '' }}>Post-Assessment</option>
                                                </select>
                                                <select name="per_page" class="form-select" onchange="this.form.submit()">
                                                    @foreach([10, 25, 50, 100] as $size)
                                                        <option value="{{ $size }}" {{ request('per_page', 15) == $size ? 'selected' : '' }}>
                                                            Show {{ $size }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                @if(request()->search || request()->assessment_type)
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="ri-information-line me-1"></i>
                                        {{ $questions->total() }} result(s) found
                                        @if(request()->search)
                                            for "{{ request()->search }}"
                                        @endif
                                        @if(request()->assessment_type)
                                            in {{ ucfirst(request()->assessment_type) }}-Assessment
                                        @endif
                                    </small>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Question</th>
                                        <th>Assessment Type</th>
                                        <th>Options</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($questions as $question)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary">{{ $question->sequence_number }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-start">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1">{{ Str::limit($question->question_text, 80) }}</h6>
                                                    @if($question->explanation)
                                                        <small class="text-muted">{{ Str::limit($question->explanation, 50) }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $badgeClass = match($question->assessment_type) {
                                                    'pre' => 'bg-info',
                                                    'mid' => 'bg-warning',
                                                    'post' => 'bg-success',
                                                    default => 'bg-secondary',
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ ucfirst($question->assessment_type) }}-Assessment</span>
                                        </td>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="badge bg-warning mb-1">{{ $question->options_count }} options</span>
                                            </div>
                                        </td>
                                        <td>{{ $question->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="action-buttons">
                                                <a href="{{ route('assessments.show', $question) }}" 
                                                   class="btn btn-sm btn-action btn-outline-info" 
                                                   data-bs-toggle="tooltip" 
                                                   data-bs-placement="top" 
                                                   title="View Question">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <a href="{{ route('assessments.edit', $question) }}" 
                                                   class="btn btn-sm btn-action btn-outline-primary" 
                                                   data-bs-toggle="tooltip" 
                                                   data-bs-placement="top" 
                                                   title="Edit Question">
                                                    <i class="ri-edit-line"></i>
                                                </a>
                                                <form action="{{ route('assessments.destroy', $question) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('Are you sure you want to delete this question?')" 
                                                      style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-action btn-outline-danger" 
                                                            data-bs-toggle="tooltip" 
                                                            data-bs-placement="top" 
                                                            title="Delete Question">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="ri-question-line" style="font-size: 3rem;"></i>
                                                <p class="mt-2">No questions found</p>
                                                <a href="{{ route('assessments.create') }}" class="btn btn-primary btn-sm">
                                                    Create your first question
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted">
                                Showing {{ $questions->firstItem() }} to {{ $questions->lastItem() }} of {{ $questions->total() }} entries
                            </div>
                            <div>
                                {{ $questions->links('pagination::bootstrap-4') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endsection 