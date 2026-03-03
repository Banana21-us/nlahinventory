<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\About;
use App\Livewire\Medicines;
use App\Livewire\PatientManager;
use App\Livewire\PatientDetail;
use App\Livewire\Dashboard;
use App\Livewire\Home;
use App\Livewire\Services;
use App\Livewire\Landing;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
// Route::get('/', function () {
//     return view('welcome');
// })->name('home');

Route::get('/email/verify', function () {
     return view('pages::auth.verify-email'); 
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = User::findOrFail($id);

    // Validate the hash
    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403);
    }

    // Validate the signature
    if (! URL::hasValidSignature($request)) {
        abort(403);
    }

    // Mark as verified if not already
    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
    }

    return redirect()->route('login')->with('status', 'Email verified! Please log in.');
})->middleware('signed')->name('verification.verify');

// HR routes
Route::middleware(['auth', 'verified', 'role:HR'])->prefix('hr')->name('hr.')->group(function () {
    Route::get('/dashboard', fn() => view('pages.hr.dashboard'))->name('dashboard');
    Route::get('/employees', fn() => view('pages.hr.employees'))->name('employees');
    // add more HR routes
});

// Department Head routes
Route::middleware(['auth', 'verified', 'role:Department_Head'])->prefix('department-head')->name('department-head.')->group(function () {
    Route::get('/dashboard', fn() => view('pages.department-head.dashboard'))->name('dashboard');
    // add more department head routes
});

// Staff routes
Route::middleware(['auth', 'verified', 'role:Staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', fn() => view('pages.staff.dashboard'))->name('dashboard');
    // add more staff routes
});

// Maintenance routes
Route::middleware(['auth', 'verified', 'role:Maintenance'])->prefix('maintenance')->name('maintenance.')->group(function () {
    Route::get('/dashboard', fn() => view('pages.maintenance.dashboard'))->name('dashboard');
    // add more maintenance routes
});

// Inspector routes
Route::middleware(['auth', 'verified', 'role:Inspector'])->prefix('inspector')->name('inspector.')->group(function () {
    Route::get('/dashboard', fn() => view('pages.inspector.dashboard'))->name('dashboard');
    // add more inspector routes
});

Route::post('/email/resend', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');
});

// Route::get('/', function () {
//     return redirect()->route('nlah.home');
// });
Route::get('/', function () {
    return redirect()->route('nlah.home');
})->name('home');

Route::get('/hpage', Landing::class)->name('hpage');

Route::get('/landing', Landing::class)->name('landing');
Route::get('medicines', Medicines::class)
    ->middleware(['auth', 'verified'])
    ->name('medicines');  
// Route::get('/',         Home::class)->name('home');

Route::prefix('nlah')->name('nlah.')->group(function () {

    Route::view('/home', 'nlah.home')->name('home');
    Route::view('/about', 'nlah.about')->name('about');
    Route::view('/services', 'nlah.services')->name('services');

});

Route::view('reports', 'reports')
    ->middleware(['auth', 'verified'])
    ->name('reports');    

Route::get('/dashboard', Dashboard::class)->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/patients',PatientManager::class)
    ->middleware(['auth', 'verified'])
    ->name('patients');
Route::get('/dispense', \App\Livewire\DispenseMedicine::class)
    ->middleware(['auth', 'verified'])
    ->name('dispense');

Route::middleware(['auth'])->group(function () {
    Route::redirect('/Maintenance/checklist', '/Maintenance/checklist/check')->name('Maintenance.checklist');
    Route::redirect('/Maintenance/checklist/profile', '/Maintenance/checklist/check');
    Route::livewire('/Maintenance/checklist/check', 'pages::Maintenance.checklist.check')->name('Maintenance.checklist.check');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('/Maintenance/checklist/appearance', 'pages::Maintenance.checklist.appearance')->name('Maintenance.checklist.appearance');
});


Route::get('/patients/{id}', PatientDetail::class)->name('patient.details');

require __DIR__.'/settings.php';
