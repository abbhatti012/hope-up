<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegistrationCredentials extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @param array $data
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
        return $this->subject('Your Account Credentials')
                    ->view('emails.credentials', [
                        'name' => $this->data['name'],
                        'email' => $this->data['email'],
                        'password' => $this->data['password'],
                        'loginUrl' => $this->data['loginUrl'],
                        'isAdmin' => $this->data['isAdmin']
                    ]);
    }
}
