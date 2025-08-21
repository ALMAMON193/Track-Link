<?php

namespace App\Notifications;

use App\Models\JobPost;
use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JobPaymentSuccessNotification extends Notification
{
    use Queueable;

    protected JobPost $job;
    protected Payment $payment;

    public function __construct(JobPost $job, Payment $payment)
    {
        $this->job = $job;
        $this->payment = $payment;
    }

    public function via($notifiable): array
    {
        return ['database', 'mail']; // database + email
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Job Application Accepted & Payment Recorded')
            ->line('Your job application has been accepted and the payment has been recorded.')
            ->line('Once the job is completed, you may request withdrawal.')
            ->line('The admin will review and release the payment for transfer.')
            ->line('Amount: ' . $this->payment->amount . ' ' . $this->payment->currency)
            ->line('Transaction ID: ' . $this->payment->transaction_id);
    }

    public function toArray($notifiable): array
    {
        return [
            'job_id'        => $this->job->id,
            'package_name'  => $this->job->package_name,
            'payment_id'    => $this->payment->id,
            'amount'        => $this->payment->amount,
            'currency'      => $this->payment->currency,
            'transactionId' => $this->payment->transaction_id,
            'message'       => 'Job application has been accepted and payment has been recorded.
                                Once the job is completed, you may request withdrawal.
                                The admin will review and release the payment for transfer.'
        ];
    }
}
