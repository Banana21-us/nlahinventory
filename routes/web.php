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
use App\Livewire\HRCorner;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Volt;
use App\Http\Controllers\NewsEventController;
use App\Livewire\HR;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\POSDashboardController;
use App\Http\Controllers\HRCorner\HrdashboardController;
// Route::get('/', function () {
//     return view('welcome');
// })->name('home');

use App\Livewire\PointofSale\Posdashboard;
use App\Livewire\PointofSale\POS;
use App\Livewire\PointofSale\PosInventory;
use App\Livewire\PointofSale\PosItems;
use App\Livewire\PointofSale\PosSales;
use App\Livewire\PointofSale\PosCustomer;
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
Route::middleware(['auth', 'verified', 'can:access-medical'])
    ->prefix('medmission') 
    ->name('medmission.')  
    ->group(function () {
        Route::get('/dashboard', Dashboard::class)->name('dashboard');
        Route::get('/dispense', DispenseMedicine::class)->name('dispense');
        Route::get('/medicines', Medicines::class)->name('medicines');
        Route::get('/patients', PatientManager::class)->name('patients');
        Route::get('/patients/{id}', PatientDetail::class)->name('patient.details');
    });

// HR Routes
Route::middleware('can:access-hr-only')->group(function () {
        Route::get('/HR/news', News::class)->name('NewsPage.newshr');
        Route::get('/HR/userlist', HR::class)->name('HR.userlist');
    });
// Maintenance routes
Route::middleware(['auth','can:access-maintenance'])->group(function () {
    Route::redirect('/Maintenance/checklist', '/Maintenance/checklist/check')->name('Maintenance.checklist');
    Route::livewire('/Maintenance/checklist/check', 'pages::Maintenance.checklist.check')->name('Maintenance.checklist.check');
});
// Verify
Route::middleware(['auth','can:access-verify'])->group(function () {
    Route::redirect('/Maintenance/checklist', '/Maintenance/checklist/check')->name('Maintenance.checklist');
    Route::livewire('/Maintenance/checklist/verify', 'pages::Maintenance.checklist.verify')->name('Maintenance.checklist.verify');
});

// Route::get('/HR-Corner/hrdashboard', [HrdashboardController::class, 'index'])->name('HR-Corner.hrdashboard');
// Route::post('/HR-Corner/approve-leave/{leaveId}', [HrdashboardController::class, 'approveLeave'])->name('HR-Corner.approve-leave');
// Route::post('/HR-Corner/reject-leave/{leaveId}', [HrdashboardController::class, 'rejectLeave'])->name('HR-Corner.reject-leave');

// HR News route - This is the Livewire component route
Route::get('/HR/news', News::class)->middleware(['auth', 'verified'])->name('NewsPage.newshr');
//HR Dashboard
Route::get('/HRCorner/dashboard', HRCorner::class)->middleware(['auth', 'verified'])->name('HR.hrdashboard');
// hr
Route::get('/HR/userlist', HR::class)->middleware(['auth', 'verified'])->name('HR.userlist'); 

Route::get('/medmission/dispense', DispenseMedicine::class)->middleware(['auth', 'verified'])->name('dispense');
Route::get('/medmission/medicines', Medicines::class)->middleware(['auth', 'verified'])->name('medicines');  
Route::get('/medmission/patients',PatientManager::class)->middleware(['auth', 'verified'])->name('patients');
Route::get('/medmission/patients/{id}', PatientDetail::class)->middleware(['auth', 'verified'])->name('patient.details');
Volt::route('POS/posproducts', 'POS/posproducts')->name('POS.posproducts');

// Public NLAH routes
Route::get('/', function () {return redirect()->route('nlah.home');})->name('home');
Route::prefix('nlah')->name('nlah.')->group(function () {
    Route::view('/home', 'nlah.home')->name('home');
    Route::view('/about', 'nlah.about')->name('about');
    Route::view('/services', 'nlah.services')->name('services');
    Route::get('/news', [NewsEventController::class, 'index'])->name('news');
    Route::get('/news/{id}', [NewsEventController::class, 'show'])->name('news.detail');
    Route::get('/news/category/{category}', [NewsEventController::class, 'byCategory'])->name('news.category');
    Route::get('/news/type/{type}', [NewsEventController::class, 'byType'])->name('news.type');
    Route::get('/feedbacks', [FeedbackController::class, 'getFeedbacks'])->name('feedbacks');
    Route::post('/feedback/submit', [FeedbackController::class, 'submit'])->name('feedback.submit');
});

Route::view('reports', 'reports')
    ->middleware(['auth', 'verified'])
    ->name('reports');    

// Maintenance routes
Route::middleware(['auth'])->group(function () {
    Route::redirect('/Maintenance/checklist', '/Maintenance/checklist/check')->name('Maintenance.checklist');
    Route::livewire('/Maintenance/checklist/check', 'pages::Maintenance.checklist.check')->name('Maintenance.checklist.check');
    Route::livewire('/Maintenance/checklist/verify', 'pages::Maintenance.checklist.verify')->name('Maintenance.checklist.verify');
});

require __DIR__.'/settings.php';
