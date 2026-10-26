<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Auth\ConsentController;
use App\Http\Controllers\Auth\EmailOtpController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Settings\ThemeController;
use App\Http\Controllers\Student\PinSessionController;
use App\Livewire\OnboardingWizard;
use App\Livewire\Quiz;
use App\Livewire\Tutor;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| TOTO Arena routes
|--------------------------------------------------------------------------
|
| Route names below match design-system/README.md's list exactly. None of
| these are gated behind auth middleware yet — see that README's "ข้อควรระวัง"
| section: there's no seeder, no finished onboarding flow end-to-end, and
| most page data is still fixture data (TODO comments in each view). Add
| auth middleware per role once there's an actual seeded account to test
| against; right now gating would just lock the pages behind a flow that
| can't be completed yet.
|
*/

Route::get('/', fn () => redirect()->route('login'));

// --- Guardian auth (Fortify owns register/login/password.* — wired in
// FortifyServiceProvider) + the custom pieces Fortify doesn't cover ---
Route::get('/signup-login', fn () => view('auth.form', ['tab' => request('tab', 'signup')]))->name('auth.form');
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);

Route::get('/verify-email', fn () => view('auth.verify-email'))->name('verification.notice');
Route::post('/verify-email/send-otp', [EmailOtpController::class, 'send'])->name('verification.send-otp');
Route::post('/verify-email/verify-otp', [EmailOtpController::class, 'verify'])->name('verification.verify-otp');

Route::get('/consent', fn () => view('auth.consent'))->name('consent.show');
Route::post('/consent', [ConsentController::class, 'store'])->name('consent.accept');

Route::livewire('/onboarding', OnboardingWizard::class)->name('onboarding');

// --- Student PIN session (separate guard from guardians — see config/auth.php) ---
Route::post('/student/pin-verify', [PinSessionController::class, 'store'])->name('student.pin.verify');
Route::post('/student/logout', [PinSessionController::class, 'destroy'])->name('student.logout');

// --- Theme (shared by both roles) ---
Route::patch('/theme', [ThemeController::class, 'update'])->name('theme.update');

// --- Admin (site-wide, not family-scoped — see User::isAdmin()) ---
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
});

// --- Student pages ---
Route::get('/arena', fn () => view('student.home'))->name('student.home');
Route::get('/learn', fn () => view('student.learn'))->name('student.learn');
Route::livewire('/tutor', Tutor::class)->name('student.tutor');
Route::livewire('/quiz', Quiz::class)->name('student.quiz');
Route::get('/quests', fn () => view('student.quests'))->name('student.quests');
Route::get('/report', fn () => view('student.report'))->name('student.report');
Route::get('/hall-of-fame', fn () => view('student.hall-of-fame'))->name('student.hof');
Route::get('/shop', fn () => view('student.shop'))->name('student.shop');
Route::get('/student/settings', fn () => view('settings.index', ['role' => 'student']))->name('student.settings');

Route::post('/learn/request-summary', fn () => back()->with('status', 'TODO: ยังไม่ต่อคิวตรวจของผู้ปกครอง'))
    ->name('student.learn.request-summary');
Route::post('/quests/{quest}/claim', fn ($quest) => back()->with('status', 'TODO: ยังไม่ต่อ Game Engine'))
    ->name('student.quests.claim');
Route::post('/shop/redeem', fn () => back()->with('status', 'TODO: ยังไม่ต่อระบบแลกรางวัล'))
    ->name('student.shop.redeem');

// --- Parent pages ---
Route::get('/parent', fn () => view('parent.dashboard'))->name('parent.dashboard');
Route::get('/parent/content', fn () => view('parent.content'))->name('parent.content');
Route::get('/parent/sourcing', fn () => view('parent.sourcing'))->name('parent.sourcing');
Route::get('/parent/rewards', fn () => view('parent.rewards'))->name('parent.rewards');
Route::get('/parent/settings', fn () => view('settings.index', ['role' => 'parent']))->name('parent.settings');

Route::get('/parent/dashboard/export', fn () => back()->with('status', 'TODO: ยังไม่ต่อ CSV export'))
    ->name('parent.dashboard.export');
Route::get('/parent/content/item-bank', fn () => back()->with('status', 'TODO: ยังไม่ต่อคลังข้อสอบ'))
    ->name('parent.content.item-bank');
Route::post('/parent/content/upload', fn () => back()->with('status', 'TODO: ยังไม่ต่อระบบอัปโหลด'))
    ->name('parent.content.upload');
Route::post('/parent/rewards/{redemption}/decide', fn ($redemption) => back()->with('status', 'TODO: ยังไม่ต่อระบบอนุมัติ'))
    ->name('parent.rewards.decide');

// --- Settings actions (both roles) ---
Route::post('/settings/preferences', fn () => back()->with('status', 'TODO: ยังไม่ต่อการบันทึกค่าตั้งต้น'))
    ->name('settings.preferences');
Route::post('/settings/google/toggle', fn () => back()->with('status', 'TODO: ยังไม่ต่อ Socialite unlink/relink'))
    ->name('settings.google.toggle');
Route::post('/settings/two-factor/toggle', fn () => back()->with('status', 'TODO: 2FA ปิดใช้งานอยู่ (ไม่มีหน้า QR) — ดู config/fortify.php'))
    ->name('settings.two-factor.toggle');
Route::post('/settings/sessions/logout-all', fn () => back()->with('status', 'TODO: ยังไม่ต่อ device_sessions'))
    ->name('settings.sessions.logout-all');
