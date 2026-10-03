<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Email Verification Required')
                    ->view('emails.email_verification')
                    ->with([
                        'name' => $this->data['name'],
                        'email' => $this->data['email'],
                        'verificationUrl' => $this->data['verificationUrl'],
                        'appName' => config('app.name'),
                        'contactEmail' => config('mail.from.address')
                    ]);
    }
} 