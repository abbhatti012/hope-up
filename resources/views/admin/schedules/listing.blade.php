@extends('admin.layout.layout')

@section('css')
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css'>
    <style>
        .fc-toolbar h2 {
            font-size: 1.4rem;
            font-weight: 600;
            color: #2c3e50;
        }
        .fc-button {
            background: #fff;
            border: 1px solid #e1e5ea;
            color: #2c3e50;
            text-transform: capitalize;
            box-shadow: none;
            border-radius: 4px;
            padding: 6px 12px;
            font-size: 0.875rem;
            transition: all 0.2s;
        }
        .fc-button:hover {
            background: #f8f9fa;
            border-color: #dae0e5;
        }
        .fc-button-primary {
            background-color: #4361ee;
            border-color: #4361ee;
            color: #fff;
        }
        .fc-button-primary:hover {
            background-color: #3a56d4;
            border-color: #3a56d4;
        }
        .fc-button-active {
            background-color: #3a56d4;
            border-color: #3a56d4;
        }
        .fc-event {
            border-radius: 4px;
            border: none;
            padding: 2px 5px;
            font-size: 0.8rem;
            cursor: pointer;
        }
        .fc-day-grid-event .fc-time {
            font-weight: 600;
        }
        .fc-today {
            background: #f8f9fa !important;
        }
        .fc-day-header {
            background-color: #f8f9fa;
            padding: 8px 0;
            font-weight: 600;
            color: #2c3e50;
        }
        #calendar {
            max-width: 100%;
            margin: 0 auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.04);
            padding: 1.5rem;
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        /* Multi-day event styling */
        .fc-event {
            border-radius: 4px;
            border: 1px solid #3a87ad;
            background-color: #3a87ad;
            color: #fff;
            padding: 2px 5px;
            font-size: 0.8rem;
            margin: 1px 2px;
        }
        
        .fc-event .fc-content {
            padding: 2px;
        }
        
        .fc-event .fc-time {
            font-weight: 600;
            margin-right: 5px;
        }
        
        .fc-event.fc-event-multiday {
            background-color: #3a87ad;
            border-color: #3a87ad;
        }
        
        .fc-event.fc-event-multiday .fc-time {
            display: none;
        }
        
        .fc-day-grid-event .fc-time {
            display: inline-block;
        }
        .schedule-actions {
            display: flex;
            gap: 10px;
            margin-bottom: 1.5rem;
        }
        .fc-toolbar {
            margin-bottom: 1.5rem !important;
        }
    </style>
@endsection

@section('content')
<div class="app-hero-header d-flex align-items-center">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <i class="ri-home-8-line lh-1 pe-3 me-3 border-end"></i>
            <a href="{{ route('/') }}">Home</a>
        </li>
        <li class="breadcrumb-item text-primary" aria-current="page">
            <i class="ri-calendar-2-line me-2"></i>Schedule Management
        </li>
    </ol>
</div>

<div class="app-body">
    @include('admin/notifications')
    <div class="row gx-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-white py-3">
                    <div class="d-flex flex-column flex-md-row align-items-center justify-content-between">
                        <h5 class="card-title mb-0">
                            <i class="ri-calendar-2-line me-2 text-primary"></i>Doctor Schedules
                        </h5>
                        <div class="schedule-actions d-flex align-items-center">
                            <div class="me-3" style="min-width: 250px;">
                                <select class="form-select form-select-sm" id="doctorFilter">
                                    <option value="">All Doctors</option>
                                    @foreach($specialists as $doctor)
                                        <option value="{{ $doctor->id }}">{{ $doctor->first_name }} {{ $doctor->last_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button class="btn btn-outline-primary me-2" id="addScheduleBtn">
                                <i class="ri-add-line me-1"></i> Add Schedule
                            </button>
                            <div class="btn-group" role="group">
                                <button type="button" class="btn btn-outline-secondary" id="dayView">Day</button>
                                <button type="button" class="btn btn-outline-secondary active" id="weekView">Week</button>
                                <button type="button" class="btn btn-outline-secondary" id="monthView">Month</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
                    </div>
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>

<script>
    $(document).ready(function() {
        // Initialize calendar
        var calendar = $('#calendar').fullCalendar({
            defaultView: 'agendaWeek',
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            defaultTimedEventDuration: '01:00:00',
            forceEventDuration: true,
            nextDayThreshold: '00:00:00',
            displayEventTime: true,
            timeFormat: 'HH:mm',
            slotLabelFormat: 'HH:mm',
            allDaySlot: true,
            allDayText: 'All Day',
            slotDuration: '00:30:00',
            minTime: '00:00:00',
            maxTime: '24:00:00',
            editable: true,
            selectable: true,
            droppable: true,
            eventLimit: true,
            nowIndicator: true,
            eventOrder: 'start',
            eventOrderStrict: true,
            eventRender: function(event, element, view) {
                // Add user icon to event title
                element.find('.fc-title').prepend('<i class="ri-user-line me-1"></i>');
                
                // For multi-day events, add a special class and hide time
                const isMultiDay = event.allDay || (event.end && event.end.diff(event.start, 'days') > 1);
                
                if (isMultiDay) {
                    element.addClass('fc-event-multiday');
                    if (view.type === 'month') {
                        element.find('.fc-time').remove();
                    }
                }
                
                // Add tooltip if notes exist
                if (event.notes) {
                    element.attr('title', event.notes);
                    element.tooltip({ placement: 'top', trigger: 'hover' });
                }
                
                // Add delete button to events
                if (event.id) {
                    element.find('.fc-content').append(
                        '<div class="fc-delete-btn" style="position: absolute; right: 2px; top: 2px; cursor: pointer;">' +
                        '<i class="ri-delete-bin-line" style="font-size: 12px; color: #fff;"></i>' +
                        '</div>'
                    );
                    
                    // Handle delete button click
                    element.find('.fc-delete-btn').on('click', function(e) {
                        e.stopPropagation();
                        
                        Swal.fire({
                            title: 'Are you sure?',
                            text: "You won't be able to revert this!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                $.ajax({
                                    url: "{{ route('schedules.destroy') }}",
                                    type: "POST",
                                    data: {
                                        _token: "{{ csrf_token() }}",
                                        id: event.id
                                    },
                                    success: function(response) {
                                        if (response.success) {
                                            Swal.fire('Deleted!', 'The schedule has been deleted.', 'success');
                                            calendar.fullCalendar('refetchEvents');
                                        } else {
                                            Swal.fire('Error!', response.message || 'Failed to delete schedule.', 'error');
                                        }
                                    },
                                    error: function(xhr) {
                                        let errorMessage = 'Failed to delete schedule.';
                                        if (xhr.responseJSON && xhr.responseJSON.message) {
                                            errorMessage = xhr.responseJSON.message;
                                        }
                                        Swal.fire('Error!', errorMessage, 'error');
                                    }
                                });
                            }
                        });
                    });
                }
            },
            eventAfterAllRender: function(view) {
                // Re-initialize tooltips after render
                $('[data-bs-toggle="tooltip"]').tooltip();
                
                // Ensure multi-day events are properly styled
                $('.fc-event').each(function() {
                    const $event = $(this);
                    if ($event.hasClass('fc-event-multiday')) {
                        $event.find('.fc-time').remove();
                    }
                });
            },
            selectHelper: true,
            eventLimit: true,
            events: function(start, end, timezone, callback) {
                $.ajax({
                    url: '{{ route("schedules.list") }}',
                    dataType: 'json',
                    data: {
                        doctor_id: $('#doctorFilter').val()
                    },
                    success: function(events) {
                        // The events are already in the correct format from the server
                        callback(events);
                    },
                    error: function(xhr, status, error) {
                        console.error('Error fetching events:', error);
                        callback([]);
                    }
                });
            },
            eventDrop: function(event, delta, revertFunc) {
                if (moment(event.start).isBefore(moment(), 'day')) {
                    revertFunc();
                    Swal.fire('Error!', 'Cannot move events to the past.', 'error');
                    return;
                }
                updateSchedule(event);
            },
            eventResize: function(event, delta, revertFunc) {
                if (moment(event.start).isBefore(moment(), 'day')) {
                    revertFunc();
                    Swal.fire('Error!', 'Cannot resize events to the past.', 'error');
                    return;
                }
                updateSchedule(event);
            },
            dayClick: function(date, jsEvent, view) {
                const startDate = date.format('YYYY-MM-DD');
                const endDate = date.add(1, 'day').format('YYYY-MM-DD');
                $('#startDate').val(startDate);
                $('#endDate').val(endDate);
                $('#scheduleModal').modal('show');
            },
            eventClick: function(calEvent, jsEvent, view) {
                // Handle event click (view/edit)
                $('#scheduleId').val(calEvent.id);
                $('#doctorId').val(calEvent.doctor_id);
                $('#startTime').val(moment(calEvent.start).format('HH:mm'));
                $('#endTime').val(moment(calEvent.end).format('HH:mm'));
                
                // Set start and end dates
                $('#startDate').val(moment(calEvent.start).format('YYYY-MM-DD'));
                // If end date is available, use it; otherwise default to next day
                const endDate = calEvent.end_date ? 
                    moment(calEvent.end_date).format('YYYY-MM-DD') : 
                    moment(calEvent.start).add(1, 'day').format('YYYY-MM-DD');
                $('#endDate').val(endDate);
                
                $('#notes').val(calEvent.notes || '');
                $('#modalTitle').text('Edit Schedule');
                $('#deleteSchedule').show();
                $('#scheduleModal').modal('show');
            },
            select: function(start, end, jsEvent, view) {
                $('#startDate').val(start.format('YYYY-MM-DD'));
                $('#endDate').val(end.format('YYYY-MM-DD'));
                $('#startTime').val(start.format('HH:mm'));
                $('#endTime').val(end.format('HH:mm'));
                $('#scheduleModal').modal('show');
                $('#deleteSchedule').hide();
                calendar.fullCalendar('unselect');
            },
            eventDrop: function(event, delta, revertFunc) {
                if (moment(event.start).isBefore(moment(), 'day')) {
                    revertFunc();
                    Swal.fire('Error!', 'Cannot move events to the past.', 'error');
                    return;
                }
                updateSchedule(event);
            },
            eventResize: function(event, delta, revertFunc) {
                if (moment(event.start).isBefore(moment(), 'day')) {
                    revertFunc();
                    Swal.fire('Error!', 'Cannot resize events to the past.', 'error');
                    return;
                }
                updateSchedule(event);
            },
            eventAfterAllRender: function(view) {
                // Initialize tooltips
                $('[data-bs-toggle="tooltip"]').tooltip();
            }
        });

        // Handle view switching
        $('#dayView').click(function() {
            $('#calendar').fullCalendar('changeView', 'agendaDay');
            $(this).addClass('active').siblings().removeClass('active');
        });

        $('#weekView').click(function() {
            $('#calendar').fullCalendar('changeView', 'agendaWeek');
            $(this).addClass('active').siblings().removeClass('active');
        });

        $('#monthView').click(function() {
            $('#calendar').fullCalendar('changeView', 'month');
            $(this).addClass('active').siblings().removeClass('active');
        });

            // Add schedule button
            $('#addScheduleBtn').click(function() {
                $('#scheduleForm')[0].reset();
                $('#scheduleId').val('');
                $('#modalTitle').text('Add New Schedule');
                const today = moment().format('YYYY-MM-DD');
                const tomorrow = moment().add(1, 'day').format('YYYY-MM-DD');
                $('#startDate').val(today);
                $('#endDate').val(tomorrow);
                $('#scheduleModal').modal('show');
                $('#deleteSchedule').hide();
            });

        // Function to update schedule via AJAX
        function updateSchedule(event) {
            const doctorId = event.doctor_id || $('#doctorId').val();
            const doctorName = doctorId ? $(`#doctorId option[value="${doctorId}"]`).text() : 'Doctor';
            const isMultiDay = event.end && moment(event.end).diff(moment(event.start), 'days') > 0;
            
            const formData = {
                _token: "{{ csrf_token() }}",
                id: event.id,
                user_id: doctorId,
                start_date: moment(event.start).format("YYYY-MM-DD"),
                end_date: isMultiDay ? moment(event.end).subtract(1, 'day').format("YYYY-MM-DD") : moment(event.start).format("YYYY-MM-DD"),
                start_time: moment(event.start).format("HH:mm"),
                end_time: moment(event.end).format("HH:mm"),
                event: event.title || `${doctorName} availability`,
                notes: event.notes || '',
            };

            $.ajax({
                url: "{{ route('schedules.storeOrUpdate') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Success!', 'Schedule updated successfully.', 'success');
                        $('#scheduleModal').modal('hide');
                        $('#deleteSchedule').hide();
                        calendar.fullCalendar('refetchEvents');
                    }
                },
                error: function(xhr) {
                    console.error('Error updating schedule:', xhr.responseText);
                    Swal.fire('Error!', 'Failed to update schedule. ' + (xhr.responseJSON?.message || ''), 'error');
                    calendar.fullCalendar('refetchEvents');
                }
            });
        }

        // Handle form submission
        $('#saveSchedule').click(function() {
            const doctorName = $('#doctorId option:selected').text();
            const startDate = $('#startDate').val();
            const endDate = $('#endDate').val();
            
            // Validate end date is after start date
            if (new Date(endDate) < new Date(startDate)) {
                Swal.fire('Error', 'End date must be after start date', 'error');
                return;
            }
            
            const formData = {
                _token: "{{ csrf_token() }}",
                id: $('#scheduleId').val(),
                user_id: $('#doctorId').val(),
                start_date: startDate,
                end_date: endDate,
                start_time: $('#startTime').val(),
                end_time: $('#endTime').val(),
                event: doctorName + ' availability',
                notes: $('#notes').val(),
            };

            $.ajax({
                url: "{{ route('schedules.storeOrUpdate') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Success!', 'Schedule saved successfully.', 'success');
                        $('#scheduleModal').modal('hide');
                        $('#deleteSchedule').hide();
                        calendar.fullCalendar('refetchEvents');
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Failed to save schedule.';
                    if (xhr.status === 422 && xhr.responseJSON) {
                        // Handle Laravel validation errors
                        const errors = xhr.responseJSON.errors;
                        errorMessage = '';
                        // Loop through all error messages
                        for (const field in errors) {
                            if (errors.hasOwnProperty(field)) {
                                errorMessage += `${field}: ${errors[field].join(', ')}\n`;
                            }
                        }
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.message) {
                                errorMessage = response.message;
                            }
                        } catch (e) {
                            console.error('Error parsing response:', e);
                        }
                    }
                    Swal.fire({
                        title: 'Error!',
                        text: errorMessage.trim() || 'An unknown error occurred',
                        icon: 'error',
                        width: '600px',
                        customClass: {
                            content: 'text-left'
                        }
                    });
                }
            });
        });

        // Initialize tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
        
        // Handle doctor filter change
        $('#doctorFilter').change(function() {
            $('#calendar').fullCalendar('refetchEvents');
        });
        
        // Handle delete schedule
        $('#deleteSchedule').click(function() {
            const scheduleId = $('#scheduleId').val();
            if (!scheduleId) return;
            
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('schedules.destroy') }}",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: scheduleId
                        },
                        success: function(response) {
                            if (response.success) {
                                Swal.fire('Deleted!', 'The schedule has been deleted.', 'success');
                                $('#scheduleModal').modal('hide');
                                calendar.fullCalendar('refetchEvents');
                            } else {
                                Swal.fire('Error!', response.message || 'Failed to delete schedule.', 'error');
                            }
                        },
                        error: function(xhr) {
                            let errorMessage = 'Failed to delete schedule.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            Swal.fire('Error!', errorMessage, 'error');
                        }
                    });
                }
            });
        });
    });
</script>

<!-- Add/Edit Schedule Modal -->
<div class="modal fade" id="scheduleModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Add New Schedule</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="scheduleForm">
                    @csrf
                    <input type="hidden" id="scheduleId">
                    <div class="mb-3">
                        <label class="form-label">Doctor</label>
                        <select class="form-select searchable-dropdown" id="doctorId" required>
                            <option value="">Select Doctor</option>
                            @foreach($specialists as $doctor)
                                <option value="{{ $doctor->id }}">{{ $doctor->first_name }} {{ $doctor->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Time</label>
                            <input type="time" class="form-control" id="startTime" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Time</label>
                            <input type="time" class="form-control" id="endTime" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="startDate" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control" id="endDate" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="me-auto">
                    <button type="button" class="btn btn-danger" id="deleteSchedule" style="display: none;">
                        <i class="ri-delete-bin-line me-1"></i> Delete
                    </button>
                </div>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveSchedule">
                    <i class="ri-save-line me-1"></i> Save Schedule
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
