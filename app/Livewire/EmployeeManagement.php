<?php

namespace App\Livewire;

use App\Models\AccessKey;
use App\Models\Department;
use App\Models\Dependency;
use App\Models\Employee;
use App\Models\EmploymentDetail;
use App\Models\PayrollAndLeave;
use App\Models\Position;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class EmployeeManagement extends Component
{
    public string $search = '';

    public bool $showForm = false;

    public bool $isEditing = false;

    public bool $confirmingDeletion = false;

    public bool $isViewing = false;

    public ?int $selectedId = null;

    // Personal Info (employee table)
    public $employee_number;

    public $user_id;

    public $biometric_id;

    public $last_name;

    public $first_name;

    public $middle_name;

    public $extension;

    public $birth_date;

    public $place_of_birth;

    public $gender = 'Male';

    public $civil_status;

    public $citizenship = 'Filipino';

    public $religion;

    public $blood_type;

    public $height;

    public $weight;

    public $mobile_no;

    public $telephone;

    public $email_add;

    public $p_address;

    public $c_address;

    public $contact_person;

    public $contact_number;

    public $contact_relationship;

    // Employment Detail (employment_details table)
    public $department_id;

    public $position; // slash-joined string stored in DB, e.g. "HR Manager/Staff"

    public array $selectedPositions = []; // checkbox state — source of truth in the form

    public ?int $access_key_id = null; // assigned access key (saved to employment_details + linked user)

    public $rank;

    public $employment_status = 'Probationary';

    public $hiring_date;

    public $regularization_date;

    public $license_no;

    public $license_expiry;

    public $re_membership = '';

    public $philhealth_no;

    public $pagibig_no;

    public $tin_no;

    public $sss_no;

    public $gsis_no;

    // Finance (payroll_and_leaves table)
    public $salary_rate;

    public $daily_rate;

    public $monthly_rate;

    public $cola;

    public $grocery_allowance;

    public $night_diff_factor;

    public $vl_total;

    public $vl_consumed;

    public $sl_total;

    public $sl_consumed;

    public $spl_total;

    public $el_total;

    public $min_scale;

    public $max_scale;

    public $wage_factor;

    public $po_consumed;

    public $po_total;

    public $probi_rate;

    public $picture;

    public $signature;

    // Dependents
    public $dependents = [];

    public $new_dependent = [];

    protected function rules(): array
    {
        return [
            'employee_number' => ['required', 'string', $this->isEditing ? "unique:employee,employee_number,{$this->selectedId}" : 'unique:employee,employee_number'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'gender' => ['required', 'in:Male,Female'],
            'department_id' => ['required', 'integer', 'exists:departments,id'],
            'selectedPositions' => ['required', 'array', 'min:1'],
            'selectedPositions.*' => ['string', 'max:255'],
            'employment_status' => ['required', 'in:Probationary,Regular,Contractual,Casual'],
            'hiring_date' => ['required', 'date'],
        ];
    }

    public function save(): void
    {
        $this->validate();

        DB::transaction(function () {
            $emp = Employee::create([
                'employee_number' => $this->employee_number,
                'user_id' => $this->user_id ?: null,
                'biometric_id' => $this->biometric_id ?: null,
                'last_name' => $this->last_name,
                'first_name' => $this->first_name,
                'middle_name' => $this->middle_name,
                'extension' => $this->extension,
                'birth_date' => $this->birth_date,
                'place_of_birth' => $this->place_of_birth,
                'gender' => $this->gender,
                'civil_status' => $this->civil_status,
                'citizenship' => $this->citizenship ?: 'Filipino',
                'religion' => $this->religion,
                'blood_type' => $this->blood_type,
                'height' => $this->height,
                'weight' => $this->weight,
                'mobile_no' => $this->mobile_no,
                'telephone' => $this->telephone,
                'email_add' => $this->email_add,
                'p_address' => $this->p_address,
                'c_address' => $this->c_address,
                'contact_person' => $this->contact_person,
                'contact_number' => $this->contact_number,
                'contact_relationship' => $this->contact_relationship ?? null,
            ]);

            EmploymentDetail::updateOrCreate(
                ['employee_id' => $emp->id],
                $this->employmentDetailData()
            );

            $this->saveFinance($emp->id, $this->user_id ?: null);
            $this->saveDependents($emp->id);
            // Propagate access key to the linked user account
            if ($this->user_id && $this->access_key_id) {
                User::where('id', $this->user_id)->update(['access_key_id' => $this->access_key_id]);
            }
        });

        $this->resetForm();
        session()->flash('message', 'Employee added successfully.');
    }

    public function view(int $id): void
    {
        $this->selectedId = $id;
        $this->isViewing = true;
    }

    public function edit(int $id): void
    {
        $employee = Employee::findOrFail($id);

        $this->selectedId = $id;
        $this->employee_number = $employee->employee_number;
        $this->user_id = $employee->user_id;
        $this->biometric_id = $employee->biometric_id;
        $this->last_name = $employee->last_name;
        $this->first_name = $employee->first_name;
        $this->middle_name = $employee->middle_name;
        $this->extension = $employee->extension;
        $this->birth_date = $employee->birth_date?->format('Y-m-d');
        $this->place_of_birth = $employee->place_of_birth;
        $this->gender = $employee->gender;
        $this->civil_status = $employee->civil_status;
        $this->citizenship = $employee->citizenship;
        $this->religion = $employee->religion;
        $this->blood_type = $employee->blood_type;
        $this->height = $employee->height;
        $this->weight = $employee->weight;
        $this->mobile_no = $employee->mobile_no;
        $this->telephone = $employee->telephone;
        $this->email_add = $employee->email_add;
        $this->p_address = $employee->p_address;
        $this->c_address = $employee->c_address;
        $this->contact_person = $employee->contact_person;
        $this->contact_number = $employee->contact_number;
        $this->contact_relationship = $employee->contact_relationship;

        $detail = EmploymentDetail::where('employee_id', $employee->id)->first();
        if ($detail) {
            $this->department_id = $detail->department_id;
            $this->position = $detail->position;
            $this->selectedPositions = array_filter(explode('/', $detail->position ?? ''));
            $this->access_key_id = $detail->access_key_id ?? $employee->user?->access_key_id;
            $this->rank = $detail->rank;
            $this->employment_status = $detail->employment_status;
            $this->hiring_date = $detail->hiring_date?->format('Y-m-d');
            $this->regularization_date = $detail->regularization_date?->format('Y-m-d');
            $this->license_no = $detail->license_no;
            $this->license_expiry = $detail->license_expiry?->format('Y-m-d');
            $this->re_membership = $detail->re_membership;
            $this->philhealth_no = $detail->philhealth_no;
            $this->pagibig_no = $detail->pagibig_no;
            $this->tin_no = $detail->tin_no;
            $this->sss_no = $detail->sss_no;
            $this->gsis_no = $detail->gsis_no;
        }

        $payroll = PayrollAndLeave::where('employee_id', $employee->id)->first()
            ?? ($employee->user_id ? PayrollAndLeave::where('user_id', $employee->user_id)->first() : null);
        if ($payroll) {
            $this->salary_rate = $payroll->salary_rate;
            $this->daily_rate = $payroll->daily_rate;
            $this->monthly_rate = $payroll->monthly_rate;
            $this->cola = $payroll->cola;
            $this->grocery_allowance = $payroll->grocery_allowance;
            $this->night_diff_factor = $payroll->night_diff_factor;
            $this->vl_total = $payroll->vl_total;
            $this->vl_consumed = $payroll->vl_consumed;
            $this->sl_total = $payroll->sl_total;
            $this->sl_consumed = $payroll->sl_consumed;
            $this->spl_total = $payroll->spl_total;
            $this->el_total = $payroll->el_total;
            $this->min_scale = $payroll->min_scale;
            $this->max_scale = $payroll->max_scale;
            $this->wage_factor = $payroll->wage_factor;
            $this->po_consumed = $payroll->po_consumed;
            $this->po_total = $payroll->po_total;
            $this->probi_rate = $payroll->probi_rate;
        }

        $this->dependents = Dependency::where('employee_id', $employee->id)
            ->get()
            ->map(fn ($d) => [
                'id' => $d->id,
                'lastname' => $d->lastname,
                'firstname' => $d->firstname,
                'middlename' => $d->middlename,
                'extension' => $d->extension,
                'relationship' => $d->relationship,
                'gender' => $d->gender,
                'birthday' => $d->birthday?->format('Y-m-d'),
                'age' => $d->age,
            ])->toArray();

        $this->isEditing = true;
    }

    public function addDependent(): void
    {
        if (! empty($this->new_dependent['lastname']) && ! empty($this->new_dependent['firstname'])) {
            $this->dependents[] = [
                'id' => null,
                'lastname' => $this->new_dependent['lastname'],
                'firstname' => $this->new_dependent['firstname'],
                'middlename' => $this->new_dependent['middlename'] ?? '',
                'extension' => $this->new_dependent['extension'] ?? '',
                'relationship' => $this->new_dependent['relationship'] ?? '',
                'gender' => $this->new_dependent['gender'] ?? 'Male',
                'birthday' => $this->new_dependent['birthday'] ?? '',
                'age' => $this->new_dependent['age'] ?? 0,
            ];
            $this->new_dependent = [];
        }
    }

    public function removeDependent(int $index): void
    {
        unset($this->dependents[$index]);
        $this->dependents = array_values($this->dependents);
    }

    protected function saveFinance(int $employeeId, ?int $userId): void
    {
        PayrollAndLeave::updateOrCreate(
            ['employee_id' => $employeeId],
            array_filter([
                'user_id' => $userId,
            ], fn ($v) => $v !== null) + [
                'salary_rate' => $this->salary_rate ?: 0,
                'daily_rate' => $this->daily_rate ?: 0,
                'monthly_rate' => $this->monthly_rate ?: 0,
                'cola' => $this->cola ?: 0,
                'grocery_allowance' => $this->grocery_allowance ?: 0,
                'night_diff_factor' => $this->night_diff_factor ?: 1.10,
                'vl_total' => $this->vl_total ?: 0,
                'vl_consumed' => $this->vl_consumed ?: 0,
                'sl_total' => $this->sl_total ?: 0,
                'sl_consumed' => $this->sl_consumed ?: 0,
                'spl_total' => $this->spl_total ?: 0,
                'el_total' => $this->el_total ?: 0,
                'min_scale' => $this->min_scale ?: 0,
                'max_scale' => $this->max_scale ?: 0,
                'wage_factor' => $this->wage_factor ?: 1.00,
                'po_consumed' => $this->po_consumed ?: 0,
                'po_total' => $this->po_total ?: 0,
                'probi_rate' => $this->probi_rate ?: 1.00,
            ]
        );
    }

    protected function saveDependents(int $employeeId): void
    {
        foreach ($this->dependents as $dep) {
            Dependency::updateOrCreate(
                ['id' => $dep['id']],
                [
                    'employee_id' => $employeeId,
                    'lastname' => $dep['lastname'],
                    'firstname' => $dep['firstname'],
                    'middlename' => $dep['middlename'] ?: null,
                    'extension' => $dep['extension'] ?: null,
                    'relationship' => $dep['relationship'],
                    'gender' => $dep['gender'],
                    'birthday' => $dep['birthday'],
                    'age' => $dep['age'] ?: 0,
                ]
            );
        }
    }

    public function update(): void
    {
        $this->validate();

        $employee = Employee::findOrFail($this->selectedId);

        DB::transaction(function () use ($employee) {
            $employee->update([
                'employee_number' => $this->employee_number,
                'user_id' => $this->user_id ?: null,
                'biometric_id' => $this->biometric_id ?: null,
                'last_name' => $this->last_name,
                'first_name' => $this->first_name,
                'middle_name' => $this->middle_name,
                'extension' => $this->extension,
                'birth_date' => $this->birth_date,
                'place_of_birth' => $this->place_of_birth,
                'gender' => $this->gender,
                'civil_status' => $this->civil_status,
                'citizenship' => $this->citizenship ?: 'Filipino',
                'religion' => $this->religion,
                'blood_type' => $this->blood_type,
                'height' => $this->height,
                'weight' => $this->weight,
                'mobile_no' => $this->mobile_no,
                'telephone' => $this->telephone,
                'email_add' => $this->email_add,
                'p_address' => $this->p_address,
                'c_address' => $this->c_address,
                'contact_person' => $this->contact_person,
                'contact_number' => $this->contact_number,
                'contact_relationship' => $this->contact_relationship ?? null,
            ]);

            EmploymentDetail::updateOrCreate(
                ['employee_id' => $employee->id],
                $this->employmentDetailData()
            );

            $this->saveFinance($employee->id, $this->user_id ?: null);
            $this->saveDependents($employee->id);
            // Propagate access key to the linked user account
            if ($this->user_id && $this->access_key_id) {
                User::where('id', $this->user_id)->update(['access_key_id' => $this->access_key_id]);
            }
        });

        $this->resetForm();
        session()->flash('message', 'Employee updated successfully.');
    }

    public function confirmDelete(int $id): void
    {
        $this->selectedId = $id;
        $this->confirmingDeletion = true;
    }

    public function delete(): void
    {
        $employee = Employee::findOrFail($this->selectedId);
        EmploymentDetail::where('employee_id', $employee->id)->delete();
        Dependency::where('employee_id', $employee->id)->delete();
        if ($employee->user_id) {
            PayrollAndLeave::where('user_id', $employee->user_id)->delete();
        }
        $employee->delete();
        $this->resetForm();
        session()->flash('message', 'Employee deleted successfully.');
    }

    private function employmentDetailData(): array
    {
        // Join multi-select positions into slash-separated string for storage
        $positionStr = implode('/', array_filter($this->selectedPositions));

        return [
            'department_id' => $this->department_id,
            'position' => $positionStr ?: ($this->position ?: ''),
            'access_key_id' => $this->access_key_id ?: null,
            'rank' => $this->rank,
            'employment_status' => $this->employment_status,
            'hiring_date' => $this->hiring_date,
            'regularization_date' => $this->regularization_date ?: null,
            'license_no' => $this->license_no,
            'license_expiry' => $this->license_expiry ?: null,
            're_membership' => $this->re_membership,
            'philhealth_no' => $this->philhealth_no,
            'pagibig_no' => $this->pagibig_no,
            'tin_no' => $this->tin_no,
            'sss_no' => $this->sss_no,
            'gsis_no' => $this->gsis_no,
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
            'contact_person', 'contact_number', 'contact_relationship',
            'department_id', 'position', 'selectedPositions', 'access_key_id', 'rank',
            'hiring_date', 'regularization_date',
            'license_no', 'license_expiry',
            'philhealth_no', 'pagibig_no', 'tin_no', 'sss_no', 'gsis_no',
            'salary_rate', 'daily_rate', 'monthly_rate', 'cola', 'grocery_allowance',
            'night_diff_factor', 'vl_total', 'vl_consumed', 'sl_total', 'sl_consumed',
            'spl_total', 'el_total', 'min_scale', 'max_scale', 'wage_factor',
            'po_consumed', 'po_total', 'probi_rate',
            'dependents', 'new_dependent',
            'selectedId', 'isEditing', 'showForm', 'confirmingDeletion', 'isViewing',
        ]);
        $this->gender = 'Male';
        $this->citizenship = 'Filipino';
        $this->employment_status = 'Probationary';
        $this->re_membership = '';
    }

    public function render()
    {
        $employees = Employee::query()
            ->with(['employmentDetail.department'])
            ->when($this->search, fn ($q) => $q->where(fn ($inner) => $inner->where('last_name', 'like', "%{$this->search}%")
                ->orWhere('first_name', 'like', "%{$this->search}%")
                ->orWhere('employee_number', 'like', "%{$this->search}%")
            )
            )
            ->latest()
            ->get();

        $users = User::orderBy('name')->get(['id', 'name', 'employee_number']);
        $departments = Department::orderBy('name')->get(['id', 'name', 'code']);
        $positions = Position::orderBy('name')->get(['id', 'name', 'code']);
        $accessKeys = AccessKey::orderBy('name')->get(['id', 'name', 'description']);

        $viewEmployee = $this->isViewing && $this->selectedId
            ? Employee::with(['employmentDetail.department'])->find($this->selectedId)
            : null;

        return view('pages.HR.employee-management', compact('employees', 'users', 'departments', 'positions', 'accessKeys', 'viewEmployee'))
            ->layout('layouts.app');
    }
}
