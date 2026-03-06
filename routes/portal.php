<?php

use App\Http\Middleware\TrackLastLogin;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', TrackLastLogin::class])
    ->prefix('portal')
    ->name('portal.')
    ->group(function () {
        // Dashboard
        Route::get('dashboard', \App\Http\Controllers\Portal\DashboardController::class)->name('dashboard');

        // Profile
        Route::get('profile', [\App\Http\Controllers\Portal\ProfileController::class, 'edit'])->name('profile');
        Route::put('profile', [\App\Http\Controllers\Portal\ProfileController::class, 'update'])->name('profile.update');
        Route::put('profile/password', [\App\Http\Controllers\Portal\ProfileController::class, 'updatePassword'])->name('profile.password');

        // Membership
        Route::get('membership', [\App\Http\Controllers\Portal\MembershipController::class, 'index'])->name('membership');
        Route::get('membership/apply', [\App\Http\Controllers\Portal\MembershipController::class, 'apply'])->name('membership.apply');
        Route::post('membership/apply', [\App\Http\Controllers\Portal\MembershipController::class, 'submitApplication'])->name('membership.submit');
        Route::get('membership/renew', [\App\Http\Controllers\Portal\MembershipController::class, 'renew'])->name('membership.renew');
        Route::post('membership/renew', [\App\Http\Controllers\Portal\MembershipController::class, 'submitRenewal'])->name('membership.submit-renewal');
        Route::get('membership/certificate/{certificate}', [\App\Http\Controllers\Portal\MembershipController::class, 'certificate'])->name('membership.certificate');

        // Events
        Route::get('events', [\App\Http\Controllers\Portal\EventController::class, 'index'])->name('events.index');
        Route::get('events/{event}', [\App\Http\Controllers\Portal\EventController::class, 'show'])->name('events.show');
        Route::post('events/{event}/register', [\App\Http\Controllers\Portal\EventController::class, 'register'])->name('events.register');
        Route::get('events/registrations/my', [\App\Http\Controllers\Portal\EventController::class, 'myRegistrations'])->name('events.my-registrations');

        // CPD
        Route::get('cpd', [\App\Http\Controllers\Portal\CpdController::class, 'index'])->name('cpd.index');
        Route::get('cpd/log', [\App\Http\Controllers\Portal\CpdController::class, 'create'])->name('cpd.create');
        Route::post('cpd/log', [\App\Http\Controllers\Portal\CpdController::class, 'store'])->name('cpd.store');
        Route::get('cpd/certificate/{certificate}', [\App\Http\Controllers\Portal\CpdController::class, 'certificate'])->name('cpd.certificate');

        // Invoices
        Route::get('invoices', [\App\Http\Controllers\Portal\InvoiceController::class, 'index'])->name('invoices.index');
        Route::get('invoices/{invoice}', [\App\Http\Controllers\Portal\InvoiceController::class, 'show'])->name('invoices.show');
        Route::get('invoices/{invoice}/pdf', [\App\Http\Controllers\Portal\InvoiceController::class, 'pdf'])->name('invoices.pdf');

        // Tickets
        Route::resource('tickets', \App\Http\Controllers\Portal\TicketController::class)->only(['index', 'create', 'store', 'show']);
        Route::post('tickets/{ticket}/respond', [\App\Http\Controllers\Portal\TicketController::class, 'respond'])->name('tickets.respond');

        // Notifications
        Route::get('notifications', [\App\Http\Controllers\Portal\NotificationController::class, 'index'])->name('notifications');
        Route::post('notifications/mark-all-read', [\App\Http\Controllers\Portal\NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
        Route::post('notifications/{id}/mark-read', [\App\Http\Controllers\Portal\NotificationController::class, 'markRead'])->name('notifications.mark-read');

        // Community
        Route::get('community', [\App\Http\Controllers\Portal\CommunityController::class, 'index'])->name('community.index');
        Route::get('community/{post}', [\App\Http\Controllers\Portal\CommunityController::class, 'show'])->name('community.show');
        Route::post('community/{post}/comment', [\App\Http\Controllers\Portal\CommunityController::class, 'comment'])->name('community.comment');
    });
