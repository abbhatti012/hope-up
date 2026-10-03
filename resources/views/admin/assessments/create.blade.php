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
                Add Question
            </li>
        </ol>
    </div>

    <div class="app-body">
        @include('admin/notifications')
        
        <div class="row gx-3">
            <div class="col-xl-12">
                <div class="card mb-3">
                    <div class="card-header bg-white border-bottom">
                        <h5 class="card-title mb-0">
                            <i class="ri-question-add-line me-2"></i>Add New Question
                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('assessments.store') }}" method="POST" id="questionForm" class="needs-validation" novalidate>
                            @csrf
                            
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="question_text" class="form-label">Question Text <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('question_text') is-invalid @enderror" 
                                                  id="question_text" name="question_text" rows="4" 
                                                  placeholder="Enter your question here..." required>{{ old('question_text') }}</textarea>
                                        @error('question_text')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="assessment_type" class="form-label">Assessment Type <span class="text-danger">*</span></label>
                                        <select class="form-select @error('assessment_type') is-invalid @enderror" 
                                                id="assessment_type" name="assessment_type" required>
                                            <option value="">Select Type</option>
                                            @foreach($assessmentTypes as $key => $type)
                                                <option value="{{ $key }}" {{ old('assessment_type') == $key ? 'selected' : '' }}>
                                                    {{ $type }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('assessment_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="sequence_number" class="form-label">Sequence Number <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('sequence_number') is-invalid @enderror" 
                                               id="sequence_number" name="sequence_number" value="{{ old('sequence_number', 1) }}" 
                                               min="1" required>
                                        @error('sequence_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>


                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Options Section -->
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="mb-3">
                                        <i class="ri-list-check me-2"></i>Options <span class="text-danger">*</span>
                                    </h5>
                                    <div id="optionsContainer">
                                        <div class="option-row mb-3 p-3 border rounded bg-light">
                                            <div class="row g-3">
                                                <div class="col-md-10">
                                                    <label class="form-label">Option 1</label>
                                                    <input type="text" class="form-control" name="options[0][option_text]" 
                                                           placeholder="Enter option text" required>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="form-label">Sequence</label>
                                                    <input type="number" class="form-control" name="options[0][sequence_number]" 
                                                           value="1" min="1" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addOption()">
                                        <i class="ri-add-line me-1"></i> Add Option
                                    </button>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <h5 class="alert-heading">
                                            <i class="ri-information-line me-2"></i>Question Guidelines
                                        </h5>
                                        <ul class="mb-0">
                                            <li>Use clear, concise language for questions</li>
                                            <li>Provide 2-4 options for multiple choice questions</li>
                                            <li>Mark at least one option as correct</li>
                                            <li>Sequence numbers should be unique within each assessment type</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="d-flex justify-content-end gap-2">
                                        <a href="{{ route('assessments.index') }}" class="btn btn-secondary">
                                            <i class="ri-arrow-left-line me-1"></i> Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ri-save-line me-1"></i> Create Question
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
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
    newOption.className = 'option-row mb-3 p-3 border rounded bg-light';
    newOption.innerHTML = `
        <div class="row g-3">
            <div class="col-md-10">
                <label class="form-label">Option ${optionCounter + 1}</label>
                <input type="text" class="form-control" name="options[${optionCounter}][option_text]" 
                       placeholder="Enter option text" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Sequence</label>
                <input type="number" class="form-control" name="options[${optionCounter}][sequence_number]" 
                       value="${optionCounter + 1}" min="1" required>
            </div>
        </div>
    `;
    container.appendChild(newOption);
    optionCounter++;
}

$(document).ready(function() {
    // Form validation
    $('#questionForm').on('submit', function() {
        var isValid = true;
        
        // Check required fields
        $('[required]').each(function() {
            if (!$(this).val()) {
                $(this).addClass('is-invalid');
                isValid = false;
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        

        
        if (!isValid) {
            return false;
        }
    });
    
    // Remove validation on input
    $('input, select, textarea').on('input change', function() {
        $(this).removeClass('is-invalid');
    });
});
</script>
@endsection 