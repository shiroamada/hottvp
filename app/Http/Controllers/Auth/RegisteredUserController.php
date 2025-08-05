<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin\AdminUser;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.AdminUser::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = AdminUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'pid' => 0,
            'level_id' => 0,
            'channel_id' => 0,
            'account' => $request->email, // Using email as account for simplicity, adjust as needed
            'phone' => '',
            'status' => AdminUser::STATUS_ENABLE,
            'is_cancel' => 0,
            'balance' => 0.00,
            'recharge' => 0.00,
            'profit' => 0.00,
            'photo' => '',
            'remark' => '',
            'remember_token' => null,
            'is_new' => 1,
            'is_relation' => 1,
            'type' => 1,
            'person_num' => 0,
            'try_num' => 0,
            'language' => 'en',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
