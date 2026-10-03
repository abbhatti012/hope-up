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
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'event' => 'required|string'
        ]);

        $schedule = Schedule::updateOrCreate(
            ['id' => $request->id],
            [
                'user_id' => $request->user_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'event' => $request->event
            ]
        );

        return response()->json(['success' => true, 'schedule' => $schedule]);
    }

    public function index()
    {
        $specialists = User::where('role', 'specialist')->get();
        
        $schedules = Schedule::with('user')
                            ->where('end_date', '>=', now()->subDays(30)->toDateString())
                            ->orderBy('start_date')
                            ->orderBy('start_time')
                            ->get();
                            
        return view('admin.schedules.listing', compact('schedules', 'specialists'));
    }
    
    /**
     * Get schedules for the calendar
     */
    public function list(Request $request)
    {
        $start = $request->start;
        $end = $request->end;
        $doctorId = $request->doctor_id;
        
        // Parse the start and end dates
        $startDate = \Carbon\Carbon::parse($start)->format('Y-m-d');
        $endDate = \Carbon\Carbon::parse($end)->format('Y-m-d');
        
        $query = Schedule::with('user')
            ->when($doctorId, function($q) use ($doctorId) {
                return $q->where('user_id', $doctorId);
            });
            
        $schedules = $query->get()
            ->map(function($schedule) {
                $isMultiDay = $schedule->end_date && $schedule->end_date != $schedule->start_date;
                
                return [
                    'id' => $schedule->id,
                    'title' => $schedule->user->name . ' - ' . $schedule->event,
                    'start' => $schedule->start_date . 'T' . $schedule->start_time,
                    'end' => $isMultiDay 
                        ? $schedule->end_date . 'T23:59:59' 
                        : $schedule->start_date . 'T' . $schedule->end_time,
                    'allDay' => $isMultiDay,
                    'color' => '#3a87ad',
                    'textColor' => '#fff',
                    'borderColor' => '#2c6a8a',
                    'className' => $isMultiDay ? 'fc-event-multiday' : '',
                    'doctor_id' => $schedule->user_id,
                    'event' => $schedule->event,
                    'notes' => $schedule->notes,
                ];
            });

        return response()->json($schedules);
    }


    public function destroy(Request $request)
    {
        $schedule = Schedule::find($request->id);

        if ($schedule) {
            $schedule->delete();
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Schedule not found']);
    }
}
