<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'employee_number' => [
                'required',
                'string',
                'exists:employee,employee_number',
                'unique:users,employee_number',
            ],
        ], [
            'employee_number.exists' => 'This employee number was not found. Please contact HR.',
            'employee_number.unique' => 'This employee number is already registered.',
        ])->validate();

        $employee = DB::table('employee')
            ->where('employee_number', $input['employee_number'])
            ->select('last_name', 'first_name', 'middle_name', 'extension')
            ->first();

        $name = trim(implode(' ', array_filter([
            $employee->last_name . ',',
            $employee->first_name,
            $employee->middle_name,
            $employee->extension,
        ])));

        return User::create([
            'name' => $name,
            'username' => $input['username'],
            'email' => $input['email'],
            'password' => $input['password'],
            'employee_number' => $input['employee_number'],
        ]);
    }
}
