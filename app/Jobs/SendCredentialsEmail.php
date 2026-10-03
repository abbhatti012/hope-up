<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendCredentialsEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $email;
    protected $subject;
    protected $view;
    protected $data;

    /**
     * Create a new job instance.
     *
     * @param string $email
     * @param string $subject
     * @param string $view
     * @param array $data
     */
    public function __construct(string $email, string $subject, string $view, array $data = [])
    {
        $this->email = $email;
        $this->subject = $subject;
        $this->view = $view;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::send($this->view, $this->data, function($message) {
            $message->to($this->email)
                    ->subject($this->subject);
        });
    }
}
