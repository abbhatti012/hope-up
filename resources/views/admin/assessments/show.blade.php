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
                Question Details
            </li>
        </ol>
    </div>

    <div class="app-body">
        @include('admin/notifications')
        
        <div class="row gx-3">
            <!-- Question Details -->
            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-header bg-white border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="ri-question-line me-2"></i>Question Information
                            </h5>
                            <div class="btn-group">
                                <a href="{{ route('assessments.edit', $question) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="ri-edit-line me-1"></i> Edit
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Question:</strong></td>
                                        <td>{{ $question->question_text }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Assessment Type:</strong></td>
                                        <td><span class="badge bg-secondary">{{ ucfirst($question->assessment_type) }}-Assessment</span></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Sequence:</strong></td>
                                        <td>{{ $question->sequence_number }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Options:</strong></td>
                                        <td>{{ $question->options->count() }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Created:</strong></td>
                                        <td>{{ $question->created_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Updated:</strong></td>
                                        <td>{{ $question->updated_at->format('M d, Y H:i') }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>


                    </div>
                </div>

                <!-- Options -->
                <div class="card mb-3">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="ri-list-check me-2"></i>Options ({{ $question->options->count() }})
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($question->options->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Option Text</th>
                                            <th>Sequence</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($question->options as $option)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-primary">{{ $option->sequence_number }}</span>
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong>{{ $option->option_text }}</strong>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-info">{{ $option->sequence_number }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="ri-list-check text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2">No options found</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="ri-bar-chart-line me-2"></i>Question Statistics
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="p-3">
                                    <h3 class="text-primary mb-1">{{ $question->options->count() }}</h3>
                                    <p class="text-muted mb-0">Options</p>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-3">
                                    <h3 class="text-warning mb-1">{{ $question->sequence_number }}</h3>
                                    <p class="text-muted mb-0">Sequence</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Assessment Type Info -->
                <div class="card mb-3">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="ri-information-line me-2"></i>Assessment Type
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($question->assessment_type == 'pre')
                            <div class="alert alert-info">
                                <h6 class="alert-heading">
                                    <i class="ri-arrow-right-line me-2"></i>Pre-Assessment
                                </h6>
                                <p class="mb-0">This question is part of the initial assessment to establish a baseline for the user's mental health before they start using the app.</p>
                            </div>
                        @elseif($question->assessment_type == 'mid')
                            <div class="alert alert-warning">
                                <h6 class="alert-heading">
                                    <i class="ri-arrow-right-line me-2"></i>Mid-Assessment
                                </h6>
                                <p class="mb-0">This question tracks progress and engagement during app usage to monitor improvement.</p>
                            </div>
                        @elseif($question->assessment_type == 'post')
                            <div class="alert alert-success">
                                <h6 class="alert-heading">
                                    <i class="ri-arrow-right-line me-2"></i>Post-Assessment
                                </h6>
                                <p class="mb-0">This question evaluates overall effectiveness and impact after using the app.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 