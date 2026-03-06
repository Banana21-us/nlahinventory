<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\About;
use App\Livewire\Medicines;
use App\Livewire\News;
use App\Livewire\PatientManager;
use App\Livewire\PatientDetail;
use App\Livewire\Dashboard;
use App\Livewire\DispenseMedicine;
use App\Livewire\Home;
use App\Livewire\Services;
use App\Livewire\Landing;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Volt;
use App\Http\Controllers\NewsEventController;
use App\Livewire\HR;
// Route::get('/', function () {
//     return view('welcome');
// })->name('home');

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
Route::middleware(['auth', 'verified'])->prefix('medmission')->name('medmission.')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
});

// HR News route - This is the Livewire component route
Route::get('/HR/news', News::class)->middleware(['auth', 'verified'])->name('NewsPage.newshr');
// hr
Route::get('/HR/userlist', HR::class)->middleware(['auth', 'verified'])->name('HR.userlist'); 

Route::get('/medmission/dispense', DispenseMedicine::class)->middleware(['auth', 'verified'])->name('dispense');
Route::get('/medmission/medicines', Medicines::class)->middleware(['auth', 'verified'])->name('medicines');  
Route::get('/medmission/patients',PatientManager::class)->middleware(['auth', 'verified'])->name('patients');
Route::get('/medmission/patients/{id}', PatientDetail::class)->middleware(['auth', 'verified'])->name('patient.details');

// Public NLAH routes
Route::get('/', function () {return redirect()->route('nlah.home');})->name('home');

Route::prefix('nlah')->name('nlah.')->group(function () {
    Route::view('/home', 'nlah.home')->name('home');
    Route::view('/about', 'nlah.about')->name('about');
    Route::view('/services', 'nlah.services')->name('services');

    // News routes using NewsEventController for public pages
    Route::get('/news', [NewsEventController::class, 'index'])->name('news');
    Route::get('/news/{id}', [NewsEventController::class, 'show'])->name('news.detail');
    Route::get('/news/category/{category}', [NewsEventController::class, 'byCategory'])->name('news.category');
    Route::get('/news/type/{type}', [NewsEventController::class, 'byType'])->name('news.type');
});

Route::view('reports', 'reports')
    ->middleware(['auth', 'verified'])
    ->name('reports');    

// Maintenance routes
Route::middleware(['auth'])->group(function () {
    Route::redirect('/Maintenance/checklist', '/Maintenance/checklist/check')->name('Maintenance.checklist');
    Route::redirect('/Maintenance/checklist/profile', '/Maintenance/checklist/check');
    Route::livewire('/Maintenance/checklist/check', 'pages::Maintenance.checklist.check')->name('Maintenance.checklist.check');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::livewire('/Maintenance/checklist/appearance', 'pages::Maintenance.checklist.appearance')->name('Maintenance.checklist.appearance');
});

require __DIR__.'/settings.php';