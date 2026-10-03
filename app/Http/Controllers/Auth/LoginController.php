<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LoginActivity;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function redirectTo()
    {
        return '/home';
    }
    protected function attemptLogin(Request $request)
    {
        $credentials = $this->credentials($request);
        
        // First, find the user to check if they're blocked
        $user = User::where('email', $credentials['email'])->first();
        
        if ($user && $user->is_block) {
            // Record blocked login attempt
            LoginActivity::create([
                'user_id' => $user->id,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'blocked',
            ]);
            
            return false;
        }
        
        return $this->guard()->attempt(
            $credentials, $request->filled('remember')
        );
    }

    protected function authenticated(Request $request, $user)
    {
        session(['profile_image' => $user->profile_photo]);
        // Record login activity
        LoginActivity::create([
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => 'success',
        ]);
        // Update last_login_at
        $user->update(['last_login_at' => now()]);
    }
}
