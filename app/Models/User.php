<?php

namespace App\Models;

 use App\Notifications\CustomResetPassword;
 use Illuminate\Contracts\Auth\MustVerifyEmail;
 use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;

/**
 * @method static create(array $array)
 * @method static where(string $string, mixed $email)
 * @method static findOrFail(\Illuminate\Routing\Route|object|string|null $route)
 * @property mixed $email
 * @property mixed $user_type
 */
class User extends  Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable,HasApiTokens;
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

    public function sendVerification(): void
    {
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',               // route name
            Carbon::now()->addMinutes(60),      // expire time
            [
                'id' => $this->getKey(),
                'hash' => sha1($this->getEmailForVerification()),
            ]
        );
        $this->notify(new class($verificationUrl) extends \Illuminate\Notifications\Notification {
            protected $url;
            public function __construct($url)
            {
                $this->url = $url;
            }
            public function via($notifiable): array
            {
                return ['mail'];
            }
            public function toMail($notifiable): MailMessage
            {
                return (new MailMessage)
                    ->subject('Verify Your Email Address')
                    ->line('Please click the button below to verify your email address.')
                    ->action('Verify Email', $this->url)
                    ->line('If you did not create an account, no further action is required.');
            }
        });
    }
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new CustomResetPassword($token));
    }
    public function personalInformation()
    {
        return $this->hasOne(PersonalInformation::class);
    }

    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function jobPosts()
    {
        return $this->hasMany(JobPost::class);
    }

    public function experiencePreference()
    {
        return $this->hasOne(ExperiencePreference::class);
    }
    public function driverDetail()
    {
        return $this->hasOne(DriverDetail::class);
    }



}
