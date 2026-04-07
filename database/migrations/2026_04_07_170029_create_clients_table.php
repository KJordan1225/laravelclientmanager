<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('client_code')->unique();
            $table->string('industry')->nullable();
            $table->string('website')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state', 100)->nullable();
            $table->string('postal_code', 30)->nullable();
            $table->string('country', 100)->nullable();
            $table->enum('status', ['active', 'inactive', 'lead'])->default('lead');
            $table->decimal('hourly_rate', 10, 2)->nullable();
            $table->decimal('credit_limit', 12, 2)->nullable();
            $table->date('onboarded_at')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['company_name', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
