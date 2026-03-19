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
use App\Http\Controllers\FeedbackController;

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

// User POS routes  
// Route::middleware(['auth', 'verified'])
//     ->prefix('POS') 
//     ->name('POS.')  
//     ->group(function () {
//         Route::get('/dashboard', Dashboard::class)->name('dashboard');
//     });
    Route::get('/pos/dashboard', Posdashboard::class)->name('pos.dashboard');
    Route::get('/pos',POS::class)->name('pos.main');
    Route::get('/pos/inventory', Posinventory::class)->name('pos.inventory');
    Route::get('/pos/items', PosItems::class)->name('pos.items');
    Route::get('/pos/sales', PosSales::class)->name('pos.sales');
    Route::get('/pos/customers', PosCustomer::class)->name('pos.customers');
require __DIR__.'/settings.php';
