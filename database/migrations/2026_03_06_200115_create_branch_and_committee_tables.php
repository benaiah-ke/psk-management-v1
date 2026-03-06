<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('slug', 255)->unique();
            $table->string('code', 20)->unique();
            $table->string('county', 100)->nullable();
            $table->string('region', 100)->nullable();
            $table->text('description')->nullable();
            $table->text('address')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 255)->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('cost_center_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('branch_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role', 30)->default('member');
            $table->date('joined_at');
            $table->date('left_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['branch_id', 'user_id']);
        });

        Schema::create('committees', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('slug', 255)->unique();
            $table->string('code', 20)->unique();
            $table->string('type', 30)->default('standing');
            $table->foreignId('parent_id')->nullable()->constrained('committees')->nullOnDelete();
            $table->text('description')->nullable();
            $table->text('mandate')->nullable();
            $table->date('established_date')->nullable();
            $table->date('dissolution_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('cost_center_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('committee_members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('committee_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role', 30)->default('member');
            $table->date('appointed_at');
            $table->date('term_ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['committee_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('committee_members');
        Schema::dropIfExists('committees');
        Schema::dropIfExists('branch_members');
        Schema::dropIfExists('branches');
    }
};
