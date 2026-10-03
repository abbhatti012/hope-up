<?php

namespace App\Http\Controllers;

use App\Enums\Roles;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\LoginActivity;
use Illuminate\Support\Facades\Auth;

class PublicController extends Controller
{
    public function index() {
        if (auth()->check()) {
            return redirect()->route('home');
        }
        return view('admin.admin_login');
    }
    
    public function login()
    {
        if (auth()->check()) {
            return redirect()->route('home');
        }
        return view('admin.admin_login');
    }
    public function authenticate_user(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $authUser = auth()->user();
            
            // Check if user is blocked
            if ($authUser->is_block) {
                Auth::logout();
                // Record blocked login attempt
                LoginActivity::create([
                    'user_id' => $authUser->id,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'status' => 'blocked',
                ]);
                return back()->with('login_error', 'Your account has been blocked. Please contact support.');
            }
            
            // Record successful login activity
            LoginActivity::create([
                'user_id' => $authUser->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'success',
            ]);
            
            // Update last_login_at
            $authUser->last_login_at = now();
            $authUser->save();
            
            return redirect(url('/'))->with('login_status', 'success');

        } else {
            // Record failed login attempt
            $userModel = User::where('email', $request->email)->first();
            LoginActivity::create([
                'user_id' => $userModel ? $userModel->id : null,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'failed',
            ]);
            return back()->with('login_error', 'error');
        }
    }
}
