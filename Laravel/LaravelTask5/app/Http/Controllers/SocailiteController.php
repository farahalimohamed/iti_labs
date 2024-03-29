<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SocailiteController extends Controller
{
    //
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }
    
    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->user();

        $existingUser = User::where('social_id', $user->id)->first();

        if ($existingUser) {
            // Log in the existing user.
            // auth()->login($existingUser, true);
            Auth::login($existingUser);
            return response()->json($existingUser);
        } else {
            // Create a new user.
            // $newUser = new User();
            // $newUser->name = $user->name;
            // $newUser->email = $user->email;
            // $newUser->social_id = $user->id;
            // $newUser->password = bcrypt(request(Str::random()));
            // $newUser->save();

            $newUser = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'social_id' => $user->id,
                'password' => Hash::make('my-google'),
            ]);

            // Log in the new user.
            // auth()->login($newUser, true);
            Auth::login($newUser);

            return redirect('/dashboard');
            // return response()->json($newUser);
        }

         // Redirect to url as requested by user, if empty use /dashboard page as generated by Jetstream
        return redirect()->intended('/dashboard');
    }
}
