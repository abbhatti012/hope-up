<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TransactionNotification extends Mailable
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
     * @param string $action (created, paid, refunded, etc.)
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

    private function getSubject(): string
    {
        $actionText = ucfirst($this->action);
        switch ($this->action) {
            case 'created':
                return "Transaction Created - {$actionText}";
            case 'paid':
                return "Transaction Paid - {$actionText}";
            case 'refunded':
                return "Transaction Refunded - {$actionText}";
            case 'updated':
                return "Transaction Updated - {$actionText}";
            default:
                return "Transaction Notification - {$actionText}";
        }
    }

    private function getView(): string
    {
        $action = $this->action;
        $recipient = $this->recipientType;
        return "emails.transactions.{$action}_{$recipient}";
    }
} 