<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PanditRegistrationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PanditDashboardController;
use App\Http\Controllers\KundliController;
use App\Http\Controllers\EbookController;
use App\Http\Controllers\FestivalController;
use App\Http\Controllers\RashiController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\LegalController;
use App\Http\Controllers\PageController;

// ─── Public Routes ──────────────────────────────────────
Route::get('/', [PageController::class, 'home'])->name('home');

// ─── Google Auth ────────────────────────────────────────
Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('auth.google.callback');

// ─── Auth Routes (Guest only) ───────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Pandit Registration
    Route::get('/register/pandit', [PanditRegistrationController::class, 'showForm'])->name('pandit.register');
    Route::post('/register/pandit', [PanditRegistrationController::class, 'register'])->name('pandit.register.submit');

    // Forgot / Reset Password
    Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

    // Admin Login
    Route::get('/admin/login', [PageController::class, 'adminLogin'])->name('admin.login');
    Route::post('/admin/login', [AuthController::class, 'adminLogin'])->name('admin.login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Public Spiritual Features ─────────────────────────
Route::get('/pandits/search', [SearchController::class, 'index'])->name('pandit.search');
Route::get('/kundli', [KundliController::class, 'showForm'])->name('kundli.form');
Route::post('/kundli/generate', [KundliController::class, 'generate'])->name('kundli.generate');
Route::get('/ebooks', [EbookController::class, 'index'])->name('ebooks.index');
Route::get('/ebooks/{ebook}', [EbookController::class, 'show'])->name('ebooks.show');
Route::get('/festivals', [FestivalController::class, 'index'])->name('festivals.index');
Route::get('/festivals/{festival}', [FestivalController::class, 'show'])->name('festivals.show');
Route::get('/rashi', [RashiController::class, 'index'])->name('rashi.index');
Route::get('/rashi/{rashi}', [RashiController::class, 'show'])->name('rashi.show');
Route::get('/sitemap.xml', [LegalController::class, 'sitemap'])->name('sitemap');
Route::get('/{slug}', [LegalController::class, 'show'])
    ->whereIn('slug', ['privacy-policy', 'terms-and-conditions', 'refund-policy', 'cancellation-policy', 'trademark-notice', 'disclaimer', 'help-center', 'faq', 'customer-support', 'email-support'])
    ->name('legal.show');

// ─── Pandit Pending Page ───────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/pandit/pending', [PanditRegistrationController::class, 'pendingPage'])->name('pandit.pending');
});

// ─── Authenticated Routes ──────────────────────────────
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
    Route::get('/user', [PageController::class, 'userDashboard'])->name('user.dashboard');
    Route::get('/my-bookings', [PageController::class, 'userBookings'])->name('my.bookings');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    // Kundli history
    Route::get('/kundli/{kundli}', [KundliController::class, 'show'])->name('kundli.show');
    Route::get('/kundli/{kundli}/pdf', [KundliController::class, 'downloadPdf'])->name('kundli.pdf');

    // Booking
    Route::get('/booking/{pandit}', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking/store/{pandit}', [BookingController::class, 'store'])->name('booking.store');

    // Payment
    Route::get('/payment/{booking}', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::post('/payment/verify', [PaymentController::class, 'verify'])->name('payment.verify');
    Route::post('/payment/demo/{booking}', [PaymentController::class, 'processDemoPayment'])->name('payment.demo');
    Route::get('/payment/success/{booking}', [PaymentController::class, 'success'])->name('payment.success');

    // Chat (post-booking)
    Route::get('/chat/{booking}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{booking}/send', [ChatController::class, 'send'])->name('chat.send');
    Route::get('/chat/{booking}/messages', [ChatController::class, 'fetchMessages'])->name('chat.messages');

    // Slot availability API
    Route::get('/api/slots/{pandit}', [PanditDashboardController::class, 'getSlots'])->name('api.slots');
});

// ─── Pandit Dashboard Routes (auth + pandit.approved) ──
Route::middleware(['auth', 'pandit.approved'])->prefix('pandit')->group(function () {
    Route::get('/dashboard', [PanditDashboardController::class, 'dashboard'])->name('pandit.dashboard');

    Route::get('/bookings', [PanditDashboardController::class, 'bookings'])->name('pandit.bookings');
    Route::post('/bookings/{booking}/accept', [PanditDashboardController::class, 'acceptBooking'])->name('pandit.booking.accept');
    Route::post('/bookings/{booking}/reject', [PanditDashboardController::class, 'rejectBooking'])->name('pandit.booking.reject');
    Route::post('/bookings/{booking}/complete', [PanditDashboardController::class, 'completeBooking'])->name('pandit.booking.complete');

    Route::get('/availability', [PanditDashboardController::class, 'availability'])->name('pandit.availability');
    Route::post('/availability', [PanditDashboardController::class, 'saveAvailability'])->name('pandit.availability.save');
    Route::post('/availability/block', [PanditDashboardController::class, 'blockDate'])->name('pandit.availability.block');
    Route::delete('/availability/unblock/{blockedDate}', [PanditDashboardController::class, 'unblockDate'])->name('pandit.availability.unblock');

    Route::get('/calendar', [PanditDashboardController::class, 'calendar'])->name('pandit.calendar');
    Route::get('/calendar/events', [PanditDashboardController::class, 'calendarEvents'])->name('pandit.calendar.events');

    Route::get('/earnings', [PanditDashboardController::class, 'earnings'])->name('pandit.earnings');
    Route::get('/reviews', [PanditDashboardController::class, 'reviews'])->name('pandit.reviews');
});

// ─── Admin Routes (auth + admin middleware) ────────────
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/users/{user}/status', [AdminController::class, 'toggleUserStatus'])->name('admin.user.status');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.user.delete');

    Route::get('/pandits', [AdminController::class, 'pandits'])->name('admin.pandits');
    Route::get('/pandits/pending', [PageController::class, 'pendingPandits'])->name('admin.pandits.pending');
    Route::get('/review/{pandit}', [AdminController::class, 'reviewPandit'])->name('admin.review');
    Route::post('/approve/{pandit}', [AdminController::class, 'approvePandit'])->name('admin.approve');
    Route::post('/reject/{pandit}', [AdminController::class, 'rejectPandit'])->name('admin.reject');
    Route::post('/suspend/{pandit}', [AdminController::class, 'suspendPandit'])->name('admin.suspend.pandit');

    Route::get('/bookings', [AdminController::class, 'bookings'])->name('admin.bookings');
    Route::post('/bookings/{booking}/cancel', [AdminController::class, 'cancelBooking'])->name('admin.booking.cancel');

    Route::get('/payments', [AdminController::class, 'payments'])->name('admin.payments');
});
