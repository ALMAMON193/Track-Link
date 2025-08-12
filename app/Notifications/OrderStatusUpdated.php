<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderStatusUpdated extends Notification
{

    protected $jobPost;
    protected $statusType;
    protected $newStatus;

    public function __construct($jobPost, $statusType, $newStatus)
    {
        $this->jobPost = $jobPost;
        $this->statusType = $statusType;
        $this->newStatus = $newStatus;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database']; // send via email + store in DB
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("{$this->statusType} Updated for Job {$this->jobPost->job_id}")
            ->view('emails.job_post_status_updated', [
                'jobPost'    => $this->jobPost,
                'statusType' => $this->statusType,
                'newStatus'  => $this->newStatus,
                'notes'      => $this->jobPost->additional_instructions ?? null,
            ]);
    }

    public function toArray($notifiable): array
    {
        return [
            'job_id'      => $this->jobPost->job_id,
            'status_type' => $this->statusType,
            'new_status'  => $this->newStatus
        ];
    }
}
