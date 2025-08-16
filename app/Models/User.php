<?php

namespace App\Models;

use App\Notifications\CustomEmailVerification;
use App\Notifications\CustomResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'is_verified',
        'status',
        'terms_and_conditions',
        'company_name',
        'email_verified_at',
        'verified_at',
        'reset_password_token',
        'reset_password_token_expire_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'reset_password_token',
        'reset_password_token_expire_at',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'verified_at' => 'datetime',
        'reset_password_token_expire_at' => 'datetime',
        'is_verified' => 'boolean',
        'terms_and_conditions' => 'boolean',
    ];

    public function sendEmailVerifiedNotification(): void
    {
        $frontendUrl = config('app.frontend_url') . '/auth/verified-success';

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            [
                'id' => $this->id,
                'hash' => sha1($this->email),
                'redirect_url' => $frontendUrl,
            ]
        );
        $this->notify(new CustomEmailVerification($verificationUrl));
    }
    /**
     * Send custom password reset notification
     */
    public function sendPasswordResetNotification ($token): void
    {
        $this->notify (new CustomResetPassword($token));
    }

    /**
     * Relationships
     */
    public function personalInformation ()
    {
        return $this->hasOne (PersonalInformation::class);
    }

    public function jobApplications ()
    {
        return $this->hasMany (JobApplication::class);
    }

    public function jobPosts ()
    {
        return $this->hasMany (JobPost::class);
    }

    public function experiencePreference ()
    {
        return $this->hasOne (ExperiencePreference::class);
    }

    public function driverDetail ()
    {
        return $this->hasOne (DriverDetail::class);
    }
}
