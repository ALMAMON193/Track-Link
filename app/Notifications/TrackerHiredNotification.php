<?php

namespace App\Notifications;

use App\Models\HireRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TrackerHiredNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $hireRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct(HireRequest $hireRequest)
    {
        $this->hireRequest = $hireRequest;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database']; // email and database notification
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('New Hire Request')
            ->view(
                'emails.tracker_hired',
                [
                    'notifiable' => $notifiable,
                    'shipperName' => auth()->user()->name,
                    'date' => $this->hireRequest->date,
                    'reason' => $this->hireRequest->reason,
                ]
            );
    }


    /**
     * Get the array representation of the notification (for database storage).
     */
    public function toArray(object $notifiable): array
    {
        return [
            'hire_request_id' => $this->hireRequest->id,
            'shipper_id' => $this->hireRequest->shipper_id,
            'tracker_id' => $this->hireRequest->tracker_id,
            'date' => $this->hireRequest->date,
            'reason' => $this->hireRequest->reason,
            'status' => $this->hireRequest->status,
        ];
    }
}
