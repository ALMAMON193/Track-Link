<?php

namespace App\Http\Controllers\API\Shipper;

use App\Helpers\Helper;
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


    /**
     * Get personal information
     */
    public function personalInformation()
    {
        $user = Auth::user();

        $personalInfo = $user->personalInformation ?? new PersonalInformation();

        return $this->sendResponse(
            new PersonalInformationResource($personalInfo),
            'Personal information retrieved successfully.'
        );
    }


    /**
     * Update personal information
     */
    public function updatePersonalInformation(UpdatePersonalInformationRequest $request)
    {
        $user = auth()->user();

        // Update user basic fields
        $user->name  = $request->name;
        $user->save();


        // Update or create personal information
        $personalInfo = $user->personalInformation ?? new PersonalInformation(['user_id' => $user->id]);
        $personalInfo->city    = $request->city;
        $personalInfo->address = $request->address;
        $personalInfo->phone   = $request->phone;
        $personalInfo->about   = $request->about;


        // Handle avatar upload
        if ($request->hasFile('avatar')) {

            // Delete old avatar (if exists)
            if ($personalInfo->avatar) {
                Helper::deleteFile($personalInfo->avatar);
            }

            // Upload new avatar
            $avatarPath = Helper::uploadFile('avatars/shipper', $request->file('avatar'));
            $personalInfo->avatar = $avatarPath;
        }

        $personalInfo->save();

        return $this->sendResponse([], 'Personal information updated successfully.');
    }


    /**
     * Update password
     */
    public function updatePassword(UpdatePasswordRequest $request)
    {
        $user = auth()->user();

        // Check current password
        if (!Hash::check($request->current_password, $user->password)) {
            return $this->sendError('The current password is incorrect.');
        }

        // Check new password != current password
        if (Hash::check($request->new_password, $user->password)) {
            return $this->sendError('Your new password cannot be the same as your current password. Please choose a different one.');
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return $this->sendResponse([], 'Password updated successfully.');
    }


    /**
     * Delete account after confirming email
     */
    public function deleteAccount(Request $request)
    {
        $user = auth()->user();

        // Validate email input
        $request->validate([
            'email' => 'required|email',
        ]);

        if ($request->email !== $user->email) {
            return $this->sendError('Email does not match authenticated user.');
        }

        // Delete related personal information
        $user->personalInformation()->delete();

        // Delete user's job posts
        $user->jobPosts()->delete();

        // Delete user account
        $user->delete();

        return $this->sendResponse([], 'Account deleted successfully.');
    }

}
