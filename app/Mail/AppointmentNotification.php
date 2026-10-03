<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AppointmentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $recipientType;
    public $action;

    /**
     * Create a new message instance.
     *
     * @param array $data
     * @param string $recipientType (patient or doctor)
     * @param string $action (created, updated, cancelled, completed)
     * @return void
     */
    public function __construct($data, $recipientType, $action)
    {
        $this->data = $data;
        $this->recipientType = $recipientType;
        $this->action = $action;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->getSubject();
        $view = $this->getView();

        return $this->subject($subject)
                    ->view($view, [
                        'data' => $this->data,
                        'recipientType' => $this->recipientType,
                        'action' => $this->action
                    ]);
    }

    /**
     * Get the email subject based on action and recipient type
     */
    private function getSubject(): string
    {
        $actionText = ucfirst($this->action);
        $recipientText = $this->recipientType === 'patient' ? 'Patient' : 'Doctor';
        
        switch ($this->action) {
            case 'created':
                return "Appointment Created - {$actionText}";
            case 'updated':
                return "Appointment Updated - {$actionText}";
            case 'cancelled':
                return "Appointment Cancelled - {$actionText}";
            case 'completed':
                return "Appointment Completed - {$actionText}";
            default:
                return "Appointment Notification - {$actionText}";
        }
    }

    /**
     * Get the email view based on action and recipient type
     */
    private function getView(): string
    {
        $action = $this->action;
        $recipient = $this->recipientType;
        
        return "emails.appointments.{$action}_{$recipient}";
    }
} 