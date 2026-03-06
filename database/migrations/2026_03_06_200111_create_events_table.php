<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title', 255);
            $table->string('slug', 255)->unique();
            $table->text('description')->nullable();
            $table->string('short_description', 500)->nullable();
            $table->string('type', 30)->default('other');
            $table->string('status', 30)->default('draft');
            $table->string('venue_name', 255)->nullable();
            $table->text('venue_address')->nullable();
            $table->boolean('is_virtual')->default(false);
            $table->string('virtual_link', 500)->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->dateTime('registration_opens')->nullable();
            $table->dateTime('registration_closes')->nullable();
            $table->unsignedInteger('max_attendees')->nullable();
            $table->foreignId('cost_center_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('cpd_points')->default(0);
            $table->string('featured_image_path', 500)->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('event_ticket_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('name', 255);
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->unsignedInteger('quantity')->nullable();
            $table->unsignedInteger('sold_count')->default(0);
            $table->dateTime('sale_starts')->nullable();
            $table->dateTime('sale_ends')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ticket_type_id')->constrained('event_ticket_types');
            $table->string('registration_number', 50)->unique();
            $table->string('status', 20)->default('pending');
            $table->decimal('amount_paid', 12, 2)->default(0);
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->string('qr_code_data', 500)->nullable();
            $table->timestamp('checked_in_at')->nullable();
            $table->foreignId('checked_in_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('dietary_requirements', 255)->nullable();
            $table->text('special_needs')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->unique(['event_id', 'user_id']);
        });

        Schema::create('event_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->string('speaker', 255)->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('venue', 255)->nullable();
            $table->unsignedInteger('cpd_points')->default(0);
            $table->unsignedInteger('max_attendees')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('event_sponsors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('name', 255);
            $table->string('company', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('tier', 30)->default('other');
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('logo_path', 500)->nullable();
            $table->string('website_url', 500)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_confirmed')->default(false);
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->timestamps();
        });

        Schema::create('event_surveys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->json('questions');
            $table->boolean('is_active')->default(true);
            $table->dateTime('opens_at')->nullable();
            $table->dateTime('closes_at')->nullable();
            $table->timestamps();
        });

        Schema::create('event_survey_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_survey_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->json('responses');
            $table->timestamp('submitted_at');
            $table->timestamps();
            $table->unique(['event_survey_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_survey_responses');
        Schema::dropIfExists('event_surveys');
        Schema::dropIfExists('event_sponsors');
        Schema::dropIfExists('event_sessions');
        Schema::dropIfExists('event_registrations');
        Schema::dropIfExists('event_ticket_types');
        Schema::dropIfExists('events');
    }
};
