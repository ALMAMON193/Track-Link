<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPassword extends Notification
{

    public string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        // Get frontend base URL from config (.env)
        $frontendUrl = config('app.frontend_url', 'https://craigharlequin-next-js.vercel.app');

        // Construct reset password URL
        $resetUrl = $frontendUrl
            . "/reset-password/{$this->token}"
            . "?email=" . urlencode($notifiable->getEmailForPasswordReset());

        return (new MailMessage)
            ->subject('Reset Your Password')
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', $resetUrl)
            ->line('If you did not request a password reset, no further action is required.');
    }


    public function toArray($notifiable): array
    {
        return [
            'token' => $this->token,
        ];
    }
}
