<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;

    public function __construct($otp) {
        $this->otp = $otp;
    }

    public function build()
    {
        return $this->subject('Mã xác thực đăng ký')
            ->view('email.otp-register')
            ->with(['otp' => $this->otp]);
    }
}
