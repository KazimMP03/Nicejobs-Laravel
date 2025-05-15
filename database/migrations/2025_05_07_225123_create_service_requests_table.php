<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceRequestsTable extends Migration
{
    public function up(): void
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('custom_user_id')
                ->constrained('custom_users')->onDelete('cascade');

            $table->foreignId('provider_id')
                ->constrained('providers')->onDelete('cascade');

            // Endereço da execução do serviço (pode ser endereço específico ou padrão)
            $table->foreignId('address_id')
                ->nullable()
                ->constrained('addresses')->onDelete('set null');

            // Descrição completa da solicitação
            $table->text('description');

            // Orçamento inicial sugerido pelo CustomUser (obrigatório)
            $table->decimal('initial_budget', 10, 2);

            // Valor final acordado após negociação (preenchido depois)
            $table->decimal('final_price', 10, 2)->nullable();

            // Status da solicitação
            $table->enum('status', [
                'requested',
                'chat_opened',
                'accepted',
                'rejected',
                'cancelled',
                'completed'
            ])->default('requested');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
};

