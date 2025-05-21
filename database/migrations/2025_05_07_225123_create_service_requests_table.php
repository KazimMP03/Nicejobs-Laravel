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

            // Usuário cliente
            $table->foreignId('custom_user_id')
                ->constrained('custom_users')->onDelete('cascade');

            // Prestador
            $table->foreignId('provider_id')
                ->constrained('providers')->onDelete('cascade');

            // Endereço onde o serviço será executado
            $table->foreignId('address_id')
                ->nullable()
                ->constrained('addresses')->onDelete('set null');

            // Descrição da solicitação
            $table->text('description');

            // Orçamento inicial sugerido pelo cliente
            $table->decimal('initial_budget', 10, 2);

            // Valor final proposto pelo prestador e aceito por ambos
            $table->decimal('final_price', 10, 2)->nullable();

            // Status da solicitação
            $table->enum('status', [
                'requested',
                'chat_opened',
                'pending_acceptance',
                'accepted',
                'rejected',
                'cancelled',
                'completed'
            ])->default('requested');

            // Flags de aceite (Duplo Aceite)
            $table->boolean('provider_accepted')->default(false);
            $table->boolean('customer_accepted')->default(false);

            // Campo para data e hora do serviço
            $table->dateTime('service_date')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_requests');
    }
}
