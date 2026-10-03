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
                        <div class="schedule-actions">
                            <button class="btn btn-outline-primary" id="addScheduleBtn">
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
            minTime: '06:00:00',
            maxTime: '22:00:00',
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
                if (event.allDay || (event.end && event.end.diff(event.start, 'days') > 1)) {
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
            },
            eventRender: function(event, element, view) {
                // Add tooltip for events
                if (event.notes) {
                    element.attr('title', event.notes);
                    element.tooltip({ placement: 'top', trigger: 'hover' });
                }
                
                // Style multi-day events differently
                if (event.allDay) {
                    element.addClass('fc-event-multiday');
                    // For multi-day events, remove the time display
                    if (view.type === 'month') {
                        element.find('.fc-time').remove();
                    }
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
            events: '{{ route("schedules.list") }}',
            eventRender: function(event, element) {
                element.find('.fc-title').prepend('<i class="ri-user-line me-1"></i>');
                
                // Add delete button to events
                element.find('.fc-content').append(
                    '<span class="close-event" style="position:absolute;right:5px;top:2px;cursor:pointer;color:red;"><i class="ri-close-line"></i></span>'
                );

                element.find('.close-event').on('click', function(e) {
                    e.stopPropagation();
                    
                    Swal.fire({
                        title: 'Delete Schedule',
                        text: event.is_recurring ? 
                            'This is a recurring schedule. Delete this occurrence only or all future occurrences?' :
                            'Are you sure you want to delete this schedule?',
                        icon: 'warning',
                        showDenyButton: event.is_recurring,
                        showCancelButton: true,
                        confirmButtonText: event.is_recurring ? 'This occurrence only' : 'Delete',
                        denyButtonText: 'All future occurrences',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#3085d6',
                        denyButtonColor: '#d33',
                        cancelButtonColor: '#6c757d'
                    }).then((result) => {
                        if (result.isConfirmed || result.isDenied) {
                            const deleteAll = result.isDenied && event.is_recurring;
                            const url = deleteAll ? 
                                "{{ route('schedules.delete') }}" : 
                                "{{ route('schedules.delete') }}";
                                
                            $.ajax({
                                url: url,
                                type: "POST",
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    id: event.original_id || event.id,
                                    delete_all: deleteAll ? 1 : 0,
                                    is_recurring: event.is_recurring ? 1 : 0
                                },
                                success: function(response) {
                                    if (response.success) {
                                        if (deleteAll && event.is_recurring) {
                                            // Remove all occurrences of this recurring event
                                            $('#calendar').fullCalendar('clientEvents', function(e) {
                                                return e.original_id === event.original_id;
                                            }).forEach(function(e) {
                                                $('#calendar').fullCalendar('removeEvents', e._id);
                                            });
                                        } else {
                                            // Remove just this occurrence
                                            $('#calendar').fullCalendar('removeEvents', event.id);
                                        }
                                        Swal.fire('Deleted!', 'Schedule has been deleted.', 'success');
                                    }
                                },
                                error: function() {
                                    Swal.fire('Error!', 'Failed to delete schedule.', 'error');
                                }
                            });
                        }
                    });
                });
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
                
                // Handle recurring event data if any
                if (calEvent.is_recurring) {
                    $('#isRecurring').prop('checked', true);
                    $('#recurringDays').show();
                    // Check the days that this event recurs on
                    if (calEvent.recurring_days && calEvent.recurring_days.length > 0) {
                        $('.form-check-input', '#recurringDays').prop('checked', false);
                        calEvent.recurring_days.forEach(day => {
                            $(`#recurringDays input[value="${day}"]`).prop('checked', true);
                        });
                    }
                } else {
                    $('#isRecurring').prop('checked', false);
                    $('#recurringDays').hide();
                }
                
                $('#notes').val(calEvent.notes || '');
                $('#modalTitle').text('Edit Schedule');
                $('#scheduleModal').modal('show');
            },
            select: function(start, end, jsEvent, view) {
                $('#startDate').val(start.format('YYYY-MM-DD'));
                $('#endDate').val(end.format('YYYY-MM-DD'));
                $('#startTime').val(start.format('HH:mm'));
                $('#endTime').val(end.format('HH:mm'));
                $('#scheduleModal').modal('show');
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
            });

        // Function to update schedule via AJAX
        function updateSchedule(event) {
            const doctorId = event.doctor_id || $('#doctorId').val();
            const doctorName = doctorId ? $(`#doctorId option[value="${doctorId}"]`).text() : 'Doctor';
            
            const formData = {
                _token: "{{ csrf_token() }}",
                id: event.id,
                user_id: doctorId,
                date: moment(event.start).format("YYYY-MM-DD"),
                start_time: moment(event.start).format("HH:mm:ss"),
                end_time: moment(event.end).format("HH:mm:ss"),
                event: event.title || `Appointment with ${doctorName}`,
                notes: event.notes || '',
                is_recurring: event.is_recurring || 0,
                recurring_days: event.recurring_days || []
            };

            $.ajax({
                url: "{{ route('schedules.storeOrUpdate') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Success!', 'Schedule updated successfully.', 'success');
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
                date: startDate,
                end_date: endDate,
                start_time: $('#startTime').val(),
                end_time: $('#endTime').val(),
                event: 'Appointment with ' + doctorName,
                notes: $('#notes').val(),
                is_recurring: $('#isRecurring').is(':checked') ? 1 : 0,
                recurring_days: []
            };

            if ($('#isRecurring').is(':checked')) {
                $('input[type="checkbox"]:checked', '#recurringDays').each(function() {
                    formData.recurring_days.push($(this).val());
                });
            }

            $.ajax({
                url: "{{ route('schedules.storeOrUpdate') }}",
                type: "POST",
                data: formData,
                success: function(response) {
                    if (response.success) {
                        Swal.fire('Success!', 'Schedule saved successfully.', 'success');
                        $('#scheduleModal').modal('hide');
                        calendar.fullCalendar('refetchEvents');
                    }
                },
                error: function() {
                    Swal.fire('Error!', 'Failed to save schedule.', 'error');
                }
            });
        });

        // Toggle recurring days visibility
        $('#isRecurring').change(function() {
            if ($(this).is(':checked')) {
                $('#recurringDays').slideDown();
            } else {
                $('#recurringDays').slideUp();
            }
        });

        // Initialize tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
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
                        <select class="form-select" id="doctorId" required>
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
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="isRecurring">
                            <label class="form-check-label" for="isRecurring">
                                Recurring Weekly
                            </label>
                        </div>
                    </div>
                    <div class="mb-3" id="recurringDays" style="display: none;">
                        <label class="form-label">Repeat On</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" id="day{{ $loop->index }}" value="{{ $day }}">
                                    <label class="form-check-label" for="day{{ $loop->index }}">{{ $day }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveSchedule">
                    <i class="ri-save-line me-1"></i> Save Schedule
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
