<?php

namespace App\Services;

use App\Jobs\SendVerifyCodeJob;
use App\Models\VerifyCode;
use Carbon\Carbon;

class VerifyCodeService
{
    public function generate(string $mobile): VerifyCode
    {
        $this->deleteCodes($mobile);

        $code = rand(10000, 99999);

        $verify = VerifyCode::create([
            'mobile' => $mobile,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(3),
        ]);

        SendVerifyCodeJob::dispatch($mobile, $code);

        return $verify;
    }

    public function getLastCode(string $mobile): ?VerifyCode
    {
        return VerifyCode::where('mobile', $mobile)
            ->latest()
            ->first();
    }

    public function check(string $mobile, string $code): bool
    {
        $record = VerifyCode::where('mobile', $mobile)
            ->where('code', $code)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();
        return (bool) $record;
    }

    public function deleteCodes(string $mobile): void
    {
        VerifyCode::where('mobile', $mobile)
            ->delete();
    }
}
