<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_presets', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary(); // ID fijo definido en BusinessPreset enum
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('description');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_presets');
    }
};
