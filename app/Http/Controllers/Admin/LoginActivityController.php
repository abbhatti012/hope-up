<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginActivity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoginActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = LoginActivity::with('user')
            ->orderBy('created_at', 'desc');

        // Filter by user type if specified
        if ($request->filled('user_type')) {
            $userType = strtolower($request->user_type);
            $query->whereHas('user', function ($q) use ($userType) {
                $q->whereRaw('LOWER(role) = ?', [$userType]);
            });
        }

        // Filter by status if specified
        if ($request->filled('status')) {
            $query->whereRaw('LOWER(status) = ?', [strtolower($request->status)]);
        }

        // Filter by date range if specified
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $activities = $query->paginate(50);

        return view('admin.login_activities.index', compact('activities'));
    }

    public function userActivities($userId)
    {
        $user = User::findOrFail($userId);
        $activities = LoginActivity::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.login_activities.user_activities', compact('user', 'activities'));
    }

    public function export(Request $request)
    {
        $query = LoginActivity::with('user')
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->has('user_type')) {
            $userType = $request->user_type;
            $query->whereHas('user', function ($q) use ($userType) {
                $q->where('role', $userType);
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $activities = $query->get();

        $filename = 'login_activities_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($activities) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, ['User', 'Email', 'Role', 'IP Address', 'User Agent', 'Status', 'Date']);
            
            // Add data
            foreach ($activities as $activity) {
                $user = $activity->user;
                fputcsv($file, [
                    $user ? $user->first_name . ' ' . $user->last_name : 'Unknown',
                    $user ? $user->email : 'Unknown',
                    $user ? $user->role : 'Unknown',
                    $activity->ip_address,
                    $activity->user_agent,
                    $activity->status,
                    $activity->created_at->format('Y-m-d H:i:s')
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
} 