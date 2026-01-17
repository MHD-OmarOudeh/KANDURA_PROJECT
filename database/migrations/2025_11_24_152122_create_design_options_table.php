<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('design_options', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            // ✅ FIXED: Removed enum dependency
            $table->enum('type', ['color', 'dome_type', 'fabric_type', 'sleeve_type']);
            $table->string('color_code')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
            $table->softDeletes(); // ✅ Added

            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('design_options');
    }
};
