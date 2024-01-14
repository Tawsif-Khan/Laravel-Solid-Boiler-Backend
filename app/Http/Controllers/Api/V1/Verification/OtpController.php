<?php

namespace App\Http\Controllers\Api\V1\Verification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Verification\OtpVerificationRequest;
use App\Mail\SendOtp;
use App\Models\Verifier;
use App\Traits\Otp\HasOtp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class OtpController extends Controller
{
    use HasOtp;

    private const OTP_EXPIRY = 5 * 60; //5 minutes

    public function send(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $email = $request->email;

        $verifier = Verifier::firstOrCreate(['email' => $email]);

        $otp = $this->generateOtp();

        $verifier->otps()->create([
            'email' => $email,
            'otp' => $otp,
            'created_at' => now(),
            'expires_at' => now()->addSeconds(self::OTP_EXPIRY),
        ]);

        Mail::to($verifier->email)->send(new SendOtp($otp));

        return new JsonResponse(
            [
                'message' => 'An OTP has been sent to your email.',
            ],
            Response::HTTP_OK
        );
    }

    public function verify(OtpVerificationRequest $request)
    {
        try {
            $verifier = $request->authenticate();

            return new JsonResponse(
                [
                    'message' => 'Otp successfully verified.',
                    'data' => [
                        'id' => $verifier->id,
                        'email' => $verifier->email,
                    ],
                ],
                Response::HTTP_OK
            );
        } catch (\Throwable $th) {

            return new JsonResponse(
                [
                    'message' => $th->getMessage(),
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
