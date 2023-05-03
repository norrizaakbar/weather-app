<?php

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\ActionResponse;

class RegisterNewUser
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', Password::min(8)->mixedCase()->numbers()->symbols()->uncompromised()],
        ];
    }

    public function handle(ActionRequest $request): ActionResponse
    {
        $validatedData = $this->validate($request, $this->rules());

        $user = $this->createUser($validatedData);

        event(new Registered($user));

        Auth::login($user);

        return redirect('/dashboard');
    }

    protected function createUser(array $validatedData)
    {
        return \App\Models\User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'remember_token' => Str::random(10),
        ]);
    }
}
