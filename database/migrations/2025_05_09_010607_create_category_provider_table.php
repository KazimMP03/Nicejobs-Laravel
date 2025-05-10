<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('category_provider', function (Blueprint $table) {
            $table->id(); // ID da relação entre ServiceCategory e Provider
            $table->foreignId('provider_id')->constrained()->onDelete('cascade'); // ID do Provider
            $table->foreignId('service_category_id')->constrained()->onDelete('cascade'); // ID da ServiceCategory
            $table->timestamps(); // Criado quando e atualizado quando
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_provider');
    }
};
