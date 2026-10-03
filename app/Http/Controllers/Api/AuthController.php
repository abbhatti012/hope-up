<?php

namespace App\Http\Controllers\Api;

use App\Services\UserService;
use App\Models\User;
use App\Models\LoginActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(Request $request)
    {
        $result = $this->userService->registerUser($request);

        if ($result instanceof \Illuminate\Http\JsonResponse) {
            $resultData = $result->getData(true);
        } else {
            $resultData = $result;
        }

        if (!$resultData['success']) {
            return response()->json(['error' => 'Error creating user!', 'message' => $resultData['error']], 500);
        }

        return response()->json(['message' => 'User Created Successfully!', 'data' => $resultData['data']], 200);
    }

    public function update(Request $request, $id)
    {
        $result = $this->userService->updateUser($request, $id);

        if (!$result['success']) {
            return response()->json([
                'error' => 'Error updating user!',
                'message' => $result['error']
            ], 500);
        }

        return response()->json([
            'message' => 'User updated successfully!',
            'data' => $result['data']
        ], 200);
    }
    
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            $user = User::with(['doctorDetail', 'patientDetail'])
                        ->where('email', $request->email)
                        ->first();

            // Add the appropriate detail to the user object for backward compatibility
            if ($user) {
                $user->setRelation('detail', $user->doctorDetail ?? $user->patientDetail);
            }

            // Blocked user check
            if ($user && $user->is_block) {
                // Record failed login attempt for blocked user
                LoginActivity::create([
                    'user_id' => $user->id,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'status' => 'blocked',
                ]);
                return response()->json(['success' => false, 'message' => 'Your account is blocked. Please contact support.'], 403);
            }

            if (!$user || !Hash::check($request->password, $user->password)) {
                // Record failed login attempt
                LoginActivity::create([
                    'user_id' => $user ? $user->id : null,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'status' => 'failed',
                ]);
                
                return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
            }

            // Record successful login attempt
            LoginActivity::create([
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'success',
            ]);

            // Update last_login_at
            $user->last_login_at = now();
            $user->save();

            $token = $user->createToken('authToken')->plainTextToken;

            $user->update(['user_token' => $token]);

            return response()->json(['success' => true, 'data' => $user], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to login. Please try again later!',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

