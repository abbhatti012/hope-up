@extends('admin.layout.layout')

@section('content')
<div class="app-body">
    <div class="row gx-3">
        <div class="col-xl-12">
            <div class="card mb-3">
                <div class="card-header bg-white border-bottom d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="card-title mb-0">
                            <i class="ri-user-3-line me-2 text-primary"></i>
                            <a href="{{ route('patients.show', $user->id) }}" target="_blank" class="text-decoration-underline text-primary">
                                {{ $user->first_name }} {{ $user->last_name }}
                            </a>
                            <span class="text-muted small">&lt;{{ $user->email }}&gt;</span>
                        </h5>
                    </div>
                    <a href="{{ route('assessments.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="ri-arrow-left-line me-1"></i> Back to Questions
                    </a>
                </div>
                <div class="card-body">
                    @foreach($userAnswers as $type => $answers)
                        <h6 class="mt-4 mb-2">{{ ucfirst($type) }} Assessment</h6>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Question</th>
                                        <th>Answer</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($answers as $answer)
                                    <tr>
                                        <td>{{ $answer->question->question_text }}</td>
                                        <td>
                                            @if($answer->option)
                                                {{ $answer->option->option_text }}
                                            @elseif($answer->answer_text)
                                                {{ $answer->answer_text }}
                                            @else
                                                <em>No answer</em>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 