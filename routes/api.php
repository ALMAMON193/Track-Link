<?php

use App\Http\Controllers\API\Auth\AuthApiController;
use App\Http\Controllers\API\Shipper\JobRequestController;
use App\Http\Controllers\API\Shipper\MMGPaymentController;
use App\Http\Controllers\API\Shipper\MyJobController;
use App\Http\Controllers\API\Shipper\PostJobController;
use App\Http\Controllers\API\Shipper\OverviewController as ShipperOverviewController;
use App\Http\Controllers\API\Shipper\ProfileSettingController as ShipperProfileController;
use App\Http\Controllers\API\Trucker\BrowseJobController;
use App\Http\Controllers\API\Trucker\OverviewController as TruckerOverviewController;
use App\Http\Controllers\API\Trucker\ProfileSettingController;
use App\Http\Controllers\API\Trucker\SetAvailabilityController;
use App\Http\Controllers\API\Trucker\TrackDeliveryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthApiController::class, 'registerApi']);
    Route::post('login', [AuthApiController::class, 'loginApi']);
    Route::post('forgot-password', [AuthApiController::class, 'forgotPasswordApi']);
    Route::post('reset-password', [AuthApiController::class, 'resetPasswordApi']);
    Route::post('resend-verification-email', [AuthApiController::class, 'resendVerificationEmailApi']);
});

// Email verification
Route::get('/verify-email/{id}/{hash}', [AuthApiController::class, 'verifyEmailApi'])
    ->middleware('signed')
    ->name('verification.verify');

/*
|--------------------------------------------------------------------------
| Authenticated & Verified Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    // Logout
    Route::post('auth/logout', [AuthApiController::class, 'logoutApi']);

    /*
    |--------------------------------------------------------------------------
    | Shipper Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('shipper')->middleware('shipper')->group(function () {

        // Jobs
        Route::get('my-jobs', [MyJobController::class, 'myJobs']);
        Route::post('post-job', [PostJobController::class, 'postJob']);
        Route::get('post-job/{id}', [PostJobController::class, 'postJobDetails']);

        // Overview
        Route::get('overview', [ShipperOverviewController::class, 'shipperOverview']);

        // Profile Settings
        Route::get('personal-info', [ShipperProfileController::class, 'personalInformation']);
        Route::post('personal-info/update', [ShipperProfileController::class, 'updatePersonalInformation']);
        Route::post('password/update', [ShipperProfileController::class, 'updatePassword']);
        Route::post('delete/account', [ShipperProfileController::class, 'deleteAccount']);

        // Job Requests
        Route::get('request-job', [JobRequestController::class, 'index']);
        Route::get('request-job/{id}', [JobRequestController::class, 'show']);
        Route::get('request-job/{jobId}/users/{userId}', [JobRequestController::class, 'userDetails']);

        // MMG Payment
        Route::post('mmg/payment', [MMGPaymentController::class, 'merchantPayment']);
    });

    /*
    |--------------------------------------------------------------------------
    | Trucker Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('trucker')->middleware('trucker')->group(function () {

        // Jobs
        Route::get('my-jobs', [TrackDeliveryController::class, 'index']);
        Route::post('job-posts/{id}/delivery-status', [TrackDeliveryController::class, 'updateDeliveryStatus']);
        Route::post('job-posts/{id}/tracking-status', [TrackDeliveryController::class, 'updateTrackingStatus']);

        // Profile Settings
        Route::get('personal-info', [ProfileSettingController::class, 'personalInformation']);
        Route::post('personal-info/update', [ProfileSettingController::class, 'updatePersonalInformation']);
        Route::get('experience-skill', [ProfileSettingController::class, 'experienceAndSkill']);
        Route::post('experience-skill/update', [ProfileSettingController::class, 'updateExperienceAndSkill']);
        Route::get('driving-credential', [ProfileSettingController::class, 'drivingCredential']);
        Route::post('driving-credential/update', [ProfileSettingController::class, 'updateDrivingCredential']);
        Route::post('password/update', [ProfileSettingController::class, 'updatePassword']);
        Route::post('delete/account', [ProfileSettingController::class, 'deleteAccount']);

        // Browse Jobs
        Route::get('browse-job', [BrowseJobController::class, 'browseJob']);
        Route::post('apply-job', [BrowseJobController::class, 'applyJob']);

        // Set Availability
        Route::get('set-availability', [SetAvailabilityController::class, 'index']);
        Route::post('set-availability/store', [SetAvailabilityController::class, 'store']);

        // Overview
        Route::get('home-overview', [TruckerOverviewController::class, 'overview']);
    });
});
