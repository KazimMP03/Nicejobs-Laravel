<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('service_categories', function (Blueprint $table) {
            $table->id(); // ID da categoria
            $table->string('name')->unique(); // Nome da categoria
            $table->string('slug')->unique(); // Slug da categoria
            $table->text('description')->nullable(); // Descrição da categoria
            $table->timestamps(); // Timestamps para created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_categories');
    }
};
