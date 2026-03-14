<?php

use App\Enums\BusinessStatus;
use App\Enums\BusinessType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('ruc', 11)->nullable()->unique();
            $table->string('phone', 20)->nullable();
            $table->string('address')->nullable();
            $table->enum('type', array_column(BusinessType::cases(), 'value'))
                  ->default(BusinessType::Retail->value);
            $table->enum('status', array_column(BusinessStatus::cases(), 'value'))
                  ->default(BusinessStatus::Active->value);
            $table->string('logo_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
