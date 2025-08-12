<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $user;
    public $purpose;

    public function __construct($otp, $user, $purpose)
    {
        $this->otp = $otp;
        $this->user = $user;
        $this->purpose = $purpose;
    }

    public function build(): OtpMail
    {
        return $this->subject($this->purpose)
            ->view('emails.otp');
    }
}
