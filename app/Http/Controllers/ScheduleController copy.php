<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Schedule;
use Illuminate\Http\Request;
use App\Enums\NotificationStatus;

class ScheduleController extends Controller
{
    public function create()
    {
        $specialists = User::where('role', 'specialist')->get();
        return view('admin.schedules.create', compact('specialists'));
    }

    public function storeOrUpdate(Request $request)
    {
        $rules = [
            'user_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'event' => 'required|string|max:255',
            'is_recurring' => 'sometimes|boolean',
            'notes' => 'nullable|string',
        ];

        // Add recurring days validation if is_recurring is true
        if ($request->is_recurring) {
            $rules['recurring_days'] = [
                'required',
                'array',
                function ($attribute, $value, $fail) {
                    foreach ($value as $day) {
                        if (!in_array((string)$day, ['0', '1', '2', '3', '4', '5', '6'])) {
                            $fail('The selected '.$attribute.' contains an invalid day.');
                        }
                    }
                }
            ];
        }

        $validated = $request->validate($rules);

        // Set default end_date to be same as date if not provided
        if (!isset($validated['end_date'])) {
            $validated['end_date'] = $validated['date'];
        }

        // Set default event title if not provided
        if (empty($validated['event'])) {
            $user = User::find($validated['user_id']);
            $validated['event'] = 'Appointment with ' . $user->first_name . ' ' . $user->last_name;
        }

        // Start a database transaction
        return \DB::transaction(function () use ($validated, $request) {
            $isRecurring = $validated['is_recurring'] ?? false;
            $recurringDays = $isRecurring && !empty($validated['recurring_days']) ? 
                (is_array($validated['recurring_days']) ? $validated['recurring_days'] : explode(',', $validated['recurring_days'])) : [];
            
            $startDate = new \DateTime($validated['date']);
            $endDate = new \DateTime($validated['end_date'] ?? $validated['date']);
            
            // If no end date is provided, default to 3 months from start date
            if (!isset($validated['end_date'])) {
                $endDate = (clone $startDate)->modify('+3 months');
            }
            
            // If this is an update, delete existing occurrences first
            if ($request->id) {
                $existing = Schedule::findOrFail($request->id);
                if ($existing->isParent()) {
                    // If it's a parent, delete all children
                    Schedule::where('parent_id', $existing->id)->delete();
                } elseif ($existing->parent_id) {
                    // If it's a child, just delete this one
                    $existing->delete();
                }
            }
            
            $schedules = [];
            
            // Create the parent schedule if recurring
            if ($isRecurring && !empty($recurringDays)) {
                $parent = Schedule::create([
                    'user_id' => $validated['user_id'],
                    'date' => $validated['date'],
                    'end_date' => $endDate->format('Y-m-d'),
                    'start_time' => $validated['start_time'],
                    'end_time' => $validated['end_time'],
                    'event' => $validated['event'],
                    'notes' => $validated['notes'] ?? null,
                    'is_recurring' => true,
                    'recurring_days' => json_encode(array_map('strtolower', $recurringDays))
                ]);
                
                // Generate all occurrences between start and end dates
                $current = clone $startDate;
                $endDateObj = new \DateTime($endDate->format('Y-m-d'));
                while ($current <= $endDateObj) {
                    $dayOfWeek = strtolower($current->format('l'));
                    
                    if (in_array($dayOfWeek, $recurringDays) && $current->format('Y-m-d') !== $startDate->format('Y-m-d')) {
                        $schedules[] = [
                            'parent_id' => $parent->id,
                            'user_id' => $validated['user_id'],
                            'date' => $current->format('Y-m-d'),
                            'end_date' => $current->format('Y-m-d'),
                            'start_time' => $validated['start_time'],
                            'end_time' => $validated['end_time'],
                            'event' => $validated['event'],
                            'notes' => $validated['notes'] ?? null,
                            'is_recurring' => false,
                            'created_at' => now(),
                            'updated_at' => now()
                        ];
                    }
                    $current->modify('+1 day');
                }
                
                // Insert all occurrences in one query for better performance
                if (!empty($schedules)) {
                    Schedule::insert($schedules);
                }
                
                return response()->json(['success' => true, 'schedule' => $parent]);
            } else {
                // Single event
                $schedule = Schedule::updateOrCreate(
                    ['id' => $request->id],
                    [
                        'user_id' => $validated['user_id'],
                        'date' => $validated['date'],
                        'end_date' => $validated['end_date'],
                        'start_time' => $validated['start_time'],
                        'end_time' => $validated['end_time'],
                        'event' => $validated['event'],
                        'notes' => $validated['notes'] ?? null,
                        'is_recurring' => false,
                        'recurring_days' => null,
                        'parent_id' => null
                    ]
                );
                
                return response()->json(['success' => true, 'schedule' => $schedule]);
            }
        });
    }

    public function index()
    {
        $specialists = User::where('role', 'specialist')->get();
        
        $schedules = Schedule::with('user')
                            ->where('date', '>=', now()->toDateString())
                            ->get();
                            
        return view('admin.schedules.listing', compact('schedules', 'specialists'));
    }
    
    public function list(Request $request)
    {
        $start = $request->get('start');
        $end = $request->get('end');
        
        // Get all schedules that either:
        // 1. Start within the date range, or
        // 2. End within the date range, or
        // 3. Span across the date range
        $schedules = Schedule::with('user')
            ->where(function($query) use ($start, $end) {
                // Events that start within the range
                $query->whereBetween('date', [$start, $end])
                      // Or events that end within the range
                      ->orWhereBetween('end_date', [$start, $end])
                      // Or events that span across the range
                      ->orWhere(function($q) use ($start, $end) {
                          $q->where('date', '<=', $start)
                            ->where('end_date', '>=', $end);
                      });
            })
            ->where(function($query) {
                // Either it's a parent with children, or it's a standalone event
                $query->where('is_recurring', true)
                      ->orWhereNull('parent_id');
            })
            ->get();
            
        // Also get parent schedules that might have children in our date range
        $parentSchedules = Schedule::with('user')
            ->where('is_recurring', true)
            ->where('date', '<=', $end)
            ->where('end_date', '>=', $start)
            ->whereNotIn('id', $schedules->pluck('id'))
            ->get();
            
        $schedules = $schedules->merge($parentSchedules);
        
        $events = [];
        
        foreach ($schedules as $schedule) {
            if ($schedule->is_recurring) {
                // For parent schedules, get all their children
                $children = $schedule->children()
                    ->whereBetween('date', [$start, $end])
                    ->get();
                    
                // Add the parent itself if it's in the date range
                if ($schedule->date >= $start && $schedule->date <= $end) {
                    $children->push($schedule);
                }
                
                foreach ($children as $child) {
                    $events[] = $this->formatEvent($child, $schedule->id);
                }
            } else {
                // Single event or child event
                $events[] = $this->formatEvent($schedule);
            }
        }
        
        return response()->json($events);
    }
    
    /**
     * Format a schedule as a calendar event
     */
    protected function formatEvent($schedule, $parentId = null)
    {
        // Format start and end times
        $startTime = $schedule->start_time;
        $endTime = $schedule->end_time;
        
        // If times are in datetime format, extract just the time part
        if (strpos($startTime, ' ') !== false) {
            $startTime = substr($startTime, 11, 5); // Get only HH:MM part
            $endTime = substr($endTime, 11, 5);
        }
        
        // Create event start and end datetimes
        $eventStart = $schedule->date->format('Y-m-d') . 'T' . $startTime;
        
        // For multi-day events, set the end date to the end of the last day
        if ($schedule->end_date && $schedule->end_date != $schedule->date) {
            $eventEnd = $schedule->end_date->format('Y-m-d') . 'T23:59:59';
            $allDay = true; // Make multi-day events show as all-day
        } else {
            $eventEnd = $schedule->date->format('Y-m-d') . 'T' . $endTime;
            $allDay = false;
        }
        
        $isRecurring = $schedule->is_recurring || $schedule->parent_id !== null;
        $originalId = $parentId ?? $schedule->id;
        
        return [
            'id' => $schedule->id,
            'title' => $schedule->user ? $schedule->user->name . ' - ' . $schedule->event : $schedule->event,
            'start' => $eventStart,
            'end' => $eventEnd,
            'end_date' => $schedule->end_date ? $schedule->end_date->format('Y-m-d') : $schedule->date->format('Y-m-d'),
            'doctor_id' => $schedule->user_id,
            'className' => 'fc-event-primary',
            'allDay' => $allDay ?? false,
            'notes' => $schedule->notes,
            'is_recurring' => $isRecurring,
            'recurring_days' => $isRecurring ? json_decode($schedule->recurring_days ?? '[]', true) : [],
            'original_id' => $originalId,
            'parent_id' => $schedule->parent_id
        ];
    }


    public function destroy(Request $request)
    {
        $schedule = Schedule::find($request->id);

        if (!$schedule) {
            return response()->json(['success' => false, 'message' => 'Schedule not found']);
        }

        try {
            // Start a database transaction
            return \DB::transaction(function () use ($schedule, $request) {
                $deleteAll = $request->delete_all ?? false;
                $isRecurring = $request->is_recurring ?? false;
                
                if ($isRecurring && $schedule->isParent()) {
                    // If it's a parent and we're deleting all occurrences
                    if ($deleteAll) {
                        // Delete all children first
                        $schedule->children()->delete();
                        // Then delete the parent
                        $schedule->delete();
                    } else {
                        // Just delete this specific occurrence
                        $schedule->delete();
                    }
                } elseif ($schedule->parent_id) {
                    // If it's a child event
                    if ($deleteAll) {
                        // Delete all future occurrences (this and all after)
                        $parent = $schedule->parent;
                        Schedule::where('parent_id', $parent->id)
                            ->where('date', '>=', $schedule->date)
                            ->delete();
                    } else {
                        // Just delete this occurrence
                        $schedule->delete();
                    }
                } else {
                    // Regular non-recurring event
                    $schedule->delete();
                }
                
                return response()->json(['success' => true]);
            });
            
        } catch (\Exception $e) {
            \Log::error('Error deleting schedule: ' . $e->getMessage());
            return response()->json([
                'success' => false, 
                'message' => 'Failed to delete schedule. Please try again.'
            ]);
        }
    }
}
