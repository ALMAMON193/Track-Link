<?php

namespace App\Http\Controllers\API\Trucker;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Trucker\UpdateDrivingCredentialsRequest;
use App\Http\Requests\Trucker\UpdateExperienceSkillsRequest;
use App\Http\Requests\Trucker\UpdatePasswordRequest;
use App\Http\Requests\Trucker\UpdatePersonalInformationRequest;
use App\Http\Resources\Trucker\DrivingCredentialsResource;
use App\Http\Resources\Trucker\ExperienceSkillsResource;
use App\Http\Resources\Trucker\PersonalInformationResource;
use App\Models\DriverDetail;
use App\Models\ExperiencePreference;
use App\Models\PersonalInformation;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class ProfileSettingController extends Controller
{
    use ApiResponse;


    /**
     * Get personal information of authenticated trucker
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

        // Update User fields
        $user->name  = $request->name;
        $user->save();

        // Update or create personal information
        $personalInfo = $user->personalInformation ?? new PersonalInformation(['user_id' => $user->id]);
        $personalInfo->city    = $request->city;
        $personalInfo->address = $request->address;
        $personalInfo->phone   = $request->phone;
        $personalInfo->about   = $request->about;


        if ($request->hasFile('avatar')) {
            $oldAvatar = $personalInfo->avatar;

            // Delete old avatar if exists
            if ($oldAvatar) {
                $deleted = Helper::deleteFile($oldAvatar);
                if (!$deleted) {
                    Log::warning("Failed to delete old avatar: {$oldAvatar}");
                }
            }

            // Upload new avatar
            $avatarPath = Helper::uploadFile('avatars/tracker', $request->file('avatar'));
            $personalInfo->avatar = $avatarPath;
        }

        $personalInfo->save();

        return $this->sendResponse([], 'Personal information updated successfully.');
    }


    /**
     * Get experience and skill
     */
    public function experienceAndSkill()
    {
        $user = Auth::user();
        $experienceAndSkill = $user->ExperiencePreference ?? new ExperiencePreference();

        return $this->sendResponse(
            new ExperienceSkillsResource($experienceAndSkill),
            'Experience And Skills retrieved successfully.'
        );
    }


    /**
     * Update experience and skill
     */
    public function updateExperienceAndSkill(UpdateExperienceSkillsRequest $request)
    {
        $user = Auth::user();
        $experienceAndSkill = $user->ExperiencePreference ?? new ExperiencePreference(['user_id' => $user->id]);

        // Update fields from request
        $experienceAndSkill->experience = $request->experience;  // e.g. '5 years'
        $experienceAndSkill->save();

        return $this->sendResponse([], __('Experience and skills updated successfully.'));
    }


    /**
     * Get driving credentials
     */
    public function drivingCredential()
    {
        $user = Auth::user();
        $drivingCredential = $user->driverDetail ?? new DriverDetail();

        return $this->sendResponse(
            new DrivingCredentialsResource($drivingCredential),
            'Driver Details retrieved successfully.'
        );
    }


    /**
     * Update driving credentials
     */
    public function updateDrivingCredential(UpdateDrivingCredentialsRequest $request)
    {
        $user = auth()->user();
        $driverDetail = $user->driverDetail ?? new DriverDetail(['user_id' => $user->id]);
        $oldStatus = $driverDetail->status ?? 'pending';

        $driverDetail->license_number = $request->license_number;
        $driverDetail->state_of_issue = $request->state_of_issue;

        // Convert date to proper format
        if ($request->expiration_date) {
            $driverDetail->expiration_date = Carbon::createFromFormat('m/d/Y', $request->expiration_date)
                ->format('Y-m-d');
        }

        if ($request->hasFile('driver_license')) {

            // Delete old license if exists
            if ($driverDetail->driver_license) {
                Helper::deleteFile($driverDetail->driver_license);
            }
            // Upload new license
            $driverDetail->driver_license = Helper::uploadFile('driver_licenses', $request->file('driver_license'));
        }


        if ($driverDetail->isDirty() && $oldStatus === 'verified') {
            $driverDetail->status = 'unverified';
        }

        $driverDetail->save();

        return $this->sendResponse([], 'Driving credentials updated successfully.');
    }


    /**
     * Update trucker password
     */
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


    /**
     * Delete account after email confirmation
     */
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

        // Delete job applications
        $user->jobApplications()->delete();

        // Delete job posts
        $user->jobPosts()->delete();

        // Delete experience preferences
        $user->experiencePreference()->delete();

        // Delete driver details
        $user->driverDetail()->delete();

        // Finally delete the user
        $user->delete();

        return $this->sendResponse([], 'Account deleted successfully.');
    }

}
