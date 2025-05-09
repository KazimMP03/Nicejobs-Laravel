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
        Schema::create('services', function (Blueprint $table) {
            $table->id(); // ID do serviço
            $table->foreignId('provider_id')
                ->constrained()->onDelete('cascade'); // Chave estrangeira para o provedor

            $table->foreignId('service_category_id')
                ->constrained()->onDelete('restrict'); // Chave estrangeira para a categoria de serviço

            $table->string('title'); // Título do serviço
            $table->text('description')->nullable(); // Descrição do serviço
            $table->decimal('price', 10, 2); // Preço do serviço

            $table->enum('status', ['pending', 'active', 'inactive', 'canceled'])
                ->default('pending'); // Status do serviço (Pendente, Ativo ou Inativo)
            $table->timestamps(); // Timestamps para created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
