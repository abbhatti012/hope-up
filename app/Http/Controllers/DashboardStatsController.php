<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DashboardStatsController extends Controller
{
    /**
     * Get appointment statistics for the dashboard
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAppointmentStats($range = '1m')
    {
        // Generate a unique cache key based on the range and current date
        $cacheKey = "appointment_stats_{$range}_" . now()->format('YmdH');
        
        // Cache duration in minutes for each range
        $cacheDurations = [
            'today' => 5,     // 5 minutes for today's data (more frequent updates)
            '7d' => 15,       // 15 minutes for 7 days
            '1m' => 60,       // 1 hour for current month
            '3m' => 120,      // 2 hours for 3 months
            '6m' => 360,      // 6 hours for 6 months
            '1y' => 720,      // 12 hours for 1 year
        ];
        
        // Get cache duration for the current range (default to 60 minutes)
        $cacheDuration = $cacheDurations[$range] ?? 60;
        
        // Try to get cached data first
        $cachedData = Cache::get($cacheKey);
        
        if ($cachedData !== null) {
            // Add debug info for cache hit
            $cachedData['debug'] = [
                'cached' => true,
                'cache_key' => $cacheKey,
                'cache_expires_at' => now()->addMinutes($cacheDuration)->toDateTimeString(),
                'timestamp' => now()->toDateTimeString(),
                'range' => $range
            ];
            Log::info("Serving from cache", ['key' => $cacheKey, 'range' => $range]);
            return response()->json($cachedData);
        }
        
        // If not in cache, execute the query and log the execution
        Log::info("Cache miss, executing query", ['key' => $cacheKey, 'range' => $range]);
        
        // Execute the query and get the result
        $result = $this->fetchAppointmentData($range, $cacheKey, $cacheDuration);
        
        // Add debug info
        $result['debug'] = [
            'cached' => false,
            'cache_key' => $cacheKey,
            'query_executed_at' => now()->toDateTimeString(),
            'range' => $range,
            'query_time' => number_format(microtime(true) - (defined('LARAVEL_START') ? LARAVEL_START : microtime(true)), 4) . ' seconds'
        ];
        
        // Store in cache
        Cache::put($cacheKey, $result, $cacheDuration);
        
        return response()->json($result);
    }

    /**
     * Get treatment type statistics for the dashboard
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTreatmentTypeStats($range = '1m')
    {
        // Generate a unique cache key based on the range and current date
        $cacheKey = "treatment_type_stats_{$range}_" . now()->format('YmdH');
        
        // Cache duration in minutes for each range
        $cacheDurations = [
            'today' => 5,     // 5 minutes for today's data (more frequent updates)
            '7d' => 15,       // 15 minutes for 7 days
            '1m' => 60,       // 1 hour for current month
            '3m' => 120,      // 2 hours for 3 months
            '6m' => 360,      // 6 hours for 6 months
            '1y' => 720,      // 12 hours for 1 year
        ];
        
        // Get cache duration for the current range (default to 60 minutes)
        $cacheDuration = $cacheDurations[$range] ?? 60;
        
        // Try to get cached data first
        $cachedData = Cache::get($cacheKey);
        
        if ($cachedData !== null) {
            // Add debug info for cache hit
            $cachedData['debug'] = [
                'cached' => true,
                'cache_key' => $cacheKey,
                'cache_expires_at' => now()->addMinutes($cacheDuration)->toDateTimeString(),
                'timestamp' => now()->toDateTimeString(),
                'range' => $range
            ];
            Log::info("Serving treatment type stats from cache", ['key' => $cacheKey, 'range' => $range]);
            return response()->json($cachedData);
        }
        
        // If not in cache, execute the query and log the execution
        Log::info("Treatment type stats cache miss, executing query", ['key' => $cacheKey, 'range' => $range]);
        
        // Execute the query and get the result
        $result = $this->fetchTreatmentTypeData($range, $cacheKey, $cacheDuration);
        
        // Add debug info
        $result['debug'] = [
            'cached' => false,
            'cache_key' => $cacheKey,
            'query_executed_at' => now()->toDateTimeString(),
            'range' => $range,
            'query_time' => number_format(microtime(true) - (defined('LARAVEL_START') ? LARAVEL_START : microtime(true)), 4) . ' seconds'
        ];
        
        // Store in cache
        Cache::put($cacheKey, $result, $cacheDuration);
        
        return response()->json($result);
    }
    
    /**
     * Get patient and doctor registration stats for the dashboard
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserRoleStats($range = '1y')
    {
        $now = now();
        $categories = [];
        $groupBy = '';
        $startDate = null;
        $endDate = null;

        switch ($range) {
            case 'today':
                $startDate = $now->copy()->startOfDay();
                $endDate = $now->copy()->endOfDay();
                $groupBy = "HOUR(created_at)";
                for ($h = 0; $h < 24; $h++) {
                    $categories[] = sprintf('%02d:00', $h);
                }
                break;
            case '7d':
                $startDate = $now->copy()->subDays(6)->startOfDay();
                $endDate = $now->copy()->endOfDay();
                $groupBy = "DATE(created_at)";
                for ($i = 0; $i < 7; $i++) {
                    $categories[] = $startDate->copy()->addDays($i)->format('Y-m-d');
                }
                break;
            case '1m':
                $startDate = $now->copy()->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
                $groupBy = "YEARWEEK(created_at, 1)";
                $current = $startDate->copy();
                while ($current <= $endDate) {
                    $week = $current->format('o') . $current->weekOfYear;
                    $categories[] = $week;
                    $current->addWeek();
                }
                $categories = array_unique($categories);
                break;
            case '3m':
                $startDate = $now->copy()->subMonths(3)->startOfDay();
                $endDate = $now->copy()->endOfDay();
                $groupBy = "YEARWEEK(created_at, 1)";
                $current = $startDate->copy()->startOfWeek();
                $end = $endDate->copy()->endOfWeek();
                while ($current <= $end) {
                    $week = $current->format('o') . $current->weekOfYear;
                    $categories[] = $week;
                    $current->addWeek();
                }
                $categories = array_unique($categories);
                break;
            case '6m':
                $startDate = $now->copy()->subMonths(6)->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
                $groupBy = "DATE_FORMAT(created_at, '%Y-%m-01')";
                for ($i = 0; $i < 6; $i++) {
                    $categories[] = $startDate->copy()->addMonths($i)->format('Y-m-01');
                }
                break;
            case '1y':
            default:
                $startDate = $now->copy()->subYear()->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
                $groupBy = "DATE_FORMAT(created_at, '%Y-%m-01')";
                for ($i = 0; $i < 12; $i++) {
                    $categories[] = $startDate->copy()->addMonths($i)->format('Y-m-01');
                }
                break;
        }

        $users = DB::table('users')
            ->selectRaw("{$groupBy} as period, SUM(role = 'user') as patients, SUM(role = 'specialist') as doctors")
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('period')
            ->orderBy('period')
            ->get()
            ->keyBy('period');

        $series = [
            ['name' => 'Patients', 'data' => []],
            ['name' => 'Doctors', 'data' => []]
        ];

        foreach ($categories as $cat) {
            $row = $users->get($cat) ?? (object)['patients' => 0, 'doctors' => 0];
            $series[0]['data'][] = (int)$row->patients;
            $series[1]['data'][] = (int)$row->doctors;
        }

        // Format categories for display
        if ($range === '1m' || $range === '3m') {
            $categories = array_map(function($week) {
                $year = (int)substr($week, 0, 4);
                $weekNum = (int)substr($week, 4);
                $start = (new \DateTime())->setISODate($year, $weekNum, 1)->format('M j');
                $end = (new \DateTime())->setISODate($year, $weekNum, 7)->format('M j');
                return "W$weekNum: $start-$end";
            }, $categories);
        }
        if ($range === '7d') {
            $categories = array_map(function($date) {
                return date('D, M d', strtotime($date));
            }, $categories);
        }
        if ($range === '6m' || $range === '1y') {
            $categories = array_map(function($date) {
                return date('M Y', strtotime($date));
            }, $categories);
        }

        return response()->json([
            'categories' => $categories,
            'series' => $series
        ]);
    }
    
    /**
     * Get both patient gender and ex-militant stats for donut charts
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPatientGenderAndMilitantStats($range = '1m')
    {
        $now = now();
        switch ($range) {
            case 'today':
                $startDate = $now->copy()->startOfDay();
                $endDate = $now->copy()->endOfDay();
                break;
            case '7d':
                $startDate = $now->copy()->subDays(6)->startOfDay();
                $endDate = $now->copy()->endOfDay();
                break;
            case '1m':
            default:
                $startDate = $now->copy()->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
                break;
            case '3m':
                $startDate = $now->copy()->subMonths(3)->startOfDay();
                $endDate = $now->copy()->endOfDay();
                break;
            case '6m':
                $startDate = $now->copy()->subMonths(6)->startOfDay();
                $endDate = $now->copy()->endOfDay();
                break;
            case '1y':
                $startDate = $now->copy()->subYear()->startOfDay();
                $endDate = $now->copy()->endOfDay();
                break;
        }

        $stats = DB::table('patient_details')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('
                SUM(CASE WHEN gender = "male" THEN 1 ELSE 0 END) as male,
                SUM(CASE WHEN gender = "female" THEN 1 ELSE 0 END) as female,
                SUM(CASE WHEN gender = "kid" THEN 1 ELSE 0 END) as kid,
                SUM(CASE WHEN is_ex_military = 1 THEN 1 ELSE 0 END) as ex_militant,
                SUM(CASE WHEN is_ex_military = 0 THEN 1 ELSE 0 END) as not_ex_militant
            ')
            ->first();

        return response()->json([
            'gender' => [
                'labels' => ['Male', 'Female', 'Kids'],
                'series' => [
                    (int)($stats->male ?? 0),
                    (int)($stats->female ?? 0),
                    (int)($stats->kid ?? 0)
                ]
            ],
            'militant' => [
                'labels' => ['Ex-Militant', 'Not Ex-Militant'],
                'series' => [
                    (int)($stats->ex_militant ?? 0),
                    (int)($stats->not_ex_militant ?? 0)
                ]
            ]
        ]);
    }
    
    /**
     * Fetch appointment data from the database
     * 
     * @param string $range Date range for the query
     * @param string $cacheKey Cache key for storing results
     * @param int $cacheDuration Duration in minutes to cache the results
     * @return array Processed appointment data
     */
    private function fetchAppointmentData($range, $cacheKey, $cacheDuration)
    {
    
        $query = Appointment::query();
        $now = now();
        
        // Apply date range filter and set grouping
        switch ($range) {
            case 'today':
                // For today: group by all hours (0-23)
                $startDate = $now->copy()->startOfDay();
                $endDate = $now->copy()->endOfDay();
                
                // Generate all hours for the day
                $allHours = collect(range(0, 23))->mapWithKeys(function ($hour) {
                    return [$hour => ['time_period' => $hour, 'completed' => 0, 'pending' => 0, 'canceled' => 0, 'confirmed' => 0]];
                })->toArray();
                
                $query->whereBetween('created_at', [$startDate, $endDate]);
                $groupBy = "HOUR(created_at)";
                $orderBy = 'time_period';
                break;
                
            case '7d':
                // For 7 days: group by day (last 7 days including today)
                $startDate = $now->copy()->subDays(6)->startOfDay();
                $endDate = $now->copy()->endOfDay();
                
                // Generate all days for the range
                $allDays = collect();
                for ($i = 0; $i < 7; $i++) {
                    $date = $startDate->copy()->addDays($i);
                    $allDays[$date->format('Y-m-d')] = [
                        'time_period' => $date->format('Y-m-d'),
                        'completed' => 0,
                        'pending' => 0,
                        'canceled' => 0,
                        'confirmed' => 0
                    ];
                }
                
                $query->whereBetween('created_at', [$startDate, $endDate]);
                $groupBy = "DATE(created_at)";
                $orderBy = 'time_period';
                break;
            
            case '3m':
                // For 3 months: group by week
                $startDate = $now->copy()->subMonths(3)->startOfDay();
                $endDate = $now->endOfDay();
                
                $query->whereBetween('created_at', [$startDate, $endDate]);
                $groupBy = "YEARWEEK(created_at, 1)"; // Mode 1 for Monday as first day of week
                $orderBy = 'time_period';
                break;
                
            case '6m':
                // For 6 months: group by month
                $startDate = $now->copy()->subMonths(6)->startOfDay();
                $endDate = $now->endOfDay();
                
                $query->whereBetween('created_at', [$startDate, $endDate]);
                $groupBy = "DATE_FORMAT(created_at, '%Y-%m-01')";
                $orderBy = 'time_period';
                break;
                
            case '1y':
                // For 1 year: group by month
                $startDate = $now->copy()->subYear()->startOfDay();
                $endDate = $now->endOfDay();
                
                $query->whereBetween('created_at', [$startDate, $endDate]);
                $groupBy = "DATE_FORMAT(created_at, '%Y-%m-01')";
                $orderBy = 'time_period';
                break;
                
            case '1m':
            default:
                // For current month: group by all weeks in the month
                $startDate = $now->copy()->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
                
                // Get all weeks in the month
                $current = $startDate->copy();
                $weeksInMonth = [];
                
                while ($current <= $endDate) {
                    $weekNumber = $current->weekOfYear;
                    $year = $current->year;
                    $weeksInMonth["$year$weekNumber"] = [
                        'time_period' => "$year$weekNumber",
                        'completed' => 0,
                        'pending' => 0,
                        'canceled' => 0,
                        'confirmed' => 0
                    ];
                    $current->addWeek();
                }
                
                $query->whereBetween('created_at', [$startDate, $endDate]);
                $groupBy = "YEARWEEK(created_at, 1)"; // Mode 1 for Monday as first day of week
                $orderBy = 'time_period';
                break;
        }
        
        // Get appointment stats grouped by time period
        $appointments = $query->selectRaw("
                {$groupBy} as time_period,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'canceled' THEN 1 ELSE 0 END) as canceled,
                SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed
            ")
            ->groupBy('time_period')
            ->orderBy($orderBy)
            ->get()
            ->keyBy('time_period');
    
        // Prepare response data
        $categories = [];
        $series = [
            ['name' => 'Completed', 'data' => []],
            ['name' => 'Pending', 'data' => []],
            ['name' => 'Canceled', 'data' => []],
            ['name' => 'Confirmed', 'data' => []]
        ];
    
        // Get the appropriate time periods based on range
        $timePeriods = [];
        $now = now();
        
        switch ($range) {
            case 'today':
                // All hours for today (0-23)
                $timePeriods = range(0, 23);
                break;
                
            case '7d':
                // Last 7 days including today
                for ($i = 0; $i < 7; $i++) {
                    $date = $now->copy()->subDays(6 - $i);
                    $timePeriods[] = $date->format('Y-m-d');
                }
                break;
                
            case '1m':
                // All weeks in current month
                $current = $now->copy()->startOfMonth();
                $endOfMonth = $now->copy()->endOfMonth();
                
                while ($current <= $endOfMonth) {
                    $timePeriods[] = $current->format('o') . $current->weekOfYear;
                    $current->addWeek();
                }
                $timePeriods = array_unique($timePeriods);
                break;
                
            case '3m':
                // All weeks in last 3 months
                $start = $now->copy()->subMonths(3)->startOfWeek();
                $end = $now->copy()->endOfWeek();
                
                while ($start <= $end) {
                    $timePeriods[] = $start->format('o') . $start->weekOfYear;
                    $start->addWeek();
                }
                $timePeriods = array_unique($timePeriods);
                break;
                
            case '6m':
            case '1y':
                // All months in the range
                $months = $range === '6m' ? 6 : 12;
                $start = $now->copy()->subMonths($months - 1)->startOfMonth();
                
                for ($i = 0; $i < $months; $i++) {
                    $timePeriods[] = $start->copy()->addMonths($i)->format('Y-m-01');
                }
                break;
        }
    
        // Process each time period
        foreach ($timePeriods as $period) {
            $formattedDate = $this->formatDate($period, $range);
            $appointment = $appointments->get($period) ?? (object)[
                'completed' => 0,
                'pending' => 0,
                'canceled' => 0,
                'confirmed' => 0
            ];
            
            $categories[] = $formattedDate;
            $series[0]['data'][] = $appointment->completed;
            $series[1]['data'][] = $appointment->pending;
            $series[2]['data'][] = $appointment->canceled;
            $series[3]['data'][] = $appointment->confirmed;
        }
    
        // Cache the data
        $data = [
            'categories' => $categories,
            'series' => $series
        ];
        Cache::put($cacheKey, $data, $cacheDuration);
    
        return $data;
    }

    /**
     * Fetch treatment type data from the database
     * 
     * @param string $range Date range for the query
     * @param string $cacheKey Cache key for storing results
     * @param int $cacheDuration Duration in minutes to cache the results
     * @return array Processed treatment type data
     */
    private function fetchTreatmentTypeData($range, $cacheKey, $cacheDuration)
    {
        $query = Appointment::query();
        $now = now();
        
        // Apply date range filter and set grouping
        switch ($range) {
            case 'today':
                // For today: group by all hours (0-23)
                $startDate = $now->copy()->startOfDay();
                $endDate = $now->copy()->endOfDay();
                
                $query->whereBetween('created_at', [$startDate, $endDate]);
                $groupBy = "HOUR(created_at)";
                $orderBy = 'time_period';
                break;
                
            case '7d':
                // For 7 days: group by day (last 7 days including today)
                $startDate = $now->copy()->subDays(6)->startOfDay();
                $endDate = $now->copy()->endOfDay();
                
                $query->whereBetween('created_at', [$startDate, $endDate]);
                $groupBy = "DATE(created_at)";
                $orderBy = 'time_period';
                break;
            
            case '3m':
                // For 3 months: group by week
                $startDate = $now->copy()->subMonths(3)->startOfDay();
                $endDate = $now->endOfDay();
                
                $query->whereBetween('created_at', [$startDate, $endDate]);
                $groupBy = "YEARWEEK(created_at, 1)"; // Mode 1 for Monday as first day of week
                $orderBy = 'time_period';
                break;
                
            case '6m':
                // For 6 months: group by month
                $startDate = $now->copy()->subMonths(6)->startOfDay();
                $endDate = $now->endOfDay();
                
                $query->whereBetween('created_at', [$startDate, $endDate]);
                $groupBy = "DATE_FORMAT(created_at, '%Y-%m-01')";
                $orderBy = 'time_period';
                break;
                
            case '1y':
                // For 1 year: group by month
                $startDate = $now->copy()->subYear()->startOfDay();
                $endDate = $now->endOfDay();
                
                $query->whereBetween('created_at', [$startDate, $endDate]);
                $groupBy = "DATE_FORMAT(created_at, '%Y-%m-01')";
                $orderBy = 'time_period';
                break;
                
            case '1m':
            default:
                // For current month: group by all weeks in the month
                $startDate = $now->copy()->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
                
                $query->whereBetween('created_at', [$startDate, $endDate]);
                $groupBy = "YEARWEEK(created_at, 1)"; // Mode 1 for Monday as first day of week
                $orderBy = 'time_period';
                break;
        }
        
        // Get treatment type stats grouped by time period
        $appointments = $query->selectRaw("
                {$groupBy} as time_period,
                SUM(CASE WHEN treatment_type = 'general' THEN 1 ELSE 0 END) as general,
                SUM(CASE WHEN treatment_type = 'OPD' THEN 1 ELSE 0 END) as opd
            ")
            ->groupBy('time_period')
            ->orderBy($orderBy)
            ->get()
            ->keyBy('time_period');
    
        // Prepare response data
        $categories = [];
        $series = [
            ['name' => 'General', 'data' => []],
            ['name' => 'OPD', 'data' => []]
        ];
    
        // Get the appropriate time periods based on range
        $timePeriods = [];
        $now = now();
        
        switch ($range) {
            case 'today':
                // All hours for today (0-23)
                $timePeriods = range(0, 23);
                break;
                
            case '7d':
                // Last 7 days including today
                for ($i = 0; $i < 7; $i++) {
                    $date = $now->copy()->subDays(6 - $i);
                    $timePeriods[] = $date->format('Y-m-d');
                }
                break;
                
            case '1m':
                // All weeks in current month
                $current = $now->copy()->startOfMonth();
                $endOfMonth = $now->copy()->endOfMonth();
                
                while ($current <= $endOfMonth) {
                    $timePeriods[] = $current->format('o') . $current->weekOfYear;
                    $current->addWeek();
                }
                $timePeriods = array_unique($timePeriods);
                break;
                
            case '3m':
                // All weeks in last 3 months
                $start = $now->copy()->subMonths(3)->startOfWeek();
                $end = $now->copy()->endOfWeek();
                
                while ($start <= $end) {
                    $timePeriods[] = $start->format('o') . $start->weekOfYear;
                    $start->addWeek();
                }
                $timePeriods = array_unique($timePeriods);
                break;
                
            case '6m':
            case '1y':
                // All months in the range
                $months = $range === '6m' ? 6 : 12;
                $start = $now->copy()->subMonths($months - 1)->startOfMonth();
                
                for ($i = 0; $i < $months; $i++) {
                    $timePeriods[] = $start->copy()->addMonths($i)->format('Y-m-01');
                }
                break;
        }
    
        // Process each time period
        foreach ($timePeriods as $period) {
            $formattedDate = $this->formatDate($period, $range);
            $appointment = $appointments->get($period) ?? (object)[
                'general' => 0,
                'opd' => 0
            ];
            
            $categories[] = $formattedDate;
            $series[0]['data'][] = (int) $appointment->general;
            $series[1]['data'][] = (int) $appointment->opd;
        }
    
        // Cache the data
        $data = [
            'categories' => $categories,
            'series' => $series
        ];
        Cache::put($cacheKey, $data, $cacheDuration);
    
        return $data;
    }
    
    /**
     * Format date based on range
     * 
     * @param mixed $value
     * @param string $range
     * @return string
     */
    private function formatDate($value, $range)
    {
        try {
            switch ($range) {
                case 'today':
                    // Format hour as 'HH:00' (e.g., '08:00', '14:00')
                    return sprintf('%02d:00', $value);
                    
                case '7d':
                    // Format as 'Day, MMM DD' (e.g., 'Mon, Jul 04')
                    return date('D, M d', strtotime($value));
                    
                case '1m':
                    // For current month: show week range (e.g., 'Jul 1 - 7')
                    $week = (int)substr($value, 4);
                    $year = (int)substr($value, 0, 4);
                    
                    $startDate = (new \DateTime())
                        ->setISODate($year, $week, 1) // Monday of the week
                        ->format('M j');
                        
                    $endDate = (new \DateTime())
                        ->setISODate($year, $week, 7) // Sunday of the week
                        ->format('M j');
                        
                    return "$startDate - $endDate";
                    
                case '3m':
                    // For 3 months: show week number and date range (e.g., 'W27: Jul 1-7')
                    $week = (int)substr($value, 4);
                    $year = (int)substr($value, 0, 4);
                    
                    $startDate = (new \DateTime())
                        ->setISODate($year, $week, 1) // Monday of the week
                        ->format('M j');
                        
                    $endDate = (new \DateTime())
                        ->setISODate($year, $week, 7) // Sunday of the week
                        ->format('M j');
                        
                    return "W$week: $startDate-$endDate";
                    
                case '6m':
                case '1y':
                    // For 6 months and 1 year: show month and year (e.g., 'Jul 2023')
                    return date('M Y', strtotime($value . '-01'));
                    
                default:
                    return $value;
            }
        } catch (\Exception $e) {
            Log::error("Error formatting date: " . $e->getMessage());
            return $value;
        }
    }
}
