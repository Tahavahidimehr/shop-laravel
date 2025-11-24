<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\VerifyCodeService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use ApiResponse;

    protected VerifyCodeService $verifyService;

    public function __construct(VerifyCodeService $verifyService)
    {
        $this->verifyService = $verifyService;
    }

    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|string|regex:/^09\d{9}$/',
        ], [
            'mobile.required' => 'شماره موبایل الزامی است',
            'mobile.regex' => 'شماره موبایل صحیح نیست'
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }

        $lastCode = $this->verifyService->getLastCode($request->mobile);
        if ($lastCode && $lastCode->expires_at->isFuture()) {
            return $this->errorResponse(
                "کد تایید قبلا ارسال شده است",
                null,
                429
            );
        }

        $code = $this->verifyService->generate($request->mobile);

        return $this->successResponse([
            'mobile' => $request->mobile,
        ], 'کد تأیید ارسال شد');
    }

    public function verify(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|string|regex:/^09\d{9}$/',
            'code' => 'required|string',
        ], [
            'mobile.required' => 'شماره موبایل الزامی است',
            'mobile.regex' => 'شماره موبایل صحیح نیست',
            'code.required' => 'کد تایید الزامی است'

        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator);
        }

        if (! $this->verifyService->check($request->mobile, $request->code)) {
            return $this->errorResponse('کد اشتباه یا منقضی شده است', null, 422);
        }

        $this->verifyService->deleteCodes($request->mobile);

        $user = User::firstOrCreate(['mobile' => $request->mobile]);

        Auth::login($user);

        $user->update(['mobile_verified_at' => now()]);

        return $this->successResponse([
            'user' => $user,
        ], 'ورود موفقیت‌آمیز بود');
    }

    public function logout(Request $request): JsonResponse
    {
        auth('web')->logout();

        $request->session()->invalidate();

        return $this->successResponse(null, 'با موفقیت خارج شدید');
    }

    public function user(Request $request): JsonResponse
    {
        return $this->successResponse([
            'user' => Auth::user(),
        ]);
    }
}
