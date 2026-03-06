<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cpd_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('slug', 255)->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('max_points_per_year')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('cpd_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('cpd_category_id')->constrained();
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->unsignedInteger('points');
            $table->date('activity_date');
            $table->string('source', 20)->default('manual');
            $table->unsignedBigInteger('event_id')->nullable();
            $table->string('status', 20)->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->string('evidence_file_path', 500)->nullable();
            $table->text('rejection_reason')->nullable();
            $table->unsignedSmallInteger('period_year');
            $table->timestamps();
        });

        Schema::create('cpd_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('certificate_number', 50)->unique();
            $table->unsignedSmallInteger('period_year');
            $table->unsignedInteger('total_points');
            $table->unsignedInteger('required_points');
            $table->string('file_path', 500)->nullable();
            $table->date('issued_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cpd_certificates');
        Schema::dropIfExists('cpd_activities');
        Schema::dropIfExists('cpd_categories');
    }
};
