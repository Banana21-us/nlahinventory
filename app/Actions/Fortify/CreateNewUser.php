<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\Employee;
use App\Models\PayrollAndLeave;
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

        return DB::transaction(function () use ($input, $name) {
            $user = User::create([
                'name' => $name,
                'username' => $input['username'],
                'email' => $input['email'],
                'password' => $input['password'],
                'employee_number' => $input['employee_number'],
            ]);

            // Auto-link the employee record to this new user account
            $emp = Employee::where('employee_number', $input['employee_number'])->first();
            if ($emp) {
                $emp->update(['user_id' => $user->id]);

                // Copy the intended access key from employment_details to the user
                $detail = \App\Models\EmploymentDetail::where('employee_id', $emp->id)->first();
                if ($detail?->access_key_id) {
                    $user->update(['access_key_id' => $detail->access_key_id]);
                }

                // Also backfill user_id on any existing payroll record for this employee
                PayrollAndLeave::where('employee_id', $emp->id)
                    ->whereNull('user_id')
                    ->update(['user_id' => $user->id]);
            }

            return $user;
        });
    }
}
