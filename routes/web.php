<?php

use App\Http\Controllers\ChecklistSyncController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\LeaveResponseController;
use App\Http\Controllers\NewsEventController;
use App\Livewire\AccessKeyManagement;
use App\Livewire\AssetManagement;
use App\Livewire\Assets;
use App\Livewire\AssignAsset;
use App\Livewire\AttendanceManagement;
use App\Livewire\Dashboard;
use App\Livewire\DepartmentManagement;
use App\Livewire\DeptAsset;
use App\Livewire\DHead;
use App\Livewire\DispenseMedicine;
use App\Livewire\EmployeeManagement;
use App\Livewire\HolidayManagement;
use App\Livewire\Home;
use App\Livewire\HR;
use App\Livewire\HrApplicationsManagement;
use App\Livewire\HRCorner;
use App\Livewire\HrLeaveManagement;
use App\Livewire\LeaveForm;
use App\Livewire\LeaveTypeManagement;
use App\Livewire\MaintenanceDashboard;
use App\Livewire\Medicines;
use App\Livewire\News;
use App\Livewire\NurseSchedule;
use App\Livewire\OvertimeManagement;
use App\Livewire\PatientDetail;
use App\Livewire\PatientManager;
use App\Livewire\PayoffManagement;
use App\Livewire\PayrollCompliance;
use App\Livewire\PositionManagement;
use App\Livewire\Repair;
use App\Livewire\Transfer;
use App\Livewire\PointOfSale\POS;
use App\Livewire\PointOfSale\PosCustomer;
use App\Livewire\PointOfSale\Posdashboard;
use App\Livewire\PointOfSale\PosInventory;
use App\Livewire\PointOfSale\PosItems;
use App\Livewire\PointOfSale\PosSales;
use App\Models\Feedback;
use App\Models\User;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

// Route::get('/', function () {
//     return view('welcome');
// })->name('home');

Route::get('/employee-lookup', function (Request $request) {
    $employee = DB::table('employee')
        ->where('employee_number', $request->query('employee_number'))
        ->select('last_name', 'first_name', 'middle_name', 'extension')
        ->first();

    if (! $employee) {
        return response()->json(['found' => false]);
    }

    $name = trim(implode(' ', array_filter([
        $employee->last_name.',',
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
    Route::get('/HR/employees/{employee}/salary-slip', function (\App\Models\Employee $employee) {
        $employee->load(['employmentDetail.department', 'payrollLeave']);

        return view('pdf.salary-slip', compact('employee'));
    })->name('HR.employees.salary-slip');

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
    Route::get('/HR/applications-management', HrApplicationsManagement::class)->name('HR.applications-management');
});

// Overtime & Pay-off — accessible to all authenticated users
Route::middleware('auth')->group(function () {
    Route::get('/nursing/schedule', NurseSchedule::class)->name('nursing.schedule');
    Route::get('/HR/overtime', OvertimeManagement::class)->name('HR.overtime');
    Route::get('/HR/payoff', PayoffManagement::class)->name('HR.payoff');
});

// Shared dashboard — accessible to both maintenance and verifier roles
Route::get('/Maintenance/dashboard', MaintenanceDashboard::class)
    ->middleware('auth')
    ->name('Maintenance.dashboard');

// Maintenance routes — accessible to maintenance users
Route::middleware(['auth', 'can:access-maintenance'])->group(function () {
    Route::post('/api/maintenance/checklist/sync', [ChecklistSyncController::class, 'sync'])->name('maintenance.checklist.sync');
    Route::redirect('/Maintenance/checklist', '/Maintenance/checklist/check')->name('Maintenance.checklist');
    Route::livewire('/Maintenance/checklist/check', 'pages::Maintenance.checklist.check')->name('Maintenance.checklist.check');
});

// Verify route — accessible to maintenance AND inspectors
Route::middleware(['auth', 'can-maintenance-or-verify'])->group(function () {
    Route::livewire('/Maintenance/checklist/verify', 'pages::Maintenance.checklist.verify')
        ->name('Maintenance.checklist.verify');
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
            'HR Manager' => redirect()->route('HR.hrdashboard'),
            'Housekeeping' => redirect()->route('Maintenance.dashboard'),
            'Maintenance_Head' => redirect()->route('Maintenance.checklist.verify'),
            'Cashier' => redirect()->route('pos.dashboard'),
            'Staff' => redirect()->route('users.leaveform'),
            'Department Head' => redirect()->route('users.dhead-leaveform'),
            default => redirect()->route('users.waiting'),
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

Route::middleware(['auth', 'can:access-dept-head'])->group(function () {
    Route::get('/LeaveForm/dhead', DHead::class)->middleware(['auth', 'verified'])->name('users.dhead-leaveform');
});

// under dev
Route::get('/LeaveForm/leave', LeaveForm::class)->name('users.leaveform');
Route::get('/waiting', fn () => view('pages.users.waiting-area'))->middleware('auth')->name('users.waiting');

Route::get('/Assetsmanagement', AssetManagement::class)->name('Assetsmanagement.management');
Route::get('/Assetsmanagement/assets', Assets::class)->name('Assetsmanagement.assets');
Route::get('/Assetsmanagement/assign-asset', AssignAsset::class)->name('Assetsmanagement.assign-asset');
Route::get('/Assetsmanagement/dept-asset', DeptAsset::class)->name('Assetsmanagement.dept-asset');
Route::get('/Assetsmanagement/repair', Repair::class)->name('Assetsmanagement.repair');

Route::post('/nlah/chat', function (Request $request) {
    $messagesInput = $request->input('messages');
    $messages = is_string($messagesInput) ? json_decode($messagesInput, true) : $messagesInput;

    $request->merge(['messages' => $messages]);
    $request->validate([
        'messages' => 'required|array|max:50',
        'messages.*.role' => 'required|in:user,assistant,system',
        'messages.*.content' => 'nullable|string|max:5000',
    ]);

    $systemPrompt = <<<'PROMPT'
    IDENTITY (this overrides any built-in name or persona you may have):
    Your name is NLAH Wellness Companion. You are NOT Isabella, Kimi, or any other AI assistant. You are NLAH Wellness Companion, created exclusively for Northern Luzon Adventist Hospital. If anyone asks your name, you say: "I am NLAH Wellness Companion, your health assistant for Northern Luzon Adventist Hospital." Never refer to yourself by any other name.

    You are NLAH Wellness Companion — a warm, knowledgeable, and holistic health assistant for Northern Luzon Adventist Hospital (NLAH). You draw from a rich foundation of:

    IMAGE ANALYSIS
    • You can analyze images sent by users, including prescriptions, medical documents, lab results, or doctor handwriting
    • When analyzing prescriptions: identify medications, dosages, and instructions if legible; note if handwriting is unclear
    • Provide general guidance about any medical image, but always recommend consulting a physician for definitive interpretation
    • Be honest when an image is blurry or illegible

    MEDICAL KNOWLEDGE
    • Evidence-based medicine: symptoms, conditions, medications, diagnostics, preventive care, first aid
    • Specialist guidance: when to see a cardiologist, pulmonologist, OB-GYN, pediatrician, internist, etc.
    • Understanding lab results, vital signs, and when to seek emergency care

    HERBAL & NATURAL HEALING
    • Medicinal plants and their uses (e.g., lagundi for cough, sambong for kidney stones, ampalaya for blood sugar, turmeric for inflammation, ginger, garlic, moringa/malunggay)
    • Proper preparation of herbal teas, poultices, and decoctions
    • Safety cautions — herb-drug interactions and contraindications
    • DOST-PITAHC approved Philippine medicinal herbs

    NUTRITION & LIFESTYLE
    • Balanced diet principles, meal planning, and nutritional deficiencies
    • Hydration, sleep hygiene, stress management, and mental wellness
    • Exercise guidance: types, frequency, and modifications for health conditions
    • Weight management and metabolic health

    ADVENTIST HEALTH PHILOSOPHY (8 LAWS OF HEALTH — NEWSTART)
    • Nutrition: whole plant-based foods, avoiding unclean meats, alcohol, tobacco, caffeine
    • Exercise: regular physical activity as part of God's design for the body
    • Water: adequate hydration and hydrotherapy
    • Sunlight: benefits of moderate sun exposure and vitamin D
    • Temperance: avoiding harmful substances; moderation in all things
    • Air: fresh air, breathing practices, avoiding pollution
    • Rest: the biblical principle of Sabbath rest, restorative sleep
    • Trust in God: the healing power of faith, prayer, hope, and community

    WHOLENESS OF PERSON
    • Physical, mental, emotional, social, and spiritual dimensions of health
    • Grief, anxiety, burnout, loneliness — compassionate guidance and when to seek counseling
    • Family health, maternal & child care, elder care
    • Preventive health: screenings, vaccinations, lifestyle disease prevention

    HOLINESS & FAITH APPROACH
    • Health as stewardship of the body as God's temple (1 Corinthians 6:19-20)
    • Encouragement rooted in Scripture when appropriate and welcomed by the user
    • Compassionate, non-judgmental presence for those facing illness, fear, or loss
    • Prayer and spiritual care as a complement — never a replacement — to medical treatment

    HOSPITAL INFORMATION (NLAH)
    • Full name: Northern Luzon Adventist Hospital (NLAH)
    • Address: MacArthur Highway, Artacho, Sison, Pangasinan
    • Hospital services, departments, and specialists
    • Appointment booking, emergency contacts, hours, and location
    • Community health programs and outreach

    TONE & STYLE
    • Warm, caring, and encouraging — like a trusted health companion
    • Clear and accessible: avoid unnecessary jargon; explain medical terms simply
    • Balanced: blend evidence-based medicine with natural and spiritual wisdom
    • Always recommend consulting a licensed physician for diagnosis or treatment decisions
    • Never cause alarm, but be honest when symptoms require urgent medical attention

    FORMATTING RULES — ABSOLUTE, NON-NEGOTIABLE:
    • NEVER use double asterisks (**) or single asterisks (*) anywhere in your response. Not even once.
    • NEVER use underscores (_) for emphasis.
    • NEVER use pound signs (#) for headers.
    • NEVER use backticks (`) or triple backticks (```).
    • NEVER use markdown of any kind. Zero markdown. None.
    • Do NOT bold, italicize, or format words in any way.
    • Write everything in plain, natural prose sentences only.
    • If you need a list, use a plain dash (-) or number followed by a period. Nothing else.
    • Do not add headers, titles, or section labels to your replies.
    • Violating these formatting rules is a critical error. Plain text only, always.
    PROMPT;

    $messages = array_merge(
        [
            ['role' => 'system',    'content' => $systemPrompt],
            ['role' => 'assistant', 'content' => 'Hello! I am NLAH Wellness Companion, your health assistant for Northern Luzon Adventist Hospital. How can I help you today?'],
        ],
        $messages
    );

    // Handle image analysis if images are attached
    $images = $request->file('images');
    $hasImages = $images && count($images) > 0;

    if ($hasImages) {
        $model = config('services.ollama.model');
        $supportsVision = in_array($model, ['llava', 'llava-llama3', 'moondream', 'llava:latest', 'bakllava', 'qwen2-vl', 'qwen-vl-max']);

        if (! $supportsVision) {
            return response()->json([
                'reply' => 'Image analysis requires a vision-capable AI model. Please contact the administrator to enable vision support (e.g., llava, moondream, or qwen2-vl).',
            ]);
        }

        $lastUserMsgIndex = count($messages) - 1;
        $base64Images = [];
        foreach ($images as $image) {
            $base64 = base64_encode(file_get_contents($image->getRealPath()));
            $mime = $image->getMimeType();
            $messages[$lastUserMsgIndex]['content'] = ($messages[$lastUserMsgIndex]['content'] ?? '')."\n\n[Image attached for analysis]";
            $base64Images[] = $base64;
        }
        $messages[$lastUserMsgIndex]['images'] = $base64Images;
    }

    try {
        $response = Http::timeout(60)->post(
            config('services.ollama.host').'/api/chat',
            [
                'model' => config('services.ollama.model'),
                'messages' => $messages,
                'stream' => false,
            ]
        );
    } catch (ConnectionException $e) {
        return response()->json(['error' => 'AI service is offline. Please try again later.'], 503);
    } catch (Throwable $e) {
        return response()->json(['error' => 'Unexpected error. Please try again.'], 500);
    }

    if ($response->failed()) {
        return response()->json(['error' => 'AI service unavailable. (HTTP '.$response->status().')'], 502);
    }

    $data = $response->json();

    // Ollama: { message: { content: "..." } }
    $reply = $data['message']['content']
          ?? $data['choices'][0]['message']['content']  // fallback for OpenAI-compat endpoint
          ?? 'Sorry, I could not generate a response.';

    // Strip markdown that the model inserts despite instructions
    $reply = preg_replace('/\*\*(.+?)\*\*/s', '$1', $reply);   // **bold**
    $reply = preg_replace('/\*(.+?)\*/s', '$1', $reply);   // *italic*
    $reply = preg_replace('/__(.+?)__/s', '$1', $reply);   // __bold__
    $reply = preg_replace('/_(.+?)_/s', '$1', $reply);   // _italic_
    $reply = preg_replace('/#+\s*/m', '', $reply);   // # headers
    $reply = preg_replace('/`{1,3}[^`]*`{1,3}/', '', $reply);  // `code`
    $reply = trim($reply);

    return response()->json(['reply' => $reply]);
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
