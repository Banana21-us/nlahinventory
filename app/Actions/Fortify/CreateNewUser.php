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

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'password' => $this->passwordRules(),
            'employee_number' => [
                'required',
                'string',
                // Must exist in the employee table first
                'exists:employee,employee_number',
                // Must not already be registered in users
                'unique:users,employee_number',
            ],
        ], [
            'employee_number.exists' => 'This employee number was not found. Please contact HR.',
            'employee_number.unique' => 'This employee number is already registered.',
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
            'role'     => 'Staff',
            'employee_number' => $input['employee_number'],
        ]);
    }
}
