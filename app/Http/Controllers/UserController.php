<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str; // Add this import
// use Symfony\Component\Routing\Exception\RouteNotFoundException;
// use Illuminate\Http\RedirectResponse;


class UserController extends Controller
{
    
    public function home()
    {
        return view('home');
    }

    public function showRegistrationForm()
    {
        return view('user.register');
    }

    // Function to save user data to database
    public function store(Request $request){
    $validated = $request->validate([
        'name' => ['required', 'min:4'],
        'email' => ['required', 'email', Rule::unique('users', 'email')],
        'password' => 'required|confirmed|min:6',
        'email_verified_at' => now(), // Mark the email as verified.
        'remember_token' => Str::random(10), // Generate a random token.
    ], [
        'password.confirmed' => 'The passwords do not match.',
    ]);

    $validated['password'] = Hash::make($validated['password']);

    $user = User::create($validated);

    auth()->login($user);

    return redirect('/login')->with('success', 'Registration successful');
}

// Authenticate Using User Google Mail
 public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            $user = User::where('email', $googleUser->email)->first();

            if (!$user) {
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => Hash::make(Str::random(10)), // You can generate a random password or leave it empty.
                    'email_verified_at' => now(), // Mark the email as verified.
                    'remember_token' => Str::random(10), // Generate a random token.
                ]);
            }

            // Update email_verified_at when the user logs in (assumes email is verified through Google).
            $user->update(['email_verified_at' => now()]);
            
            Auth::login($user);

            return redirect('/dashboard')->with('success', 'Google Authentication Successful! Welcome To Your Dashboard'); // Redirect to the desired page after successful login.
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Google authentication failed.');
        }
}

// Function to Login
public function login(Request $request)
{
    if (view()->exists('user.login')) {
        // Validate the login request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt to log in the user
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            // Authentication successful

            // Redirect to the dashboard
            return redirect('/dashboard')->with(
                [
                    'success' => 'Login Successful!',
                    'message' => 'Alert Success',
                ]);
                
        } else {
            // Authentication failed
            return view('user.login')->with('error', 'Invalid credentials');
            // ->withInput($request->only('email'));
        }
    } else {
  
        // Instead of abort(404), you can use the response method to display a custom 404 view
        return response()->view('errors.404');
    }
}

   
// Function to logout   
   public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login ')->with('success', 'Logout successful');
    }

}
?>