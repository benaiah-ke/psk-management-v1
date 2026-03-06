<?php

use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\TrackLastLogin;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', EnsureUserIsAdmin::class, TrackLastLogin::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Dashboard
        Route::get('dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // Profile
        Route::get('profile', fn() => view('admin.profile'))->name('profile');

        // Notifications
        Route::get('notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications');
        Route::post('notifications/mark-all-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
        Route::post('notifications/{id}/mark-read', [\App\Http\Controllers\Admin\NotificationController::class, 'markRead'])->name('notifications.mark-read');

        // Members
        Route::get('members/export', [\App\Http\Controllers\Admin\MemberController::class, 'export'])->name('members.export');
        Route::resource('members', \App\Http\Controllers\Admin\MemberController::class);

        // Applications
        Route::resource('applications', \App\Http\Controllers\Admin\ApplicationController::class)->only(['index', 'show']);
        Route::post('applications/{application}/approve', [\App\Http\Controllers\Admin\ApplicationController::class, 'approve'])->name('applications.approve');
        Route::post('applications/{application}/reject', [\App\Http\Controllers\Admin\ApplicationController::class, 'reject'])->name('applications.reject');

        // Membership Tiers
        Route::resource('tiers', \App\Http\Controllers\Admin\TierController::class);

        // Invoices
        Route::get('invoices/export', [\App\Http\Controllers\Admin\InvoiceController::class, 'export'])->name('invoices.export');
        Route::resource('invoices', \App\Http\Controllers\Admin\InvoiceController::class);
        Route::get('invoices/{invoice}/pdf', [\App\Http\Controllers\Admin\InvoiceController::class, 'pdf'])->name('invoices.pdf');

        // Payments
        Route::resource('payments', \App\Http\Controllers\Admin\PaymentController::class);

        // Cost Centers
        Route::resource('cost-centers', \App\Http\Controllers\Admin\CostCenterController::class);

        // Budgets
        Route::resource('budgets', \App\Http\Controllers\Admin\BudgetController::class);

        // Events
        Route::resource('events', \App\Http\Controllers\Admin\EventController::class);
        Route::post('events/{event}/publish', [\App\Http\Controllers\Admin\EventController::class, 'publish'])->name('events.publish');
        Route::get('events/{event}/registrations', [\App\Http\Controllers\Admin\EventController::class, 'registrations'])->name('events.registrations');
        Route::get('events/{event}/export-registrations', [\App\Http\Controllers\Admin\EventController::class, 'exportRegistrations'])->name('events.export-registrations');
        Route::post('events/{event}/check-in/{registration}', [\App\Http\Controllers\Admin\EventController::class, 'checkIn'])->name('events.check-in');

        // CPD
        Route::prefix('cpd')->name('cpd.')->group(function () {
            Route::resource('activities', \App\Http\Controllers\Admin\CpdActivityController::class)->only(['index', 'show']);
            Route::post('activities/{activity}/verify', [\App\Http\Controllers\Admin\CpdActivityController::class, 'verify'])->name('activities.verify');
            Route::resource('categories', \App\Http\Controllers\Admin\CpdCategoryController::class);
        });

        // Branches
        Route::resource('branches', \App\Http\Controllers\Admin\BranchController::class);

        // Committees
        Route::resource('committees', \App\Http\Controllers\Admin\CommitteeController::class);

        // Communications
        Route::resource('communications', \App\Http\Controllers\Admin\CommunicationController::class)->only(['index', 'create', 'store', 'show']);

        // Email Templates
        Route::resource('email-templates', \App\Http\Controllers\Admin\EmailTemplateController::class);

        // Tickets
        Route::resource('tickets', \App\Http\Controllers\Admin\TicketController::class)->only(['index', 'show', 'update']);
        Route::post('tickets/{ticket}/respond', [\App\Http\Controllers\Admin\TicketController::class, 'respond'])->name('tickets.respond');
        Route::post('tickets/{ticket}/assign', [\App\Http\Controllers\Admin\TicketController::class, 'assign'])->name('tickets.assign');

        // Posts
        Route::resource('posts', \App\Http\Controllers\Admin\PostController::class);

        // Settings
        Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
        Route::put('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
    });
