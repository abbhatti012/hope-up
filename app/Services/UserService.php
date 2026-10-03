<?php
    namespace App\Services;

    use App\Models\User;
    use App\Models\Speciality;
    use App\Models\DoctorDetail;
    use Illuminate\Http\Request;
    use App\Models\PatientDetail;
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Log;
    use Illuminate\Support\Facades\Hash;
    use App\Services\SubscriptionService;
    use App\Services\EmailService;
    use Illuminate\Support\Facades\Mail;
    use Carbon\Carbon;
    
    class UserService
    {
        protected $emailService;

        public function __construct(EmailService $emailService)
        {
            $this->emailService = $emailService;
        }

        /**
         * Block a user
         *
         * @param int $userId
         * @return array
         */
        public function blockUser(int $userId): array
        {
            try {
                $user = User::findOrFail($userId);
                $user->is_block = true;
                $user->save();

                // Queue block notification email
                $this->emailService->sendBlockNotification($user, true);

                return [
                    'success' => true,
                    'message' => 'User has been blocked successfully.',
                    'user' => $user
                ];
            } catch (\Exception $e) {
                Log::error('Block user failed: ' . $e->getMessage());
                return [
                    'success' => false,
                    'message' => 'Failed to block user: ' . $e->getMessage(),
                    'error' => $e->getMessage()
                ];
            }
        }

        /**
         * Unblock a user
         *
         * @param int $userId
         * @return array
         */
        public function unblockUser(int $userId): array
        {
            try {
                $user = User::findOrFail($userId);
                $user->is_block = false;
                $user->save();

                // Queue unblock notification email
                $this->emailService->sendBlockNotification($user, false);

                return [
                    'success' => true,
                    'message' => 'User has been unblocked successfully.',
                    'user' => $user
                ];
            } catch (\Exception $e) {
                Log::error('Unblock user failed: ' . $e->getMessage());
                return [
                    'success' => false,
                    'message' => 'Failed to unblock user: ' . $e->getMessage(),
                    'error' => $e->getMessage()
                ];
            }
        }

        public function registerUser(Request $request)
        {
            $validatedData = $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6',
                'role' => 'required|in:admin,user,specialist',
                'profile_photo' => 'required|file|mimes:jpg,jpeg,png,gif',
                'phone_number' => 'required_if:role,user|nullable|string',
                'dob' => 'required_if:role,user|nullable|date',
                'gender' => 'required_if:role,user|nullable|string',
                'address' => 'required_if:role,user|nullable|string',
                'about' => 'required_if:role,specialist|nullable|string',
                'speciality_id' => 'required_if:role,specialist|integer|exists:manage_speciality,id',
                'medical_license' => 'required_if:role,specialist|nullable|string',
                'experience' => 'required_if:role,specialist|nullable|integer',
                'graduation_year' => 'required_if:role,specialist|nullable|integer',
                'degree_certificate' => 'required_if:role,specialist|nullable|file|mimes:jpg,jpeg,png,pdf',
                'medical_concern' => 'required_if:role,user|nullable|string',
            ]);

            DB::beginTransaction();

            try {
                // Handle profile photo upload
                $filePath = null;
                if ($request->hasFile('profile_photo')) {
                    $file = $request->file('profile_photo');
                    $filename = time() . '.' . $file->getClientOriginalExtension();
                    $destinationPath = public_path('storage/profile_photos');
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0777, true);
                    }
                    $file->move($destinationPath, $filename);
                    $filePath = 'storage/profile_photos/' . $filename;
                }

                $plainPassword = $validatedData['password'];
                
                $user = User::create([
                    'first_name' => $validatedData['first_name'],
                    'last_name' => $validatedData['last_name'],
                    'email' => $validatedData['email'],
                    'password' => bcrypt($plainPassword),
                    'role' => $validatedData['role'],
                    'profile_photo' => $filePath,
                ]);

                if ($validatedData['role'] === 'specialist') {
                    $doctorDetails = [
                        'user_id' => $user->id,
                        'phone_number' => $validatedData['phone_number'] ?? null,
                        'dob' => $validatedData['dob'] ?? null,
                        'gender' => $validatedData['gender'] ?? null,
                        'address' => $validatedData['address'] ?? null,
                        'about' => $validatedData['about'] ?? null,
                        'speciality_id' => $validatedData['speciality_id'] ?? null,
                        'medical_license' => $validatedData['medical_license'] ?? null,
                        'experience' => $validatedData['experience'] ?? null,
                        'graduation_year' => $validatedData['graduation_year'] ?? null,
                    ];

                    if (isset($validatedData['degree_certificate'])) {
                        $file = $validatedData['degree_certificate'];
                        $filename = time() . '.' . $file->getClientOriginalExtension();
                        $destinationPath = public_path('storage/degree_certificates');
                        if (!file_exists($destinationPath)) {
                            mkdir($destinationPath, 0777, true);
                        }
                        $file->move($destinationPath, $filename);
                        $doctorDetails['degree_certificate'] = 'storage/degree_certificates/' . $filename;
                    }

                    DoctorDetail::create($doctorDetails);
                }

                if ($validatedData['role'] === 'user') {
                    $patientDetails = [
                        'user_id' => $user->id,
                        'phone_number' => $validatedData['phone_number'] ?? null,
                        'dob' => $validatedData['dob'] ?? null,
                        'gender' => $validatedData['gender'] ?? null,
                        'address' => $validatedData['address'] ?? null,
                        'about' => $validatedData['about'] ?? null,
                        'medical_concern' => $validatedData['medical_concern'] ?? null,
                        'age' => $validatedData['dob'] ? Carbon::parse($validatedData['dob'])->age : null,
                        'blood_type' => $validatedData['blood_type'] ?? null,
                    ];
                    // Handle is_ex_military
                    $isExMilitary = 0;
                    if (isset($validatedData['is_ex_military'])) {
                        $val = $validatedData['is_ex_military'];
                        $isExMilitary = ($val === true || $val === 'true' || $val === 1 || $val === '1') ? 1 : 0;
                    } elseif ($request->has('is_ex_military')) {
                        $val = $request->input('is_ex_military');
                        $isExMilitary = ($val === true || $val === 'true' || $val === 1 || $val === '1') ? 1 : 0;
                    }
                    $patientDetails['is_ex_military'] = $isExMilitary;

                    $patientDetail = PatientDetail::create($patientDetails);
                    // Initialize subscription for new patient
                    $subscriptionService = app(SubscriptionService::class);
                    try {
                        $subscriptionService->initializePatientSubscription($user);
                    } catch (\Exception $e) {
                        // Log the error but don't fail the user creation
                        Log::error("Failed to initialize subscription for patient {$user->id}: " . $e->getMessage());
                    }
                }

                $this->emailService->sendRegistrationCredentials($user, $plainPassword);

                DB::commit();

                $userWithDetails = User::with($validatedData['role'] === 'specialist' ? 'doctorDetail' : 'patientDetail')
                    ->find($user->id);

                return [
                    'success' => true,
                    'data' => $userWithDetails,
                    'message' => 'User registered successfully'
                ];
            } catch (\Exception $e) {
                DB::rollBack();
                return [
                    'success' => false,
                    'error' => $e->getMessage()
                ];
            }
        }
        public function updateUser(Request $request, $id)
        {
            $user = User::with(['doctorDetail', 'patientDetail'])->find($id);

            if (!$user) {
                return [
                    'success' => false,
                    'error'   => 'User not found!',
                    'data'    => []
                ];
            }

            /*
             |------------------------------------------------------------
             | Validation rules – everything optional.
             | Email gets unique rule only when changed.
             |------------------------------------------------------------
             */
            // Build validation rules ONLY for inputs that are present **and** not empty
            $rules = [];

            if ($request->filled('email')) {
                $rules['email'] = ($request->email !== $user->email)
                    ? 'email|unique:users,email,' . $id
                    : 'email';
            }

            $stringFields = [
                'first_name', 'last_name', 'phone_number', 'gender', 'address', 'about',
                'medical_license', 'medical_concern'
            ];
            
            // Special handling for specialities
            if ($request->filled('speciality_id')) {
                $rules['speciality_id'] = 'integer|exists:manage_speciality,id';
            }
            foreach ($stringFields as $field) {
                if ($request->filled($field)) {
                    $rules[$field] = 'string';
                }
            }

            if ($request->filled('password')) {
                $rules['password'] = 'min:6';
            }
            if ($request->filled('role')) {
                $rules['role'] = 'in:admin,user,specialist';
            }
            if ($request->filled('dob')) {
                $rules['dob'] = 'date';
            }
            if ($request->filled('experience')) {
                $rules['experience'] = 'integer';
            }
            if ($request->filled('graduation_year')) {
                $rules['graduation_year'] = 'integer';
            }

            // File validation only when a file is actually sent
            if ($request->hasFile('profile_photo')) {
                $rules['profile_photo'] = 'file|mimes:jpg,jpeg,png,gif';
            }
            if ($request->hasFile('degree_certificate')) {
                $rules['degree_certificate'] = 'file|mimes:jpg,jpeg,png,pdf';
            }

            // Run validation only if any rules were assembled
            if (!empty($rules)) {
                $request->validate($rules);
            }

            DB::beginTransaction();
            try {
                /*-------------------------------------------------------
                 | Update users table
                 *-------------------------------------------------------*/
                $userUpdate = [];
                foreach (['first_name', 'last_name', 'role'] as $field) {
                    if ($request->filled($field)) {
                        $userUpdate[$field] = $request->$field;
                    }
                }

                if ($request->filled('email') && $request->email !== $user->email) {
                    $userUpdate['email'] = $request->email;
                }

                if ($request->filled('password')) {
                    $userUpdate['password'] = bcrypt($request->password);
                }

                if ($userUpdate) {
                    $user->update($userUpdate);
                }

                // Handle profile photo upload
                if ($request->hasFile('profile_photo')) {
                    $file = $request->file('profile_photo');
                    $filename = time() . '.' . $file->getClientOriginalExtension();
                    $destinationPath = public_path('storage/profile_photos');
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0777, true);
                    }
                    $file->move($destinationPath, $filename);
                    $filePath = 'storage/profile_photos/' . $filename;
                    $user->update(['profile_photo' => $filePath]);
                }

                // Handle role-specific details
                if ($user->role === 'specialist') {
                    $doctorDetail = [];
                    $doctorFields = [
                        'phone_number', 'dob', 'gender', 'address', 'about',
                        'medical_license', 'experience', 'graduation_year',
                        'speciality_id'
                    ];

                    foreach ($doctorFields as $field) {
                        if ($request->filled($field)) {
                            $doctorDetail[$field] = $request->$field;
                        }
                    }

                    // Handle degree certificate upload for doctors
                    if ($request->hasFile('degree_certificate')) {
                        $file = $request->file('degree_certificate');
                        $filename = time() . '.' . $file->getClientOriginalExtension();
                        $destinationPath = public_path('storage/degree_certificates');
                        if (!file_exists($destinationPath)) {
                            mkdir($destinationPath, 0777, true);
                        }
                        $file->move($destinationPath, $filename);
                        $filePath = 'storage/degree_certificates/' . $filename;
                        $doctorDetail['degree_certificate'] = $filePath;
                    }

                    // Update or create doctor details
                    if (!empty($doctorDetail)) {
                        if ($user->doctorDetail) {
                            $user->doctorDetail()->update($doctorDetail);
                        } else {
                            $user->doctorDetail()->create($doctorDetail);
                        }
                    }
                } else if ($user->role === 'user') {
                    $patientDetail = [];
                    $patientFields = [
                        'phone_number', 'dob', 'gender', 'address',
                        'medical_concern', 'blood_type'
                    ];

                    foreach ($patientFields as $field) {
                        if ($request->filled($field)) {
                            $patientDetail[$field] = $request->$field;
                        }
                    }

                    if ($request->has('is_ex_military')) {
                        $val = $request->input('is_ex_military');
                        $patientDetail['is_ex_military'] = ($val === true || $val === 'true' || $val === 1 || $val === '1') ? 1 : 0;
                    }

                    // Calculate age based on date of birth
                    if ($request->filled('dob')) {
                        $patientDetail['age'] = Carbon::parse($request->dob)->age;
                    }

                    // Update or create patient details
                    if (!empty($patientDetail)) {
                        if ($user->patientDetail) {
                            $user->patientDetail()->update($patientDetail);
                        } else {
                            $user->patientDetail()->create($patientDetail);
                        }
                    }
                }

                DB::commit();

                return [
                    'success' => true,
                    'data'    => $user->fresh('detail')
                ];
            } catch (\Exception $e) {
                DB::rollBack();

                return [
                    'success' => false,
                    'error'   => $e->getMessage(),
                ];
            }
        }

        public function storeSpeciality(Request $request) {
            $validatedData = $request->validate([
                'title' => 'required'
            ]);
            try {
                $speciality = Speciality::create([
                    'title' => $validatedData['title'],
                ]);

                return [
                    'success' => true,
                    'data' => $speciality
                ];
            } catch (ValidationException $e) {
                throw $e;
            } catch (\Exception $e) {
                return [
                    'success' => false,
                    'error' => $e->getMessage(),
                ];
            }
        }

        public function updateSpeciality(Request $request, $id) {
            $speciality = Speciality::find($id);

            if(!$speciality) {
                return [
                    'success' => false,
                    'error' => 'Speciality not found!',
                    'data' => []
                ];
            }

            $validatedData = $request->validate([
                'title' => 'required'
            ]);
            try {
                $speciality->update([
                    'title' => $validatedData['title'],
                ]);

                return [
                    'success' => true,
                    'data' => $speciality
                ];
            } catch (ValidationException $e) {
                throw $e;
            } catch (\Exception $e) {
                return [
                    'success' => false,
                    'error' => $e->getMessage(),
                ];
            }
        }
        
        public function deleteSpeciality($id) {
            $speciality = Speciality::where('id',$id)->first();

            try {
                if ($speciality) {
                    $speciality->delete();

                    return [
                        'success' => true,
                    ];
                }
            } catch (\Exception $e) {
                return [
                    'success' => false,
                    'error' => $e->getMessage(),
                ];
            }
        }
    }
?>