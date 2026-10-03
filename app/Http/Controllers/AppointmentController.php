<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Services\EmailService;
use App\Enums\NotificationStatus;
use App\Helpers\PaginationHelper;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $searchFields = ['id', 'appointment_date', 'appointment_time', 'status'];
        $query = Appointment::with(['patient', 'doctor']);

        // Apply search
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search, $searchFields) {
                foreach ($searchFields as $field) {
                    $q->orWhere($field, 'like', "%{$search}%");
                }
                $q->orWhereHas('patient', function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%");
                });
                $q->orWhereHas('doctor', function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%");
                });
            });
        }

        // Apply status filter
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        // Apply date range filter
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('appointment_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('appointment_date', '<=', $request->date_to);
        }

        // Apply doctor filter
        if ($request->has('doctor_id') && !empty($request->doctor_id)) {
            $query->where('doctor_id', $request->doctor_id);
        }

        // Apply treatment type filter
        if ($request->has('treatment_type') && !empty($request->treatment_type)) {
            $query->where('treatment_type', $request->treatment_type);
        }

        // Apply appointment type filter (general/opd)
        if ($request->has('type') && !empty($request->type)) {
            $query->where('treatment_type', $request->type);
        }

        // Handle export
        if ($request->has('export')) {
            return $this->export($query->get());
        }

        $appointments = $query->with([
                'patient' => function($q) {
                    $q->select('id', 'first_name', 'last_name', 'email', 'profile_photo');
                },
                'doctor' => function($q) {
                    $q->select('id', 'first_name', 'last_name', 'email', 'profile_photo');
                }
            ])
            ->orderBy('appointment_date', 'desc')
            ->orderBy('appointment_time', 'desc')
            ->paginate($request->per_page ?? 50)
            ->withQueryString();

        $doctors = \App\Models\User::where('role', 'specialist')->select('id', 'first_name', 'last_name')->get();
        
        return view('admin.appointments.listing', compact('appointments', 'doctors'));
    }

    /**
     * Export appointments to CSV
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $query = Appointment::query()
            ->with([
                'patient' => function($q) {
                    $q->select('id', 'first_name', 'last_name');
                },
                'doctor' => function($q) {
                    $q->select('id', 'first_name', 'last_name');
                }
            ]);

        // Apply the same filters as in the index method
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $searchFields = ['id', 'appointment_date', 'appointment_time', 'status'];
            $query->where(function($q) use ($search, $searchFields) {
                foreach ($searchFields as $field) {
                    $q->orWhere($field, 'like', "%{$search}%");
                }
                $q->orWhereHas('patient', function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%");
                });
                $q->orWhereHas('doctor', function($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%");
                });
            });
        }

        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->whereDate('appointment_date', '>=', $request->date_from);
        }

        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->whereDate('appointment_date', '<=', $request->date_to);
        }

        if ($request->has('type') && !empty($request->type)) {
            $query->where('treatment_type', $request->type);
        }

        $appointments = $query->orderBy('appointment_date', 'desc')
                            ->orderBy('appointment_time', 'desc')
                            ->get();
        
        $fileName = 'appointments-' . now()->format('Y-m-d') . '.csv';
        
        // Add BOM (Byte Order Mark) for Excel to handle special characters correctly
        $headers = [
            "Content-type"        => "text/csv; charset=utf-8",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = [
            'ID', 'Patient', 'Doctor', 'Date', 'Time', 'Status', 
            'Type', 'Treatment Type', 'Notes', 'Created At', 'Updated At'
        ];

        $callback = function() use($appointments, $columns) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for Excel
            fputs($file, "\xEF\xBB\xBF");
            
            // Write headers
            fputcsv($file, $columns);

            foreach ($appointments as $appointment) {
                $patientName = $appointment->patient 
                    ? trim($appointment->patient->first_name . ' ' . $appointment->patient->last_name)
                    : 'N/A';
                
                $doctorName = $appointment->doctor 
                    ? trim($appointment->doctor->first_name . ' ' . $appointment->doctor->last_name)
                    : 'N/A';

                // Format dates in Excel-friendly format with explicit date type
                $appointmentDate = $appointment->appointment_date ? 
                    '="' . \Carbon\Carbon::parse($appointment->appointment_date)->format('Y-m-d') . '"' : 'N/A';
                $appointmentTime = $appointment->appointment_time ? 
                    '="' . date('H:i', strtotime($appointment->appointment_time)) . '"' : 'N/A';
                $createdAt = $appointment->created_at ? 
                    '="' . $appointment->created_at->format('Y-m-d H:i') . '"' : 'N/A';
                $updatedAt = $appointment->updated_at ? 
                    '="' . $appointment->updated_at->format('Y-m-d H:i') . '"' : 'N/A';

                $row = [
                    'APT-' . $appointment->id,
                    $patientName,
                    $doctorName,
                    $appointmentDate,
                    $appointmentTime,
                    ucfirst($appointment->status),
                    ucfirst($appointment->treatment_type),
                    $appointment->treatment_type,
                    $appointment->notes,
                    $createdAt,
                    $updatedAt,
                ];

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function toggleBlock(Request $request, Appointment $appointment)
    {
        $newStatus = $request->input('status');

        $allowedTransitions = [
            'pending' => ['confirmed', 'canceled'],
            'confirmed' => ['completed', 'canceled'],
            'completed' => [],
            'canceled' => [],
        ];

        if (!in_array($newStatus, $allowedTransitions[$appointment->status] ?? [])) {
            return redirect()->back()->with([
                'notification' => 'Invalid status transition.',
                'status' => NotificationStatus::ERROR->value
            ]);
        }

        $oldStatus = $appointment->status;
        $appointment->update(['status' => $newStatus]);

        // Send email notifications based on status change
        try {
            $emailService = app(EmailService::class);
            
            switch ($newStatus) {
                case 'completed':
                    $emailService->sendAppointmentNotification($appointment, 'completed');
                    break;
                case 'canceled':
                    $emailService->sendAppointmentNotification($appointment, 'cancelled');
                    break;
                case 'confirmed':
                    // Only send update notification if status actually changed
                    if ($oldStatus !== $newStatus) {
                        $emailService->sendAppointmentNotification($appointment, 'updated');
                    }
                    break;
            }
        } catch (\Exception $e) {
            Log::error('Failed to send appointment status change email: ' . $e->getMessage());
        }

        return redirect()->back()->with([
            'notification' => 'Appointment status updated successfully.',
            'status' => NotificationStatus::SUCCESS->value
        ]);
    }

    /**
     * Show the form for creating a new appointment.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $patients = User::where('role', 'user')->get();
        $doctors = User::where('role', 'specialist')
            ->where('is_block', false)
            ->with(['detail.speciality'])
            ->get()
            ->map(function($doctor) {
                $doctor->speciality = $doctor->detail->speciality ?? (object)['title' => 'General'];
                return $doctor;
            });

        return view('admin.appointments.form', compact('patients', 'doctors'));
    }

    /**
     * Show the form for editing the specified appointment.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function edit(Appointment $appointment)
    {
        $patients = User::where('role', 'user')->get();
        $doctors = User::where('role', 'specialist')
            ->where('is_block', false)
            ->with(['detail.speciality'])
            ->get()
            ->map(function($doctor) {
                $doctor->speciality = $doctor->detail->speciality ?? (object)['title' => 'General'];
                return $doctor;
            });

        return view('admin.appointments.form', compact('appointment', 'patients', 'doctors'));
    }

    /**
     * Store a newly created appointment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'treatment_type' => 'required|in:general,OPD',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            // Check for existing appointment at the same time
            $existingAppointment = Appointment::where('doctor_id', $validated['doctor_id'])
                ->where('appointment_date', $validated['appointment_date'])
                ->where('appointment_time', $validated['appointment_time'])
                ->where('status', '!=', 'cancelled')
                ->first();

            if ($existingAppointment) {
                return response()->json([
                    'message' => 'The selected time slot is already booked.',
                    'errors' => ['appointment_time' => ['The selected time slot is already booked.']]
                ], 422);
            }

            $appointment = Appointment::create($validated);

            // Send email notifications
            try {
                $emailService = app(\App\Services\EmailService::class);
                $emailService->sendAppointmentNotification($appointment, 'created');
            } catch (\Exception $e) {
                Log::error('Failed to send appointment creation email: ' . $e->getMessage());
            }

            return response()->json([
                'message' => 'Appointment created successfully.',
                'data' => $appointment
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error creating appointment.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified appointment in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i',
            'status' => 'required|in:pending,confirmed,completed,cancelled',
            'treatment_type' => 'required|in:general,OPD',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            // Check for existing appointment at the same time (excluding current appointment)
            $existingAppointment = Appointment::where('doctor_id', $validated['doctor_id'])
                ->where('appointment_date', $validated['appointment_date'])
                ->where('appointment_time', $validated['appointment_time'])
                ->where('id', '!=', $appointment->id)
                ->where('status', '!=', 'cancelled')
                ->first();

            if ($existingAppointment) {
                return response()->json([
                    'message' => 'The selected time slot is already booked.',
                    'errors' => ['appointment_time' => ['The selected time slot is already booked.']]
                ], 422);
            }

            $appointment->update($validated);

            // Send email notifications
            try {
                $emailService = app(\App\Services\EmailService::class);
                $emailService->sendAppointmentNotification($appointment, 'updated');
            } catch (\Exception $e) {
                Log::error('Failed to send appointment update email: ' . $e->getMessage());
            }

            return response()->json([
                'message' => 'Appointment updated successfully.',
                'data' => $appointment
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error updating appointment.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Remove the specified appointment from storage.
     *
     * @param  \App\Models\Appointment  $appointment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appointment $appointment)
    {
        try {
            // Check if the appointment can be deleted
            if (in_array($appointment->status, [Appointment::STATUS_COMPLETED, Appointment::STATUS_CANCELLED])) {
                // For completed or cancelled appointments, we can delete them
                $appointment->delete();
                $message = 'Appointment has been deleted successfully.';
            } else {
                // For other statuses, we'll just cancel them instead of deleting
                $appointment->status = Appointment::STATUS_CANCELLED;
                $appointment->save();
                $message = 'Appointment has been cancelled successfully.';
            }
            
            return response()->json([
                'success' => true,
                'message' => $message,
                'reload' => true
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in AppointmentController@destroy: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to process appointment. ' . $e->getMessage()
            ], 500);
        }
    }
}
