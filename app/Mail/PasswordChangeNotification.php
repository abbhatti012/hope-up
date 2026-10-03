<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordChangeNotification extends Mailable
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
        return $this->subject('Password Changed Successfully')
                    ->view('emails.password_change_notification')
                    ->with([
                        'name' => $this->data['name'],
                        'email' => $this->data['email'],
                        'changedAt' => $this->data['changedAt'],
                        'ipAddress' => $this->data['ipAddress'],
                        'userAgent' => $this->data['userAgent'],
                        'loginUrl' => $this->data['loginUrl'],
                        'appName' => config('app.name'),
                        'contactEmail' => config('mail.from.address')
                    ]);
    }
} 