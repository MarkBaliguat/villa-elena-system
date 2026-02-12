<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        // Get the user by userID from the route
        $user = User::findOrFail($request->route('id'));

        // Check if the hash matches
        if (! hash_equals(sha1($user->email), (string) $request->route('hash'))) {
            abort(403, 'Invalid verification link.');
        }

        // Check if already verified
        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('home', absolute: false).'?verified=1');
        }

        // Mark as verified
        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        // Log the user in if not already logged in
        if (!Auth::check()) {
            Auth::login($user);
        }

        return redirect()->intended(route('home', absolute: false).'?verified=1');
    }
}