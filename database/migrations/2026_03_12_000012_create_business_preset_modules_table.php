<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_preset_modules', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('business_preset_id');
            $table->unsignedInteger('platform_module_id');
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['business_preset_id', 'platform_module_id'], 'bpm_preset_module_unique');

            $table->foreign('business_preset_id')
                ->references('id')->on('business_presets')
                ->cascadeOnDelete();

            $table->foreign('platform_module_id')
                ->references('id')->on('platform_modules')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_preset_modules');
    }
};
