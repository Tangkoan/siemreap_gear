<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

// Start Import
use Illuminate\Support\Facades\Hash;
use App\Models\User;
// End

class LoginRequest extends FormRequest
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
            // 'email' => ['required', 'string', 'email'],
            'login' => ['required', 'string'], // 👉 ត្រូវប្រើ "login" ជំនួស "email"
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    // public function authenticate(): void
    // {
    //     $this->ensureIsNotRateLimited();

    //     // 1. Start សរសេរកូដបន្ថែមក្នុងកន្លែងComment
    //     $user = User::where('email', $this->login)
    //         ->orWhere('name', $this->login)
    //         ->orWhere('phone', $this->login)
    //         ->first();
    //     // End

    //     // 2. Start សរសេរកូដបន្ថែមក្នុងកន្លែងComment
    //     if (!$user || !Hash::check($this->password, $user->password)) {
    //         RateLimiter::hit($this->throttleKey());

    //         throw ValidationException::withMessages([
    //             'login' => trans('auth.failed'),
    //         ]);
    //     }
    //     // End

    //     // 3. Start
    //     Auth::login($user, $this->boolean('remember'));
    //     RateLimiter::clear($this->throttleKey()); // 👉 ត្រូវដាក់ក្រោយ Auth::login
    //     // End
    // }
public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        // បង្កើត Array មួយសម្រាប់ផ្ទុកសារកំហុស
        $messages = [];

        // ជំហានទី១: ស្វែងរកអ្នកប្រើប្រាស់
        $user = User::where('email', $this->login)
            ->orWhere('name', $this->login)
            ->orWhere('phone', $this->login)
            ->first();

        // ជំហានទី២: ពិនិត្យលក្ខខណ្ឌ និងប្រមូលកំហុស
        if (!$user) {
            // ករណីរក User មិនឃើញ (Username ខុស)
            $messages['login'] = 'Incorrect Username';
            
            

        } else {
            // ករណីរក User ឃើញ, យើងពិនិត្យតែ Password
            if (!Hash::check($this->password, $user->password)) {
                $messages['password'] = 'Incorrect Password';
            }
        }

        // ជំហានទី៣: បើមានកំហុសណាមួយនៅក្នុង Array, បង្ហាញវាទាំងអស់
        if (!empty($messages)) {
            RateLimiter::hit($this->throttleKey());
            throw ValidationException::withMessages($messages);
        }

        // ជំហានទី៤: បើគ្មានកំហុស, ដំណើរការ Login
        Auth::login($user, $this->boolean('remember'));
        RateLimiter::clear($this->throttleKey());
    }


    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'login' => trans('auth.throttle', [ // 👉 កែពី 'email' ទៅ 'login'
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
        // 👉 កែពី $this->string('email') ទៅ $this->string('login')
        return Str::transliterate(Str::lower($this->string('login')) . '|' . $this->ip());
    }
}
