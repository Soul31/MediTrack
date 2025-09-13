<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\{
    AuthController,
    PatientController,
    pharmacienController,
    CommandeController,
    OrdonnanceController,
    DocteurController
};
use App\Http\Middleware\{
    EnsurePharmacienHasLicence,
    PharmacienMiddleware,
    PatientMiddleware,
    DocteurMiddleware
};
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

// Route for the welcome page
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

// Route for the welcome screen
Route::get('/welcome', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('welcome');

// Route to handle login form submission
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Route for the login page
Route::get('/login', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('login');

// Route for the dashboard page (accessible to users)
Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user->role === 'pharmacien') {
        $pharmacien = \App\Models\Pharmacien::where('user_id', $user->id)->first();
        if (!$pharmacien || empty($pharmacien->licence)) {
            return redirect()->route('licence.form');
        }
        // Call the dashboard method of pharmacienController
        return app(PharmacienController::class)->dashboard();
    } else if ($user->role === 'docteur') {
        return redirect()->route('docteur-medicaments');
    } else if ($user->role === 'admin') {
        return redirect('/admin');
    } else {
        return redirect()->route('patient-point-de-vente');
    }
})->middleware(['auth', 'verified'])->name('dashboard');

// Route for the register page
Route::get('/register', function () {
    return view('register');
})->name('register');;
// Route to handle register form submission
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// Route for the stocklist page (accessible only to pharmaciens)
Route::get('/stocklist', [pharmacienController::class, 'stocklist'])->middleware(['auth', 'pharmacien','pharmacien.licence'])->name('stocklist');

// Route for the newproduct page (accessible only to pharmaciens)
Route::get('/newproduct', function () {
    return view('pharmacien.newproduct');
})->middleware(['auth', 'pharmacien','pharmacien.licence'])->name('newproduct');

Route::post('/newproduct', [PharmacienController::class, 'storeNewProduct'])
    ->middleware(['auth', 'pharmacien','pharmacien.licence'])
    ->name('newproduct.store');

// Route for the commandes page (accessible only to pharmaciens)
Route::get('/commandes', [pharmacienController::class, 'commandes'])
    ->middleware(['auth', 'pharmacien', 'pharmacien.licence'])
    ->name('commandes');

// Route to handle logout
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

// Route for the change password page (accessible only to authenticated users)
Route::get('/change-password', function () {
    return view('user.password');
})->middleware('auth')->name('change-password');

// Route to handle change password form submission
Route::post('/change-password', [AuthController::class, 'changePassword'])->middleware('auth')->name('change-password.submit');

// Route for the update profile page (accessible only to authenticated users)
Route::get('/update-profile', function () {
    return view('user.updateProfile');
})->middleware('auth')->name('update-profile');

Route::get('/profile', function () {
    if (Auth::user()->role === 'pharmacien') {
        return view('pharmacien.profile');
    } else {
        return view('patient.profile');
    }
})->middleware('auth')->name('profile');

// Route for the liscence form page (accessible only to pharmaciens)
Route::get('/licence-form', function () {
    return view('pharmacien.licenceForm');
})->middleware(['auth', 'pharmacien'])->name('licence.form');

// Route to handle licence form submission (accessible only to pharmaciens)
Route::post('/licence-form', [pharmacienController::class, 'submitLicence']
)->middleware(['auth', 'pharmacien'])->name('licence.form.submit');

// Route to export commandes as PDF (accessible only to pharmaciens)
Route::get('/commandes/export/pdf', [pharmacienController::class, 'exportPdf'])
    ->middleware(['auth', 'pharmacien', 'pharmacien.licence'])
    ->name('commandes.export.pdf');

// Route to export medicaments as PDF (accessible only to pharmaciens)
Route::get('/medicaments/export/pdf', [pharmacienController::class, 'exportMedicamentsPdf'])
    ->middleware(['auth', 'pharmacien', 'pharmacien.licence'])
    ->name('medicaments.export.pdf');

// Route to see the details of a specific commande (accessible only to pharmaciens)
Route::get('/commandes/{id}', [pharmacienController::class, 'showCommande'])
    ->middleware(['auth', 'pharmacien', 'pharmacien.licence'])
    ->name('detailCommande');

// Route for the commandes page (accessible only to pharmaciens)
Route::get('/make-sale', [PharmacienController::class, 'makeSale'])
    ->middleware(['auth', 'pharmacien', 'pharmacien.licence'])
    ->name('makeSale');

Route::post('/make-sale', [PharmacienController::class, 'storeSale'])
    ->middleware(['auth', 'pharmacien', 'pharmacien.licence'])
    ->name('makeSale.store');

Route::delete('/stocks/{id}', [PharmacienController::class, 'deleteStock'])
    ->middleware(['auth', 'pharmacien', 'pharmacien.licence'])
    ->name('stocks.delete');

Route::post('/commandes/change-status', [PharmacienController::class, 'changeStatus'])->name('commandes.changeStatus');

Route::post('/medicaments', [PharmacienController::class, 'storeMedicaments'])
    ->middleware(['auth', 'pharmacien', 'pharmacien.licence'])
    ->name('medicament.store');

Route::post('/stocks/{id}/update', [PharmacienController::class, 'updateStock'])
    ->middleware(['auth', 'pharmacien', 'pharmacien.licence'])
    ->name('stocks.update');

Route::post('/profile/update', [AuthController::class, 'updateProfile'])
    ->middleware('auth')
    ->name('profile.update');

// patient routes

Route::middleware(['auth', 'patient'])->prefix('patient')->group(function () {
    Route::get('/medicaments', [PatientController::class, 'medicaments'])->name('patient-medicaments');
    Route::get('/commandes', [PatientController::class, 'commandes'])->name('patient-commandes');
    Route::get('/ordonnances', [PatientController::class, 'ordonnances'])->name('patient-ordonnances');
    Route::get('/point-de-vente', [PatientController::class, 'point_de_vente'])->name('patient-point-de-vente');

    Route::post('/commande', [CommandeController::class, 'storeCommande'])->name('patient-commande-store');
    Route::get('/commande/{commande}', [CommandeController::class, 'index'])->name('patient-commande');
    Route::delete('/commande/annuler/{commande}', [CommandeController::class, 'delete'])->name('patient-delete-commande');

    Route::get('/ordonnance/{ordonnance}', [OrdonnanceController::class, 'index'])->name('patient-ordonnance');
    Route::delete('/ordonnance/annuler/{ordonnance}', [OrdonnanceController::class, 'delete'])->name('patient-delete-ordonnance');
});

// Docteur Routes

Route::middleware(['auth', 'docteur'])->prefix('docteur')->group(function () {
    Route::get('/medicaments', [DocteurController::class, 'medicaments'])->name('docteur-medicaments');
    Route::get('/ordonnances', [DocteurController::class, 'ordonnances'])->name('docteur-ordonnances');
    Route::get('/ordonnance/{ordonnance}', [DocteurController::class, 'ordonnance'])->name('docteur-ordonnance');
    Route::delete('/ordonnance/annuler/{ordonnance}', [OrdonnanceController::class, 'delete'])->name('docteur-delete-ordonnance');

    Route::get('/nouvelle-ordonnance', [OrdonnanceController::class, 'newOrdonnance'])->name('docteur-ordonnance-new');
    Route::post('/ordonnance', [OrdonnanceController::class, 'store'])->name('docteur-ordonnance-store');
});

// Show verification notice
Route::get('/email/verify', function () {
    return view('verify-email');
})->middleware('auth')->name('verification.notice');

// Handle verification link
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/dashboard');
})->middleware(['auth', 'signed'])->name('verification.verify');

// Resend verification email
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

