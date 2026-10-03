<?php

namespace App\Http\Controllers;

use App\Enums\Roles;
use App\Models\User;
use App\Models\Content;
use App\Models\Schedule;
use App\Models\Speciality;
use App\Models\Appointment;
use App\Models\Transaction;
use App\Models\DoctorReview;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Enums\NotificationStatus;
use App\Helpers\PaginationHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use App\Services\SubscriptionService;
use Illuminate\Support\Facades\Storage;
use App\Models\WebSetting;
use Carbon\Carbon;

class AdminController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    
    public function index()
    {
        try {
            if (auth()->user()->role == Roles::ADMIN->value || auth()->user()->role == Roles::SUPERADMIN->value) {
                // Initialize default values
                $dashboardData = [
                    'totalPatients' => 0,
                    'totalDoctors' => 0,
                    'totalAdmins' => 0,
                    'totalAppointments' => 0,
                    'pendingAppointments' => 0,
                    'completedAppointments' => 0,
                    'canceledAppointments' => 0,
                    'totalEarnings' => 0,
                    'todayPatients' => 0,
                    'todayAppointments' => 0,
                    'pendingTodayAppointments' => 0,
                ];

                try {
                    // Get all user counts in one query using groupBy
                    $userCounts = User::selectRaw('role, COUNT(*) as count')
                        ->groupBy('role')
                        ->pluck('count', 'role')
                        ->toArray();
                    
                    $dashboardData['totalPatients'] = $userCounts['user'] ?? 0;
                    $dashboardData['totalDoctors'] = $userCounts['specialist'] ?? 0;
                    $dashboardData['totalAdmins'] = $userCounts['admin'] ?? 0;
                    
                } catch (\Exception $e) {
                    Log::error('Error fetching user counts: ' . $e->getMessage());
                    // Continue with default values (0)
                }

                try {
                    // Get all appointment counts by status in one query
                    $appointmentCounts = Appointment::selectRaw('status, COUNT(*) as count')
                        ->groupBy('status')
                        ->pluck('count', 'status')
                        ->toArray();
                    
                    // Get total appointments count
                    $totalAppointments = array_sum($appointmentCounts);
                    
                    $dashboardData['totalAppointments'] = $totalAppointments;
                    $dashboardData['pendingAppointments'] = $appointmentCounts['pending'] ?? 0;
                    $dashboardData['completedAppointments'] = $appointmentCounts['completed'] ?? 0;
                    $dashboardData['canceledAppointments'] = $appointmentCounts['canceled'] ?? 0;
                    
                } catch (\Exception $e) {
                    Log::error('Error fetching appointment counts: ' . $e->getMessage());
                    // Continue with default values (0)
                }

                try {
                    // Get total earnings from completed transactions
                    $totalEarnings = Transaction::where('payment_status', 'completed')->sum('total_amount');
                    $dashboardData['totalEarnings'] = $totalEarnings ?? 0;
                    
                } catch (\Exception $e) {
                    Log::error('Error fetching transaction earnings: ' . $e->getMessage());
                    // Continue with default value (0)
                }

                try {
                    // Get today's counts in optimized queries
                    $todayUserCounts = User::selectRaw('role, COUNT(*) as count')
                        ->whereDate('created_at', today())
                        ->groupBy('role')
                        ->pluck('count', 'role')
                        ->toArray();
                    
                    $dashboardData['todayPatients'] = $todayUserCounts['user'] ?? 0;
                    
                } catch (\Exception $e) {
                    Log::error('Error fetching today\'s user counts: ' . $e->getMessage());
                    // Continue with default value (0)
                }

                try {
                    $todayAppointmentCounts = Appointment::selectRaw('status, COUNT(*) as count')
                        ->whereDate('created_at', today())
                        ->groupBy('status')
                        ->pluck('count', 'status')
                        ->toArray();
                    
                    $dashboardData['todayAppointments'] = array_sum($todayAppointmentCounts);
                    $dashboardData['pendingTodayAppointments'] = $todayAppointmentCounts['pending'] ?? 0;
                    
                } catch (\Exception $e) {
                    Log::error('Error fetching today\'s appointment counts: ' . $e->getMessage());
                    // Continue with default values (0)
                }

                return view('admin.dashboard', compact('dashboardData'));
            }
            
            return redirect(url('/'));
            
        } catch (\Exception $e) {
            Log::error('Critical error in admin dashboard: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            // Return a fallback dashboard with error message
            $dashboardData = [
                'totalPatients' => 0,
                'totalDoctors' => 0,
                'totalAdmins' => 0,
                'totalAppointments' => 0,
                'pendingAppointments' => 0,
                'completedAppointments' => 0,
                'canceledAppointments' => 0,
                'totalEarnings' => 0,
                'todayPatients' => 0,
                'todayAppointments' => 0,
                'pendingTodayAppointments' => 0,
                'error' => 'Unable to load dashboard data. Please try again later.'
            ];
            
            return view('admin.dashboard', compact('dashboardData'));
        }
    }

    public function manage_doctors(Request $request) {
        $searchFields = ['first_name', 'last_name', 'email'];

        $users = PaginationHelper::applyDynamicFilters(
            User::with(['doctorDetail'])->where('role', 'specialist'),
            $request, 
            $searchFields
        );

        return view('admin.users.listing', compact('users'));
    }

    public function doctor_detail(Request $request, $id) {
        // Get doctor with details and speciality
        $user = User::with([
            'doctorDetail.speciality' => function($query) {
                $query->select('id', 'title');
            }
        ])->where('role', 'specialist')
          ->where('id', $id)
          ->firstOrFail();

        // Get approved reviews with reviewer information and calculate stats in one query
        $reviewsQuery = DoctorReview::with(['reviewer' => function($query) {
                $query->select('id', 'first_name', 'last_name', 'profile_photo');
            }])
            ->where('doctor_id', $id)
            ->where('is_approved', true);

        $reviews = $reviewsQuery->orderBy('created_at', 'desc')->limit(5)->get();
        
        // Get review stats in one query
        $reviewStats = DoctorReview::where('doctor_id', $id)
            ->where('is_approved', true)
            ->selectRaw('COUNT(*) as total_reviews, AVG(rating) as average_rating')
            ->first();

        $averageRating = $reviewStats->average_rating ?? 0;
        $reviewCount = $reviewStats->total_reviews ?? 0;

        // Get upcoming schedule for the next 7 days
        $startDate = now();
        $endDate = now()->addDays(7);
        
        $schedules = Schedule::where('user_id', $id)
            ->whereDate('start_date', '>=', $startDate)
            ->whereDate('end_date', '<=', $endDate)
            ->orderBy('start_date')
            ->orderBy('start_time')
            ->get()
            ->groupBy(function($schedule) {
                return strtolower($schedule->start_date->format('D'));
            });

        // Format schedule data for the view
        $formattedSchedules = [];
        $days = ['mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun'];
        
        foreach ($days as $day) {
            $formattedSchedules[$day] = 'NA';
            if (isset($schedules[$day])) {
                $daySchedules = $schedules[$day];
                $timeSlots = [];
                foreach ($daySchedules as $schedule) {
                    $timeSlots[] = $schedule->start_time->format('g:i A') . ' - ' . $schedule->end_time->format('g:i A');
                }
                $formattedSchedules[$day] = implode(', ', $timeSlots);
            }
        }

        // Get all appointment stats in one query
        $appointmentStats = Appointment::where('doctor_id', $id)
            ->selectRaw('
                COUNT(*) as total_appointments,
                COUNT(CASE WHEN status = "completed" THEN 1 END) as completed_appointments,
                COUNT(CASE WHEN status = "pending" THEN 1 END) as pending_appointments,
                COUNT(DISTINCT patient_id) as unique_patients
            ')
            ->first();

        // Get all transaction stats in one query
        $transactionStats = Transaction::where('doctor_id', $id)
            ->selectRaw('
                COUNT(*) as total_transactions,
                COUNT(CASE WHEN payment_status = "completed" THEN 1 END) as completed_transactions,
                SUM(CASE WHEN payment_status = "completed" THEN doctor_amount ELSE 0 END) as total_earnings
            ')
            ->first();

        // Get recent appointments with patient data
        $appointments = Appointment::with(['patient' => function($query) {
                $query->select('id', 'first_name', 'last_name', 'email', 'profile_photo');
            }])
            ->where('doctor_id', $id)
            ->orderBy('appointment_date', 'desc')
            ->limit(10)
            ->get();

        // Get recent transactions with patient data
        $transactions = Transaction::with(['patient' => function($query) {
                $query->select('id', 'first_name', 'last_name', 'email', 'profile_photo');
            }])
            ->where('doctor_id', $id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.users.doctor_detail', [
            'user' => $user,
            'reviews' => $reviews,
            'averageRating' => $averageRating,
            'reviewCount' => $reviewCount,
            'patientCount' => $appointmentStats->unique_patients ?? 0,
            'schedules' => $formattedSchedules,
            'appointments' => $appointments,
            'transactions' => $transactions,
            'totalAppointments' => $appointmentStats->total_appointments ?? 0,
            'completedAppointments' => $appointmentStats->completed_appointments ?? 0,
            'pendingAppointments' => $appointmentStats->pending_appointments ?? 0,
            'totalEarnings' => $transactionStats->total_earnings ?? 0,
            'totalTransactions' => $transactionStats->total_transactions ?? 0,
            'completedTransactions' => $transactionStats->completed_transactions ?? 0
        ]);
    }

    public function block_users()
    {
        $id = request('id');
        $user = User::where('id', $id)->first();
        $user->is_block = 1;
        if ($user->save()) {
            return response()->json(true);
        }
    }
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,user,specialist',
        ]);

        $user->role = $request->role;
        $user->save();

        return redirect()->back()->with([
            'notification' => 'User role updated successfully.',
            'status' => NotificationStatus::SUCCESS->value
        ]);
    }

    public function toggleBlock(Request $request, User $user)
    {
        $user->is_block = !$user->is_block;
        $user->save();

        return redirect()->back()->with([
            'notification' => 'User block status updated.',
            'status' => NotificationStatus::SUCCESS->value
        ]);
    }

    public function create()
    {
        $specialities = Speciality::where('is_active',1)->get();
        return view('admin.users.create_user', compact('specialities'));
    }


    public function store(Request $request)
    {
        $result = $this->userService->registerUser($request);

        if (!$result['success']) {
            return redirect()->back()->with([
                'notification' => 'An error occurred while creating the user: ' . $result['error'],
                'status' => NotificationStatus::DANGER->value
            ])->withInput();
        }

        return redirect()->back()->with([
            'notification' => 'User created successfully.',
            'status' => NotificationStatus::SUCCESS->value
        ]);
    }

    public function update(Request $request, User $user)
    {
        $result = $this->userService->updateUser($request, $user->id);

        if (!$result['success']) {
            return redirect()->back()->with([
                'notification' => 'An error occurred while updating the user: ' . $result['error'],
                'status' => NotificationStatus::DANGER->value
            ])->withInput();
        }

        return redirect()->back()->with([
            'notification' => 'User information updated successfully.',
            'status' => NotificationStatus::SUCCESS->value
        ]);
    }


    public function edit(User $user)
    {
        $specialities = Speciality::where('is_active',1)->get();
        return view('admin.users.create_user', compact('user', 'specialities'));
    }

    // Manage Mental Health Content
    public function manage_mhc()
    {
        $contents = Content::get();
        return view('admin.mhc.listing', compact('contents'));
    }
    public function content_create()
    {
        return view('admin.mhc.create');
    }
    public function content_store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        try {
            $filePath = null;
            if ($request->hasFile('content')) {
                $file = $request->file('content');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = 'contents/' . date('Y/m');
                
                // Create directory if it doesn't exist
                if (!file_exists(public_path($path))) {
                    mkdir(public_path($path), 0777, true);
                }
                
                $file->move(public_path($path), $fileName);
                $filePath = $path . '/' . $fileName;
            }
            
            $content = new Content();
            $content->title = $request->title;
            $content->description = $request->description;
            $content->content = $filePath;
            $content->save();

            return redirect()->route('manage-mhc')->with([
                'notification' => 'Certificate uploaded successfully.',
                'status' => NotificationStatus::SUCCESS->value
            ]);
            
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'notification' => 'Error uploading certificate: ' . $e->getMessage(),
                'status' => NotificationStatus::DANGER->value
            ])->withInput();
        }
    }
    public function content_edit(Content $content)
    {
        return view('admin.mhc.create', compact('content'));
    }
    public function content_update(Request $request, Content $content)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'sometimes|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max, optional for update
        ]);

        try {
            if ($request->hasFile('content')) {
                // Delete old file if exists
                if ($content->content && file_exists(public_path($content->content))) {
                    unlink(public_path($content->content));
                }
                
                $file = $request->file('content');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $path = 'contents/' . date('Y/m');
                
                // Create directory if it doesn't exist
                if (!file_exists(public_path($path))) {
                    mkdir(public_path($path), 0777, true);
                }
                
                $file->move(public_path($path), $fileName);
                $content->content = $path . '/' . $fileName;
            }
            
            $content->title = $request->title;
            $content->description = $request->description;
            $content->save();

            return redirect()->route('manage-mhc')->with([
                'notification' => 'Certificate updated successfully.',
                'status' => NotificationStatus::SUCCESS->value
            ]);
            
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'notification' => 'Error updating certificate: ' . $e->getMessage(),
                'status' => NotificationStatus::DANGER->value
            ])->withInput();
        }
    }

    public function content_destroy($id)
    {
        $schedule = Content::find($id);

        if ($schedule) {
            $schedule->delete();
            return redirect()->back()->with([
                'notification' => 'Content Deleted successfully.',
                'status' => NotificationStatus::SUCCESS->value
            ]);
        }

        return redirect()->back()->with([
            'notification' => 'Content not found.',
            'status' => NotificationStatus::SUCCESS->value
        ]);
    }

    public function profile()
    {
        $id = auth()->user()->id;
        $user = User::with(['doctorDetail', 'patientDetail'])->find($id);
        
        // For backward compatibility, set the appropriate detail based on role
        $user->setRelation('detail', $user->doctorDetail ?? $user->patientDetail);
        
        return view('admin.profile', compact('user'));
    }
    
    public function update_profile(Request $request)
    {
        $user = auth()->user();
        
        // Validate the request
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Check if email is being changed
        $emailChanged = $request->email !== $user->email;
        
        // Update basic user info (excluding email if it's being changed)
        $userData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
        ];
        
        // Only update email if it's not being changed (email verification will handle the change)
        if (!$emailChanged) {
            $userData['email'] = $request->email;
        }
        
        $user->update($userData);
        
        // Handle profile photo upload
        if ($request->hasFile('profile')) {
            // Delete old profile photo if exists
            if ($user->profile_photo && file_exists(public_path($user->profile_photo))) {
                unlink(public_path($user->profile_photo));
            }
            
            $file = $request->file('profile');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('storage/profile_photos');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            $file->move($destinationPath, $filename);
            $filePath = 'storage/profile_photos/' . $filename;
            
            // Update profile photo in users table
            $user->update(['profile_photo' => $filePath]);
        }
        
        // Update role-specific details
        if ($user->role === 'specialist') {
            $doctorData = [
                'phone_number' => $request->phone_number,
            ];
            
            if ($user->doctorDetail) {
                $user->doctorDetail()->update($doctorData);
            } else {
                $user->doctorDetail()->create($doctorData);
            }
        } else if ($user->role === 'user') {
            $patientData = [
                'phone_number' => $request->phone_number,
            ];
            
            if ($user->patientDetail) {
                $user->patientDetail()->update($patientData);
            } else {
                $user->patientDetail()->create($patientData);
                
                // Initialize subscription for new patient detail
                $subscriptionService = app(SubscriptionService::class);
                try {
                    $subscriptionService->initializePatientSubscription($user);
                } catch (\Exception $e) {
                    // Log the error but don't fail the profile update
                    Log::error("Failed to initialize subscription for patient {$user->id}: " . $e->getMessage());
                }
            }
        }

        // Handle email verification if email is being changed
        if ($emailChanged) {
            try {
                // Generate verification token
                $verificationToken = \App\Models\EmailVerificationToken::generateToken($user, $request->email);
                
                // Send verification email
                $emailService = app(\App\Services\EmailService::class);
                $emailService->sendEmailVerification($user, $request->email, $verificationToken->token);
                
                return back()->with([
                    'notification' => 'Profile updated successfully. Please check your new email address for verification.',
                    'status' => \App\Enums\NotificationStatus::SUCCESS->value
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to send email verification: ' . $e->getMessage());
                return back()->with([
                    'notification' => 'Profile updated but email verification failed. Please try again.',
                    'status' => \App\Enums\NotificationStatus::WARNING->value
                ]);
            }
        }

        return back()->with([
            'notification' => 'Profile updated successfully.',
            'status' => \App\Enums\NotificationStatus::SUCCESS->value
        ]);
    }

    public function update_admin_credentials(Request $request)
    {
        $id = auth()->user()->id;
        $user = User::where('id', $id)->first();
        $request->validate([
            'old_password' => 'required|min:5',
            'new_password' => 'required|min:5',
            'confirm_new_password' => 'required|same:new_password|min:5',
        ]);
        
        if (Hash::check($request->old_password, $user->password)) {
            $data['password'] = Hash::make($request->new_password);
            User::where('id', $id)->update($data);

            // Send password change notification email
            try {
                $emailService = app(\App\Services\EmailService::class);
                $emailService->sendPasswordChangeNotification(
                    $user, 
                    $request->ip(), 
                    $request->userAgent()
                );
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Failed to send password change notification: ' . $e->getMessage());
            }

            return redirect()->back()->with([
                'notification' => 'Password updated successfully. Check your email for confirmation.',
                'status' => NotificationStatus::SUCCESS->value
            ]);
        } else {
            return redirect()->back()->with([
                'notification' => 'Incorrect Password.',
                'status' => NotificationStatus::DANGER->value
            ]);
        }
    }

    /**
     * Verify email address change
     *
     * @param string $token
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verifyEmail($token)
    {
        try {
            $verificationToken = \App\Models\EmailVerificationToken::findValidToken($token);
            
            if (!$verificationToken) {
                return redirect()->route('login')->with([
                    'notification' => 'Invalid or expired verification link.',
                    'status' => NotificationStatus::DANGER->value
                ]);
            }

            $user = $verificationToken->user;
            
            // Update user's email
            $user->update([
                'email' => $verificationToken->email,
                'email_verified_at' => now(),
            ]);
            
            // Mark token as used
            $verificationToken->markAsUsed();
            
            // If user is authenticated, redirect to profile, otherwise to login
            if (auth()->check()) {
                return redirect()->route('admin.profile')->with([
                    'notification' => 'Email address verified and updated successfully.',
                    'status' => NotificationStatus::SUCCESS->value
                ]);
            } else {
                return redirect()->route('login')->with([
                    'notification' => 'Email address verified successfully. Please log in with your new email address.',
                    'status' => NotificationStatus::SUCCESS->value
                ]);
            }
            
        } catch (\Exception $e) {
            Log::error('Email verification failed: ' . $e->getMessage());
            return redirect()->route('login')->with([
                'notification' => 'Email verification failed. Please try again.',
                'status' => NotificationStatus::DANGER->value
            ]);
        }
    }
    public function manage_speciality(Request $request) {
        $searchFields = ['title'];

        $specialists = PaginationHelper::applyDynamicFilters(
            Speciality::orderBy('id','desc'), 
            $request, 
            $searchFields
        );

        return view('admin.users.speciality', compact('specialists'));
    }
    public function specialities_create()
    {
        return view('admin.users.create_speciality');
    }

    public function specialities_store(Request $request)
    {
        $result = $this->userService->storeSpeciality($request);

        if (!$result['success']) {
            return redirect()->back()->with([
                'notification' => 'An error occurred while creating the speciality: ' . $result['error'],
                'status' => NotificationStatus::DANGER->value
            ])->withInput();
        }

        return redirect()->back()->with([
            'notification' => 'Speciality created successfully.',
            'status' => NotificationStatus::SUCCESS->value
        ]);
    }

    public function specialities_edit(Speciality $speciality)
    {
        return view('admin.users.create_speciality', compact('speciality'));
    }

    public function specialities_update(Request $request, Speciality $speciality)
    {
        $result = $this->userService->updateSpeciality($request, $speciality->id);

        if (!$result['success']) {
            return redirect()->back()->with([
                'notification' => 'An error occurred while updating the speciality: ' . $result['error'],
                'status' => NotificationStatus::DANGER->value
            ])->withInput();
        }

        return redirect()->back()->with([
            'notification' => 'Speciality updated successfully.',
            'status' => NotificationStatus::SUCCESS->value
        ]);
    }

    public function specialities_destroy($id)
    {
        $result = $this->userService->deleteSpeciality($id);

        if (!$result['success']) {
            return redirect()->back()->with([
                'notification' => 'An error occurred while deleting the speciality: ' . $result['error'],
                'status' => NotificationStatus::DANGER->value
            ])->withInput();
        }

        return redirect()->back()->with([
            'notification' => 'Speciality deleted successfully.',
            'status' => NotificationStatus::SUCCESS->value
        ]);
    }
    public function toggleStatus($id)
    {
        $speciality = Speciality::findOrFail($id);
        $speciality->is_active = !$speciality->is_active;
        $speciality->save();

        return redirect()->back()->with([
            'notification' => 'Speciality status updated successfully.',
            'status' => NotificationStatus::SUCCESS->value
        ]);
    }
    public function doctors_dashboard()
    {
        return view('admin.users.doctor.doctors_dashboard');
    }

    // Patient Management Methods
    public function manage_patients(Request $request)
    {
        $searchFields = ['first_name', 'last_name', 'email'];

        $patients = PaginationHelper::applyDynamicFilters(
            User::with(['patientDetail'])->where('role', 'user'),
            $request, 
            $searchFields
        );
            
        return view('admin.users.patient.listing', compact('patients'));
    }

    public function patients_dashboard()
    {
        return view('admin.users.patient.patients_dashboard');
    }

    public function createPatient()
    {
        return view('admin.users.patient.create_patient');
    }
    
    public function showPatient(User $patient)
    {
        $patient->load('patientDetail');
        
        // Load patient's appointments with doctor data in one query
        $appointments = Appointment::with(['doctor' => function($query) {
                $query->select('id', 'first_name', 'last_name', 'email', 'profile_photo');
            }])
            ->where('patient_id', $patient->id)
            ->orderBy('appointment_date', 'desc')
            ->get();
        
        // Load patient's transactions with doctor data in one query
        $transactions = Transaction::with(['doctor' => function($query) {
                $query->select('id', 'first_name', 'last_name', 'email', 'profile_photo');
            }])
            ->where('patient_id', $patient->id)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.users.patient.details', compact('patient', 'appointments', 'transactions'));
    }

    public function editPatient(User $patient)
    {
        $user = User::with('patientDetail')->where('id', $patient->id)->first();
        return view('admin.users.patient.create_patient', compact('user'));
    }

    public function destroyPatient(User $patient)
    {
        try {
            // Delete the patient's profile photo if it exists
            if ($patient->profile_photo) {
                Storage::delete('public/' . $patient->profile_photo);
            }
            
            $patient->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Patient deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete patient. Please try again.'
            ], 500);
        }
    }

    public function manage_admins(Request $request)
    {
        $searchFields = ['first_name', 'last_name', 'email'];

        $query = User::with(['detail'])->whereIn('role', ['admin', 'superadmin']);

        // Status filter using is_block
        if ($request->has('status')) {
            if ($request->status === 'active') {
                $query->where('is_block', 0);
            } elseif ($request->status === 'blocked') {
                $query->where('is_block', 1);
            }
        }

        $admins = PaginationHelper::applyDynamicFilters(
            $query,
            $request, 
            $searchFields
        );
        
        return view('admin.users.admin.listing', compact('admins'));
    }
    
    public function create_admin()
    {
        return view('admin.users.admin.create');
    }

    /**
     * Show the form for editing the specified admin user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\View\View
     */
    public function admin_edit(User $user)
    {
        // Ensure the user is an admin
        if ($user->role !== 'admin') {
            abort(404);
        }

        return view('admin.users.admin.create', compact('user'));
    }

    public function webSettings()
    {
        $webSetting = WebSetting::first();
        if (!$webSetting) {
            $webSetting = WebSetting::create(['admin_commission' => 0]);
        }
        return view('admin.web_settings', compact('webSetting'));
    }

    public function updateWebSettings(Request $request)
    {
        $request->validate([
            'admin_commission' => 'required|numeric|min:0|max:100',
        ]);
        $webSetting = WebSetting::first();
        if (!$webSetting) {
            $webSetting = WebSetting::create(['admin_commission' => $request->admin_commission]);
        } else {
            $webSetting->update(['admin_commission' => $request->admin_commission]);
        }
        return redirect()->route('admin.web-settings')->with([
            'notification' => 'Web settings updated successfully.',
            'status' => NotificationStatus::SUCCESS->value
        ]);
    }
}
