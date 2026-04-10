<?php

use App\Http\Controllers\ChecklistSyncController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\LeaveResponseController;
use App\Http\Controllers\NewsEventController;
use App\Livewire\AccessKeyManagement;
use App\Livewire\AttendanceManagement;
use App\Livewire\HolidayManagement;
use App\Livewire\LeaveTypeManagement;
use App\Livewire\OvertimeManagement;
use App\Livewire\PayoffManagement;
use App\Livewire\Dashboard;
use App\Livewire\DepartmentManagement;
use App\Livewire\DHead;
use App\Livewire\DispenseMedicine;
use App\Livewire\EmployeeManagement;
use App\Livewire\Home;
use App\Livewire\HR;
use App\Livewire\HRCorner;
use App\Livewire\HrLeaveManagement;
use App\Livewire\LeaveForm;
use App\Livewire\MaintenanceDashboard;
use App\Livewire\Medicines;
use App\Livewire\News;
use App\Livewire\PatientDetail;
use App\Livewire\PatientManager;
use App\Livewire\PayrollCompliance;
use App\Livewire\PositionManagement;
use App\Livewire\PointOfSale\POS;
use App\Livewire\PointOfSale\PosCustomer;
use App\Livewire\PointOfSale\Posdashboard;
use App\Livewire\PointOfSale\PosInventory;
use App\Livewire\PointOfSale\PosItems;
use App\Livewire\PointOfSale\PosSales;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

// Route::get('/', function () {
//     return view('welcome');
// })->name('home');

Route::get('/employee-lookup', function (Request $request) {
    $employee = \Illuminate\Support\Facades\DB::table('employee')
        ->where('employee_number', $request->query('employee_number'))
        ->select('last_name', 'first_name', 'middle_name', 'extension')
        ->first();

    if (! $employee) {
        return response()->json(['found' => false]);
    }

    $name = trim(implode(' ', array_filter([
        $employee->last_name . ',',
        $employee->first_name,
        $employee->middle_name,
        $employee->extension,
    ])));

    return response()->json(['found' => true, 'name' => $name]);
})->name('employee.lookup');

// Email verification routes
Route::get('/email/verify', function () {
    return view('pages::auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = User::findOrFail($id);
    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403);
    }

    if (! URL::hasValidSignature($request)) {
        abort(403);
    }

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }

    return redirect()->route('login')->with('status', 'Email verified! Please log in.');
})->middleware('signed')->name('verification.verify');

Route::post('/email/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Staff routes
// Route::middleware(['auth', 'verified', 'can:access-medical'])
//     ->prefix('medmission')
//     ->name('medmission.')
//     ->group(function () {
//         Route::get('/dashboard', Dashboard::class)->name('dashboard');
//         Route::get('/dispense', DispenseMedicine::class)->name('dispense');
//         Route::get('/medicines', Medicines::class)->name('medicines');
//         Route::get('/patients', PatientManager::class)->name('patients');
//         Route::get('/patients/{id}', PatientDetail::class)->name('patient.details');
//     });

// HR Routes
Route::middleware('can:access-hr-only')->group(function () {
    Route::get('/HR/news', News::class)->name('NewsPage.newshr');
    Route::get('/HR/userlist', HR::class)->name('HR.userlist');
    Route::get('/HR/hr-leave-management', HrLeaveManagement::class)->name('HR.hr-leave-management');
    Route::get('/HR/hrdashboard', HRCorner::class)->name('HR.hrdashboard');
    Route::get('/HR/payroll-compliance', PayrollCompliance::class)->name('HR.payroll-compliance');
    Route::get('/HR/employees', EmployeeManagement::class)->name('HR.employees');
    Route::get('/HR/attendance', AttendanceManagement::class)->name('HR.attendance');
    Route::get('/HR/departments', DepartmentManagement::class)->name('HR.departments');
    Route::get('/HR/positions', PositionManagement::class)->name('HR.positions');
    Route::get('/HR/access-keys', AccessKeyManagement::class)->name('HR.access-keys');
    Route::get('/HR/holidays', HolidayManagement::class)->name('HR.holidays');
    Route::get('/HR/leave-types', LeaveTypeManagement::class)->name('HR.leave-types');
    Route::get('/HR/overtime', OvertimeManagement::class)->name('HR.overtime');
    Route::get('/HR/payoff', PayoffManagement::class)->name('HR.payoff');
});

// Maintenance routes
Route::middleware(['auth', 'can:access-maintenance'])->group(function () {
    Route::post('/api/maintenance/checklist/sync', [ChecklistSyncController::class, 'sync'])->name('maintenance.checklist.sync');
    Route::get('/Maintenance/dashboard', MaintenanceDashboard::class)->name('Maintenance.dashboard');
    Route::redirect('/Maintenance/checklist', '/Maintenance/checklist/check')->name('Maintenance.checklist');
    Route::livewire('/Maintenance/checklist/check', 'pages::Maintenance.checklist.check')->name('Maintenance.checklist.check');
    Route::livewire('/Maintenance/checklist/verify', 'pages::Maintenance.checklist.verify')->name('Maintenance.checklist.verify');
});
// Verify routes
Route::middleware(['auth', 'can:access-verify'])->group(function () {
    Route::redirect('/Maintenance/checklist', '/Maintenance/checklist/check')->name('Maintenance.checklist');
    Route::livewire('/Maintenance/checklist/verify', 'pages::Maintenance.checklist.verify')->name('Maintenance.checklist.verify');
});

// Cashier routes
Route::middleware(['auth', 'can:access-cashier-only'])->group(function () {
    Route::get('/pos/dashboard', Posdashboard::class)->name('pos.dashboard');
    Route::get('/pos', POS::class)->name('pos.main');
    Route::get('/pos/inventory', PosInventory::class)->name('pos.inventory');
    Route::get('/pos/items', PosItems::class)->name('pos.items');
    Route::get('/pos/sales', PosSales::class)->name('pos.sales');
    Route::get('/pos/customers', PosCustomer::class)->name('pos.customers');
});

// Public NLAH routes — redirect authenticated users to their dashboard
Route::get('/', function () {
    if (auth()->check()) {
        $position = auth()->user()->employmentDetail?->position;

        return match ($position) {
            'HR Manager'       => redirect()->route('HR.hrdashboard'),
            'Housekeeping'     => redirect()->route('Maintenance.dashboard'),
            'Maintenance_Head' => redirect()->route('Maintenance.checklist.verify'),
            'Cashier'          => redirect()->route('pos.dashboard'),
            'Staff'            => redirect()->route('users.leaveform'),
            default            => redirect()->route('users.waiting'),
        };
    }

    return redirect()->route('nlah.home');
})->name('home');
Route::prefix('nlah')->name('nlah.')->group(function () {
    Route::view('/home', 'nlah.home')->name('home');
    Route::view('/about', 'nlah.about')->name('about');
    Route::view('/services', 'nlah.services')->name('services');
    // News routes using NewsEventController for public pages
    Route::get('/news', [NewsEventController::class, 'index'])->name('news');
    Route::get('/news/{id}', [NewsEventController::class, 'show'])->name('news.detail');
    Route::get('/news/category/{category}', [NewsEventController::class, 'byCategory'])->name('news.category');
    Route::get('/news/type/{type}', [NewsEventController::class, 'byType'])->name('news.type');

    // Feedback route
    Route::get('/feedbacks', [FeedbackController::class, 'getFeedbacks'])->name('feedbacks');
    Route::post('/feedback/submit', [FeedbackController::class, 'submit'])->name('feedback.submit');
});
// Department Head leave approval/rejection via signed email link (no auth required)
Route::get('/leave/{leave}/respond/{action}', [LeaveResponseController::class, 'respond'])
    ->name('leave.dhead.respond')
    ->middleware('signed');

// under dev
Route::get('/LeaveForm/leave', LeaveForm::class)->name('users.leaveform');
Route::get('/LeaveForm/dhead', DHead::class)->middleware(['auth', 'verified'])->name('users.dhead-leaveform');
Route::get('/waiting', fn () => view('pages.users.waiting-area'))->middleware('auth')->name('users.waiting');


Route::post('/nlah/chat', function (Request $request) {
    $request->validate([
        'messages' => 'required|array|max:50',
        'messages.*.role' => 'required|in:user,assistant',
        'messages.*.content' => 'required|string|max:2000',
    ]);

    $systemPrompt = "You are a friendly virtual assistant for Northern Luzon Adventist Hospital (NLAH). 
Keep responses brief, warm, and helpful. You can help with:
- Hospital services and departments
- Appointment booking guidance
- Emergency contacts
- Hospital hours and location
- General health inquiries
Always recommend consulting a doctor for medical advice.";

    $messages = array_merge(
        [['role' => 'system', 'content' => $systemPrompt]],
        $request->input('messages')
    );

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . config('services.openrouter.key'),
        'HTTP-Referer'  => config('app.url'),
        'X-Title'       => config('app.name'),
        'Content-Type'  => 'application/json',
    ])->post('https://openrouter.ai/api/v1/chat/completions', [
        'model'    => 'google/gemini-2.0-flash-exp:free', // free model, change if needed
        'messages' => $messages,
        'max_tokens' => 500,
    ]);

    if ($response->failed()) {
        return response()->json([
            'error' => 'AI service unavailable. Please try again.'
        ], 502);
    }

    $data = $response->json();

    return response()->json([
        'reply' => $data['choices'][0]['message']['content'] ?? 'Sorry, I could not generate a response.'
    ]);
})->middleware('throttle:30,1'); // 30 requests per minute per user


Route::post('/nlah/feedback/submit', function (Request $request) {
    $request->validate([
        'name'    => 'nullable|string|max:100',
        'comment' => 'required|string|max:2000',
        'rating'  => 'required|integer|min:1|max:5',
    ]);

    // Save to DB — make sure you have a feedbacks table
    // Run: php artisan make:model Feedback -m
    // Migration columns: name, comment, rating (tinyint), ip_address
    \App\Models\Feedback::create([
        'name'       => $request->input('name', 'Guest'),
        'comment'    => $request->input('comment'),
        'rating'     => $request->input('rating'),
        'ip_address' => $request->ip(),
    ]);

    return response()->json(['success' => true]);
});

require __DIR__.'/settings.php';
