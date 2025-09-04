<?php

namespace App\Http\Requests\Admin\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normalize incoming data before validation.
     * - If old form still sends "email", map it to "login".
     * - Trim whitespace.
     */
    protected function prepareForValidation(): void
    {
        $login = $this->input('login');

        if ($login === null && $this->filled('email')) {
            $login = $this->input('email');
        }

        if (is_string($login)) {
            $login = trim($login);
        }

        $this->merge([
            'login' => $login,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'login'    => ['required', 'string'], // email OR username
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $rawLogin = (string) $this->input('login');
        $isEmail  = filter_var($rawLogin, FILTER_VALIDATE_EMAIL) !== false;

        $credentials = $isEmail
            ? ['email' => Str::lower($rawLogin), 'password' => $this->input('password')]
            : ['account' => $rawLogin, 'password' => $this->input('password')];

        if (! Auth::guard('admin')->attempt($credentials, $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        $value = (string) $this->input('login', '');
        $normalized = filter_var($value, FILTER_VALIDATE_EMAIL) ? Str::lower($value) : $value;

        return Str::transliterate($normalized) . '|' . $this->ip();
    }
}
