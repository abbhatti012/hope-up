@php
    $days = ['sun' => 'Sun', 'mon' => 'Mon', 'tue' => 'Tue', 'wed' => 'Wed', 'thu' => 'Thu', 'fri' => 'Fri', 'sat' => 'Sat'];
    $times = ['7AM', '8AM', '9AM', '10AM', '11AM', '12PM', '1PM', '2PM', '3PM', '4PM', '5PM', '6PM', '7PM', '8PM'];
@endphp

<div class="mb-3">
    <label class="form-label">Select Time Slot</label>
    <div class="input-group">
        <select class="form-select" id="universal_from">
            <option value="">From</option>
            @foreach ($times as $time)
                <option value="{{ $time }}">{{ $time }}</option>
            @endforeach
        </select>
        <select class="form-select" id="universal_to">
            <option value="">To</option>
            @foreach ($times as $time)
                <option value="{{ $time }}">{{ $time }}</option>
            @endforeach
        </select>
        <button type="button" class="btn btn-primary" id="applyToAll">Apply to Selected Days</button>
    </div>
</div>

<div class="row gx-3">
    @foreach ($days as $key => $label)
        <div class="col-xxl-3 col-lg-4 col-sm-6">
            <div class="mb-3">
                <div class="form-check mb-1">
                    <input class="form-check-input day-checkbox" type="checkbox" value="{{ $key }}" id="{{ $key }}_check">
                    <label class="form-check-label" for="{{ $key }}_check">
                        {{ $label }}
                    </label>
                </div>
                <div class="input-group">
                    <select class="form-select" name="availability[{{ $key }}][from]" id="{{ $key }}_from">
                        <option value="">From</option>
                        @foreach ($times as $time)
                            <option value="{{ $time }}" {{ old('availability.'.$key.'.from') == $time ? 'selected' : '' }}>{{ $time }}</option>
                        @endforeach
                    </select>

                    <select class="form-select" name="availability[{{ $key }}][to]" id="{{ $key }}_to">
                        <option value="">To</option>
                        @foreach ($times as $time)
                            <option value="{{ $time }}" {{ old('availability.'.$key.'.to') == $time ? 'selected' : '' }}>{{ $time }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    @endforeach
</div>

@push('scripts')
<script>
    document.getElementById('applyToAll').addEventListener('click', function() {
        const from = document.getElementById('universal_from').value;
        const to = document.getElementById('universal_to').value;

        if (!from || !to) {
            alert('Please select both From and To times.');
            return;
        }

        document.querySelectorAll('.day-checkbox:checked').forEach(cb => {
            const day = cb.value;
            document.getElementById(`${day}_from`).value = from;
            document.getElementById(`${day}_to`).value = to;
        });
    });
</script>
@endpush