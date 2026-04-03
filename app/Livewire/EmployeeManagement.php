<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmploymentDetail;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EmployeeManagement extends Component
{
    public string $search = '';
    public bool $showForm = false;
    public bool $isEditing = false;
    public bool $confirmingDeletion = false;
    public bool $isViewing = false;
    public ?int $selectedId = null;

    // Personal Info (employee table)
    public $employee_number, $user_id, $biometric_id;
    public $last_name, $first_name, $middle_name, $extension;
    public $birth_date, $place_of_birth, $gender = 'Male', $civil_status;
    public $citizenship = 'Filipino', $religion, $blood_type, $height, $weight;
    public $mobile_no, $telephone, $email_add, $p_address, $c_address;
    public $contact_person, $contact_number;

    // Employment Detail (employment_details table)
    public $department_id, $position, $rank;
    public $employment_status = 'Probationary', $hiring_date, $regularization_date;
    public $license_no, $license_expiry, $re_membership = false;
    public $philhealth_no, $pagibig_no, $tin_no, $sss_no, $gsis_no;

    protected function rules(): array
    {
        return [
            'employee_number'    => ['required', 'string', $this->isEditing ? "unique:employee,employee_number,{$this->selectedId}" : 'unique:employee,employee_number'],
            'user_id'            => ['nullable', 'integer', 'exists:users,id'],
            'last_name'          => ['required', 'string', 'max:255'],
            'first_name'         => ['required', 'string', 'max:255'],
            'birth_date'         => ['required', 'date'],
            'gender'             => ['required', 'in:Male,Female'],
            'department_id'      => ['required', 'integer', 'exists:departments,id'],
            'position'           => ['required', 'string', 'max:255'],
            'employment_status'  => ['required', 'in:Probationary,Regular,Contractual,Casual'],
            'hiring_date'        => ['required', 'date'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        DB::transaction(function () {
            $emp = Employee::create([
                'employee_number' => $this->employee_number,
                'user_id'         => $this->user_id ?: null,
                'biometric_id'    => $this->biometric_id ?: null,
                'last_name'       => $this->last_name,
                'first_name'      => $this->first_name,
                'middle_name'     => $this->middle_name,
                'extension'       => $this->extension,
                'birth_date'      => $this->birth_date,
                'place_of_birth'  => $this->place_of_birth,
                'gender'          => $this->gender,
                'civil_status'    => $this->civil_status,
                'citizenship'     => $this->citizenship ?: 'Filipino',
                'religion'        => $this->religion,
                'blood_type'      => $this->blood_type,
                'height'          => $this->height,
                'weight'          => $this->weight,
                'mobile_no'       => $this->mobile_no,
                'telephone'       => $this->telephone,
                'email_add'       => $this->email_add,
                'p_address'       => $this->p_address,
                'c_address'       => $this->c_address,
                'contact_person'  => $this->contact_person,
                'contact_number'  => $this->contact_number,
            ]);

            EmploymentDetail::updateOrCreate(
                ['employee_id' => $emp->id],
                $this->employmentDetailData()
            );
        });

        $this->resetForm();
        session()->flash('message', 'Employee added successfully.');
    }

    public function view(int $id): void
    {
        $this->selectedId = $id;
        $this->isViewing  = true;
    }

    public function edit(int $id): void
    {
        $employee = Employee::findOrFail($id);

        $this->selectedId      = $id;
        $this->employee_number = $employee->employee_number;
        $this->user_id         = $employee->user_id;
        $this->biometric_id    = $employee->biometric_id;
        $this->last_name       = $employee->last_name;
        $this->first_name      = $employee->first_name;
        $this->middle_name     = $employee->middle_name;
        $this->extension       = $employee->extension;
        $this->birth_date      = $employee->birth_date?->format('Y-m-d');
        $this->place_of_birth  = $employee->place_of_birth;
        $this->gender          = $employee->gender;
        $this->civil_status    = $employee->civil_status;
        $this->citizenship     = $employee->citizenship;
        $this->religion        = $employee->religion;
        $this->blood_type      = $employee->blood_type;
        $this->height          = $employee->height;
        $this->weight          = $employee->weight;
        $this->mobile_no       = $employee->mobile_no;
        $this->telephone       = $employee->telephone;
        $this->email_add       = $employee->email_add;
        $this->p_address       = $employee->p_address;
        $this->c_address       = $employee->c_address;
        $this->contact_person  = $employee->contact_person;
        $this->contact_number  = $employee->contact_number;

        $detail = EmploymentDetail::where('employee_id', $employee->id)->first();
        if ($detail) {
            $this->department_id       = $detail->department_id;
            $this->position            = $detail->position;
            $this->rank                = $detail->rank;
            $this->employment_status   = $detail->employment_status;
            $this->hiring_date         = $detail->hiring_date?->format('Y-m-d');
            $this->regularization_date = $detail->regularization_date?->format('Y-m-d');
            $this->license_no          = $detail->license_no;
            $this->license_expiry      = $detail->license_expiry?->format('Y-m-d');
            $this->re_membership       = (bool) $detail->re_membership;
            $this->philhealth_no       = $detail->philhealth_no;
            $this->pagibig_no          = $detail->pagibig_no;
            $this->tin_no              = $detail->tin_no;
            $this->sss_no              = $detail->sss_no;
            $this->gsis_no             = $detail->gsis_no;
        }

        $this->isEditing = true;
    }

    public function update(): void
    {
        $this->validate();

        $employee = Employee::findOrFail($this->selectedId);

        DB::transaction(function () use ($employee) {
            $employee->update([
                'employee_number' => $this->employee_number,
                'user_id'         => $this->user_id ?: null,
                'biometric_id'    => $this->biometric_id ?: null,
                'last_name'       => $this->last_name,
                'first_name'      => $this->first_name,
                'middle_name'     => $this->middle_name,
                'extension'       => $this->extension,
                'birth_date'      => $this->birth_date,
                'place_of_birth'  => $this->place_of_birth,
                'gender'          => $this->gender,
                'civil_status'    => $this->civil_status,
                'citizenship'     => $this->citizenship ?: 'Filipino',
                'religion'        => $this->religion,
                'blood_type'      => $this->blood_type,
                'height'          => $this->height,
                'weight'          => $this->weight,
                'mobile_no'       => $this->mobile_no,
                'telephone'       => $this->telephone,
                'email_add'       => $this->email_add,
                'p_address'       => $this->p_address,
                'c_address'       => $this->c_address,
                'contact_person'  => $this->contact_person,
                'contact_number'  => $this->contact_number,
            ]);

            EmploymentDetail::updateOrCreate(
                ['employee_id' => $employee->id],
                $this->employmentDetailData()
            );
        });

        $this->resetForm();
        session()->flash('message', 'Employee updated successfully.');
    }

    public function confirmDelete(int $id): void
    {
        $this->selectedId         = $id;
        $this->confirmingDeletion = true;
    }

    public function delete(): void
    {
        $employee = Employee::findOrFail($this->selectedId);
        EmploymentDetail::where('employee_id', $employee->id)->delete();
        $employee->delete();
        $this->resetForm();
        session()->flash('message', 'Employee deleted successfully.');
    }

    private function employmentDetailData(): array
    {
        return [
            'department_id'       => $this->department_id,
            'position'            => $this->position,
            'rank'                => $this->rank,
            'employment_status'   => $this->employment_status,
            'hiring_date'         => $this->hiring_date,
            'regularization_date' => $this->regularization_date ?: null,
            'license_no'          => $this->license_no,
            'license_expiry'      => $this->license_expiry ?: null,
            're_membership'       => $this->re_membership,
            'philhealth_no'       => $this->philhealth_no,
            'pagibig_no'          => $this->pagibig_no,
            'tin_no'              => $this->tin_no,
            'sss_no'              => $this->sss_no,
            'gsis_no'             => $this->gsis_no,
        ];
    }

    private function resetForm(): void
    {
        $this->reset([
            'employee_number', 'user_id', 'biometric_id',
            'last_name', 'first_name', 'middle_name', 'extension',
            'birth_date', 'place_of_birth', 'civil_status',
            'religion', 'blood_type', 'height', 'weight',
            'mobile_no', 'telephone', 'email_add', 'p_address', 'c_address',
            'contact_person', 'contact_number',
            'department_id', 'position', 'rank',
            'hiring_date', 'regularization_date',
            'license_no', 'license_expiry',
            'philhealth_no', 'pagibig_no', 'tin_no', 'sss_no', 'gsis_no',
            'selectedId', 'isEditing', 'showForm', 'confirmingDeletion', 'isViewing',
        ]);
        $this->gender            = 'Male';
        $this->citizenship       = 'Filipino';
        $this->employment_status = 'Probationary';
        $this->re_membership     = false;
    }

    public function render()
    {
        $employees = Employee::query()
            ->with(['employmentDetail.department'])
            ->when($this->search, fn ($q) =>
                $q->where(fn ($inner) =>
                    $inner->where('last_name', 'like', "%{$this->search}%")
                          ->orWhere('first_name', 'like', "%{$this->search}%")
                          ->orWhere('employee_number', 'like', "%{$this->search}%")
                )
            )
            ->latest()
            ->get();

        $users       = User::orderBy('name')->get(['id', 'name', 'employee_number']);
        $departments = Department::orderBy('name')->get(['id', 'name', 'code']);

        $viewEmployee = $this->isViewing && $this->selectedId
            ? Employee::with(['employmentDetail.department'])->find($this->selectedId)
            : null;

        return view('pages.HR.employee-management', compact('employees', 'users', 'departments', 'viewEmployee'))
            ->layout('layouts.app');
    }
}
