<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()  { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','unique:users,email'],
            'role'     => ['required','in:teacher,student'],
            'password' => ['required','confirmed', Password::min(8)],
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role,
            'status'   => 'pending',          // always pending on self-register
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('login')
            ->with('toast_success', 'Registration successful! Please wait for admin approval.');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Invalid email or password.'])->onlyInput('email');
        }

        $user = Auth::user();

        // Admins bypass approval check
        if (!$user->isAdmin() && !$user->isApproved()) {
            Auth::logout();
            return back()->withErrors([
                'email' => $user->status === 'rejected'
                    ? 'Your account has been rejected. Contact the administrator.'
                    : 'Your account is pending admin approval.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        // Redirect by role
        return match($user->role) {
            'admin'   => redirect()->route('admin.dashboard')->with('toast_success', 'Welcome, Admin!'),
            'teacher' => redirect()->route('teacher.dashboard')->with('toast_success', 'Welcome, ' . $user->name . '!'),
            'student' => redirect()->route('student.dashboard')->with('toast_success', 'Welcome, ' . $user->name . '!'),
            default   => redirect()->route('login'),
        };
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('toast_success', 'Logged out successfully.');
    }
}
