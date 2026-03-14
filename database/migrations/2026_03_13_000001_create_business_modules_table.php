<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('platform_module_id');
            $table->foreign('platform_module_id')
                ->references('id')->on('platform_modules')->cascadeOnDelete();
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->unique(['business_id', 'platform_module_id'], 'bm_business_module_unique');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_modules');
    }
};
