<?php

namespace App\Http\Controllers\API\Shipper;

use App\Http\Controllers\Controller;
use App\Http\Requests\Trucker\UpdatePasswordRequest;
use App\Http\Requests\Trucker\UpdatePersonalInformationRequest;
use App\Http\Resources\Trucker\PersonalInformationResource;
use App\Models\PersonalInformation;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfileSettingController extends Controller
{
    use ApiResponse;
    public function personalInformation()
    {
        $user = Auth::user();
        $personalInfo = $user->personalInformation ?? new PersonalInformation();
        return $this->sendResponse(
            new PersonalInformationResource($personalInfo),
            'Personal information retrieved successfully.'
        );
    }
    public function updatePersonalInformation(UpdatePersonalInformationRequest $request)
    {
        $user = auth()->user();
        // Update User fields
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        // Update or create personal information
        $personalInfo = $user->personalInformation ?? new PersonalInformation(['user_id' => $user->id]);
        $personalInfo->city = $request->city;
        $personalInfo->address = $request->address;
        $personalInfo->phone = $request->phone;
        $personalInfo->about = $request->about;

        if ($request->hasFile('avatar')) {
            $oldAvatar = $personalInfo->avatar;

            // Delete old avatar file if it exists in storage
            if ($oldAvatar && Storage::disk('public')->exists($oldAvatar)) {
                $deleted = Storage::disk('public')->delete($oldAvatar);
                if (!$deleted) {
                    Log::warning("Failed to delete old avatar: {$oldAvatar}");
                }
            }
            // Store new avatar and update path
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $personalInfo->avatar = $avatarPath;
        }
        $personalInfo->save();
        return $this->sendResponse([], 'Personal information updated successfully.');
    }
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = auth()->user();

        // Check if current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return $this->sendError('The current password is incorrect.');
        }

        // Check if new password is the same as current password
        if (Hash::check($request->new_password, $user->password)) {
            return $this->sendError('Your new password cannot be the same as your current password. Please choose a different one.');
        }
        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();
        return $this->sendResponse([], 'Password updated successfully.');
    }
    //delete account email input confirm than delete account
    public function deleteAccount(Request $request)
    {
        $user = auth()->user();
        // Validate the input email matches authenticated user's email
        $request->validate([
            'email' => 'required|email',
        ]);
        if ($request->email !== $user->email) {
            return $this->sendError('Email does not match authenticated user.');
        }
        // Delete personal information
        $user->personalInformation()->delete();
        // Delete job posts
        $user->jobPosts()->delete();
        // Finally delete the user
        $user->delete();

        return $this->sendResponse([], 'Account deleted successfully.');
    }
}
