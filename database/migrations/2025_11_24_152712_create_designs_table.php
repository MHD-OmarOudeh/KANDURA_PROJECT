<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('designs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->json('name');
            $table->json('description');
            $table->decimal('price', 10, 2);
            $table->timestamps();
            $table->softDeletes(); 

            $table->index('user_id');
            $table->index('price');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('designs');
    }
};
