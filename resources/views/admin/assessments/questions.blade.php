s@extends('admin.layout.layout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('assessments.index') }}">Assessments</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('assessments.show', $assessment) }}">{{ $assessment->title }}</a></li>
                        <li class="breadcrumb-item active">Questions</li>
                    </ol>
                </div>
                <h4 class="page-title">Questions - {{ $assessment->title }}</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="header-title">Questions ({{ $questions->count() }}/{{ $assessment->total_questions }})</h4>
                            <p class="text-muted">Manage questions for this assessment</p>
                        </div>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
                            <i class="ri-add-line"></i> Add Question
                        </button>
                    </div>

                    @if($questions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-centered table-striped">
                                <thead>
                                    <tr>
                                        <th width="50">#</th>
                                        <th>Question</th>
                                        <th>Options</th>
                                        <th>Assessment Type</th>
                                        <th width="150">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($questions as $question)
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary">{{ $question->sequence_number }}</span>
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ Str::limit($question->question_text, 80) }}</strong>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $question->options->count() }} options</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-dark">{{ ucfirst($question->assessment_type) }}</span>
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-outline-primary" 
                                                            onclick="editQuestion({{ $question->id }})">
                                                        <i class="ri-edit-line"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-info" 
                                                            onclick="viewQuestion({{ $question->id }})">
                                                        <i class="ri-eye-line"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-danger" 
                                                            onclick="deleteQuestion({{ $question->id }})">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ri-question-line text-muted" style="font-size: 4rem;"></i>
                            <h5 class="text-muted mt-3">No questions added yet</h5>
                            <p class="text-muted">Start by adding questions to this assessment</p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
                                <i class="ri-add-line"></i> Add First Question
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Question Modal -->
<div class="modal fade" id="addQuestionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Question</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="addQuestionForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="question_text" class="form-label">Question Text <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="question_text" name="question_text" rows="3" required></textarea>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="sequence_number" class="form-label">Sequence Number <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="sequence_number" name="sequence_number" 
                                       value="{{ $questions->count() + 1 }}" min="1" required>
                            </div>

                            <div class="mb-3">
                                <label for="assessment_type" class="form-label">Assessment Type <span class="text-danger">*</span></label>
                                <select class="form-select" id="assessment_type" name="assessment_type" required>
                                    <option value="pre">Pre-Assessment</option>
                                    <option value="mid">Mid-Assessment</option>
                                    <option value="post">Post-Assessment</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Options <span class="text-danger">*</span></label>
                        <div id="optionsContainer">
                            <div class="option-row mb-2">
                                <div class="row">
                                    <div class="col-md-10">
                                        <input type="text" class="form-control" name="options[0][option_text]" 
                                               placeholder="Option text" required>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" class="form-control" name="options[0][sequence_number]" 
                                               value="1" min="1" placeholder="Seq">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addOption()">
                            <i class="ri-add-line"></i> Add Option
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Question</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Question Modal -->
<div class="modal fade" id="viewQuestionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Question Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="questionDetails">
                <!-- Question details will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let optionCounter = 1;

function addOption() {
    const container = document.getElementById('optionsContainer');
    const newOption = document.createElement('div');
    newOption.className = 'option-row mb-2';
    newOption.innerHTML = `
        <div class="row">
            <div class="col-md-10">
                <input type="text" class="form-control" name="options[${optionCounter}][option_text]" 
                       placeholder="Option text" required>
            </div>
            <div class="col-md-2">
                <input type="number" class="form-control" name="options[${optionCounter}][sequence_number]" 
                       value="${optionCounter + 1}" min="1" placeholder="Seq">
            </div>
        </div>
    `;
    container.appendChild(newOption);
    optionCounter++;
}

function viewQuestion(questionId) {
    // Load question details via AJAX
    fetch(`/api/v1/questions/${questionId}`)
        .then(response => response.json())
        .then(data => {
            const question = data.data;
            let optionsHtml = '';
            
            question.options.forEach(option => {
                optionsHtml += `
                    <div class="mb-2">
                        <span class="badge ${option.is_correct ? 'bg-success' : 'bg-light text-dark'}">
                            ${option.sequence_number}. ${option.option_text}
                            ${option.is_correct ? ' ✓' : ''}
                        </span>
                    </div>
                `;
            });
            
            document.getElementById('questionDetails').innerHTML = `
                <div class="mb-3">
                    <strong>Question:</strong>
                    <p>${question.question_text}</p>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <strong>Type:</strong> ${question.type}<br>
                        <strong>Points:</strong> ${question.points}<br>
                        <strong>Assessment Type:</strong> ${question.assessment_type}
                    </div>
                    <div class="col-md-6">
                        <strong>Sequence:</strong> ${question.sequence_number}<br>
                        <strong>Options:</strong> ${question.options.length}
                    </div>
                </div>
                ${question.explanation ? `<div class="mt-3"><strong>Explanation:</strong><p>${question.explanation}</p></div>` : ''}
                <div class="mt-3">
                    <strong>Options:</strong>
                    <div class="mt-2">
                        ${optionsHtml}
                    </div>
                </div>
            `;
            
            new bootstrap.Modal(document.getElementById('viewQuestionModal')).show();
        })
        .catch(error => {
            console.error('Error loading question:', error);
            alert('Error loading question details');
        });
}

function deleteQuestion(questionId) {
    if (confirm('Are you sure you want to delete this question?')) {
        fetch(`/api/v1/questions/${questionId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error deleting question:', error);
            alert('Error deleting question');
        });
    }
}

// Handle form submission
document.getElementById('addQuestionForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('quiz_id', {{ $assessment->id }});
    
    fetch('/api/v1/questions', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            location.reload();
        } else {
            alert('Error adding question');
        }
    })
    .catch(error => {
        console.error('Error adding question:', error);
        alert('Error adding question');
    });
});
</script>
@endsection 