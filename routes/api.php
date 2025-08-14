<?php

use App\Http\Controllers\API\Auth\AuthApiController;
use App\Http\Controllers\API\Shipper\MyJobController;
use App\Http\Controllers\API\Shipper\PostJobController;
use App\Http\Controllers\API\Trucker\BrowseJobController;
use App\Http\Controllers\API\Trucker\OverviewController;
use App\Http\Controllers\API\Trucker\ProfileSettingController;
use App\Http\Controllers\API\Trucker\SetAvailabilityController;
use App\Http\Controllers\API\Trucker\TrackDeliveryController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthApiController::class, 'registerApi']);
    Route::post('login', [AuthApiController::class, 'loginApi']);
    Route::post('forgot-password', [AuthApiController::class, 'forgotPasswordApi']);
    Route::post('reset-password', [AuthApiController::class, 'resetPasswordApi']);
    Route::post('resend-verification-email', [AuthApiController::class, 'resendVerificationEmailApi']);
});

// Email verification route
Route::get('/verify-email/{id}/{hash}', [AuthApiController::class, 'verifyEmailApi'])
    ->middleware('signed')
    ->name('verification.verify');

// Routes that require authentication and verified email
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    // Logout
    Route::post('auth/logout', [AuthApiController::class, 'logoutApi']);
    // Shipper Routes
    Route::prefix('shipper')->middleware('shipper')->group(function () {
        Route::get('my-jobs', [MyJobController::class, 'myJobs']);
        Route::post('post-job', [PostJobController::class, 'postJob']);
        Route::get('post-job/{id}', [PostJobController::class, 'postJobDetails']);
        //overview route
        Route::get('overview', [\App\Http\Controllers\API\Shipper\OverviewController::class, 'shipperOverview']);

        //personal information
        Route::get('personal-info', [\App\Http\Controllers\API\Shipper\ProfileSettingController::class, 'personalInformation']);
        Route::post('personal-info/update', [\App\Http\Controllers\API\Shipper\ProfileSettingController::class, 'updatePersonalInformation']);

        //update password
        Route::post('password/update', [\App\Http\Controllers\API\Shipper\ProfileSettingController::class, 'updatePassword']);
        //delete account
        Route::post('delete/account', [\App\Http\Controllers\API\Shipper\ProfileSettingController::class, 'deleteAccount']);

    });
    // Trucker Routes
    Route::prefix('trucker')->middleware('trucker')->group(function () {

        Route::get('my-jobs', [TrackDeliveryController::class, 'index']);
        Route::post('job-posts/{id}/delivery-status', [TrackDeliveryController::class, 'updateDeliveryStatus']);
        Route::post('job-posts/{id}/tracking-status', [TrackDeliveryController::class, 'updateTrackingStatus']);

        //setting routes
        //personal information
        Route::get('personal-info', [ProfileSettingController::class, 'personalInformation']);
        Route::post('personal-info/update', [ProfileSettingController::class, 'updatePersonalInformation']);
        //Experience & Skills
        Route::get('experience-skill', [ProfileSettingController::class, 'experienceAndSkill']);
        Route::post('experience-skill/update', [ProfileSettingController::class, 'updateExperienceAndSkill']);

        //Driving Credential
        Route::get('driving-credential', [ProfileSettingController::class, 'drivingCredential']);
        Route::post('driving-credential/update', [ProfileSettingController::class, 'updateDrivingCredential']);

        //update password
        Route::post('password/update', [ProfileSettingController::class, 'updatePassword']);
        //delete account
        Route::post('delete/account', [ProfileSettingController::class, 'deleteAccount']);

        //browse jobs
        Route::get('browse-job', [BrowseJobController::class, 'browseJob']);

        //set Availability
        Route::get('set-availability', [SetAvailabilityController::class, 'index']);
        Route::post('set-availability/store', [SetAvailabilityController::class, 'store']);

        //overview route
        Route::get('home-overview', [OverviewController::class, 'overview']);

    });
});
