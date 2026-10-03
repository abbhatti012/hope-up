<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;

class QueueService
{
    protected $pidFile;
    
    public function __construct()
    {
        $this->pidFile = storage_path('app/queue-worker.pid');
    }
    
    public function ensureWorkerIsRunning()
    {
        if ($this->isWorkerRunning()) {
            return 'Worker already running';
        }
        
        $command = 'php ' . base_path('artisan') . ' queue:work --tries=3 --timeout=60';
        
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Windows
            $command = 'start /B ' . $command . ' > NUL 2>&1';
            $process = Process::run($command);
            $pid = $process->id();
        } else {
            // Linux/Unix
            $command = 'nohup ' . $command . ' > /dev/null 2>&1 & echo $!';
            $process = Process::run($command);
            $pid = trim($process->output());
        }
        
        file_put_contents($this->pidFile, $pid);
        
        return 'Started worker with PID: ' . $pid;
    }
    
    protected function isWorkerRunning()
    {
        if (!file_exists($this->pidFile)) {
            return false;
        }
        
        $pid = file_get_contents($this->pidFile);
        
        if (empty($pid)) {
            return false;
        }
        
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Windows
            $process = Process::run("tasklist /FI \"PID eq $pid\" | find \"$pid\"");
            return $process->successful();
        }
        
        // Linux/Unix
        $process = Process::run("ps -p $pid");
        return $process->successful();
    }
}
