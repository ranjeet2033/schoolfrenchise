<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check() && Auth::user()->isStudent()) {
            return redirect()->intended('/student/dashboard');
        }
        return view('pages.student-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login_id' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginId = trim($request->login_id);
        $password = $request->password;

        // Find student by Registration No, Roll No, Email, or Mobile
        $student = Student::where('registration_no', $loginId)
            ->orWhere('roll_no', $loginId)
            ->orWhere('email', $loginId)
            ->orWhere('mobile', $loginId)
            ->first();

        if ($student && $student->user_id) {
            $user = User::find($student->user_id);
            if ($user && Auth::attempt(['email' => $user->email, 'password' => $password, 'is_active' => true], $request->filled('remember'))) {
                $request->session()->regenerate();
                return redirect()->intended('/student/dashboard')->with('success', "Welcome to your Student Portal, {$student->full_name}!");
            }
        }

        // Direct attempt via User table by email or mobile
        $userDirect = User::where(function ($q) use ($loginId) {
            $q->where('email', $loginId)
              ->orWhere('mobile', $loginId);
        })->where('role', 'student')->first();

        if ($userDirect && Auth::attempt(['email' => $userDirect->email, 'password' => $password, 'role' => 'student', 'is_active' => true], $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/student/dashboard')->with('success', "Welcome back!");
        }

        return back()->withInput($request->only('login_id', 'remember'))
            ->with('error', 'Invalid Student Registration / Roll No / Mobile / Email or Password. Please check with your study centre.');
    }
}
