<?php

namespace App\Http\Requests\Verification;

use App\Models\Otp;
use App\Models\User;
use App\Models\Verifier;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OtpVerificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'email', 'exists:verifiers,email'],
            'otp' => ['required', 'string'],
        ];
    }

    public function authenticate(): Verifier|User
    {
        $this->ensureIsNotRateLimited();
        $otp = Otp::whereEmail($this->email)->latest()->first();
        $verifier = $otp?->verifiable;

        if (! $verifier || ($this->otp !== $otp->otp)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'otp' => 'Invalid OTP.',
            ]);
        }

        if ($otp?->used_at) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'otp' => 'This OTP is already used.',
            ]);
        }

        if (now()->gt($otp->expires_at)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'otp' => 'This OTP is expired.',
            ]);
        }

        RateLimiter::clear($this->throttleKey());

        $otp->update(['used_at' => now()]);

        return $verifier;
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited()
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey()
    {
        return Str::transliterate(Str::lower($this->input('email')).'|'.$this->ip());
    }
}
