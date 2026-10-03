<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountStatusNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $isBlocked;

    /**
     * Create a new message instance.
     *
     * @param array $data
     * @param bool $isBlocked
     * @return void
     */
    public function __construct($data, $isBlocked)
    {
        $this->data = $data;
        $this->isBlocked = $isBlocked;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $view = $this->isBlocked 
            ? 'emails.account_blocked' 
            : 'emails.account_unblocked';
            
        $subject = $this->isBlocked
            ? 'Your Account Has Been Blocked'
            : 'Your Account Has Been Unblocked';

        return $this->subject($subject)
                    ->view($view, [
                        'name' => $this->data['name'],
                        'email' => $this->data['email'],
                        'loginUrl' => $this->data['loginUrl'],
                        'contactEmail' => $this->data['contactEmail'],
                        'appName' => $this->data['appName']
                    ]);
    }
}
