<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomEmailVerification extends Notification implements ShouldQueue
{
    use Queueable;
    public string $verificationUrl;

    public function __construct(string $verificationUrl)
    {
        $this->verificationUrl = $verificationUrl;
    }
    public function via($notifiable): array
    {
        return ['mail'];
    }
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Verify Your Email Address')
            ->line('Click the button below to verify your email address.')
            ->action('Verify Email', $this->verificationUrl)
            ->line('If you did not create an account, no further action is required.');
    }
}
