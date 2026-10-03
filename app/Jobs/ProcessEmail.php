<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProcessEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $to;
    protected $subject;
    protected $message;

    /**
     * Create a new job instance.
     *
     * @param string $to
     * @param string $subject
     * @param string $message
     * @return void
     */
    public function __construct($to, $subject, $message)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            Log::info('Processing email job', [
                'to' => $this->to,
                'subject' => $this->subject,
                'job_id' => $this->job->getJobId()
            ]);

            // You can replace this with your actual email sending logic
            Mail::raw($this->message, function($message) {
                $message->to($this->to)
                        ->subject($this->subject);
            });

            Log::info('Email processed successfully', [
                'to' => $this->to,
                'subject' => $this->subject,
                'job_id' => $this->job->getJobId()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to process email job', [
                'to' => $this->to,
                'subject' => $this->subject,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Re-throw the exception to mark the job as failed
            throw $e;
        }
    }
    
    /**
     * Handle a job failure.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function failed(\Throwable $exception)
    {
        Log::error('Email job failed', [
            'to' => $this->to,
            'subject' => $this->subject,
            'error' => $exception->getMessage(),
            'trace' => $exception->getTraceAsString()
        ]);
    }
}
