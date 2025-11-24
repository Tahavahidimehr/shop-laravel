<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendVerifyCodeJob implements ShouldQueue
{
    use Queueable;

    protected string $mobile;
    protected string $code;

    /**
     * Create a new job instance.
     */
    public function __construct(string $mobile, string $code)
    {
        $this->mobile = $mobile;
        $this->code = $code;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("کد تأیید {$this->code} به {$this->mobile} ارسال شد.");
    }
}
