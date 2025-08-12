<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResendVerificationRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Resources\Auth\LoginResource;
use App\Http\Resources\Auth\RegisterResource;
use App\Models\User;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthApiController extends Controller
{
    use ApiResponse;
    public function registerApi(RegisterRequest $request)
    {
        $validated = $request->validated();

        // Handle file upload for driver_license if trucker
        if ($validated['user_type'] === 'trucker' && $request->hasFile('driver_details.driver_license')) {
            $file = $request->file('driver_details.driver_license');
            $path = $file->store('driver_licenses', 'public'); // storage/app/public/driver_licenses
            $validated['driver_details']['driver_license'] = $path;
        }

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'user_type' => $validated['user_type'],
            'terms_and_conditions' => $validated['terms_and_conditions'],
            'company_name' => $validated['company_name'] ?? null,
        ]);

        // Create related models for truckers only
        if ($validated['user_type'] === 'trucker') {
            $user->driverDetail()->create($validated['driver_details']);
            $user->experiencePreference()->create($validated['experience_preferences']);
        }

        // Send verification email
        try {
            $user->sendVerification();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send verification email.'], 500);
        }

        return response()->json([
            'data' => new RegisterResource($user),
            'message' => 'Registration successful. Please check your email to verify your account.'
        ]);
    }
    public function resendVerificationEmailApi(ResendVerificationRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user->email_verified_at) {
            return $this->sendError('Email already verified.');
        }
        try {
            $user->sendVerification();
        } catch (Exception $e) {
            return $this->sendError('Failed to resend verification email.');
        }

        return $this->sendResponse([], 'Verification email resent successfully.');
    }
    public function verifyEmailApi(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = User::findOrFail($request->route('id'));
        if (! URL::hasValidSignature($request)) {
            return $this->sendError('Invalid or expired verification link.');
        }

        if ($user->hasVerifiedEmail()) {
            return $this->sendError('Email already verified.');
        }

        $user->markEmailAsVerified();
        if ($user->user_type !== 'admin') {
            $user->update([
                'is_verified' => true,
                'verified_at' => now(),
            ]);
        }
        return $this->sendResponse([], 'Email verified successfully.');
    }
    public function loginApi(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->all());
        }
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->sendError('Invalid credentials.');
        }
        if (!$user->email_verified_at) {
            return $this->sendError('Email not verified.');
        }
        if (!$user->terms_and_conditions) {
            return $this->sendError('Terms and conditions not accepted.');
        }
        // Optionally check is_verified for non-admin users
        if ($user->user_type !== 'admin' && !$user->is_verified) {
            return $this->sendError('Account not verified.');
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return $this->sendResponse(new LoginResource($user, $token), 'Login successful.',$token);
    }
    public function logoutApi(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->sendResponse([], 'Logout successful.');
    }
    public function forgotPasswordApi(ForgotPasswordRequest $request)
    {
        $validated = $request->validated();
        $status = Password::sendResetLink(['email' => $validated['email']]);
        return $status === Password::RESET_LINK_SENT
            ? $this->sendResponse([], 'Password reset link sent to your email.')
            : $this->sendError('Unable to send reset link.');
    }
    public function resetPasswordApi(ResetPasswordRequest $request)
    {
        $validated = $request->validated();
        $status = Password::reset(
            $validated,
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                    'reset_password_token' => null,
                    'reset_password_token_expire_at' => null,
                ])->save();
                event(new PasswordReset($user));
            }
        );
        return $status === Password::PASSWORD_RESET
            ? $this->sendResponse([], 'Password reset successfully.')
            : $this->sendError('Invalid token or email.');
    }
}
