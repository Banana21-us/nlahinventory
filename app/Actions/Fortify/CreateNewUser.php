<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'username'        => ['required', 'string', 'max:255', 'unique:users'],
            'password'        => $this->passwordRules(),
            'employee_number' => [
                'required',
                'string',
                'exists:employee,employee_number',
                'unique:users,employee_number',
            ],
        ], [
            'employee_number.exists'  => 'This employee number was not found. Please contact HR.',
            'employee_number.unique'  => 'This employee number is already registered.',
        ])->validate();

        return User::create([
            'name'            => $input['name'],
            'username'        => $input['username'],
            'email'           => $input['email'],
            'password'        => $input['password'],
            'employee_number' => $input['employee_number'],
        ]);
    }
}
