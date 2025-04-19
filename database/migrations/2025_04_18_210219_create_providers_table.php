<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProvidersTable extends Migration
{
    public function up()
    {
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->string('user_name'); // Nome ou Razão Social
            $table->enum('user_type', ['PF', 'PJ']); // Pessoa Física ou Pessoa Jurídica
            $table->string('tax_id')->unique(); // CPF ou CNPJ
            $table->string('email')->unique(); // Email
            $table->string('password');  // Senha
            $table->string('phone'); // Telefone
            $table->string('profile_photo')->nullable(); // Foto de Perfil
            $table->date('birth_date')->nullable(); // Data de Nascimento (se PF)
            $table->date('foundation_date')->nullable(); // Data de Fundação (se PJ)
            $table->boolean('status')->default(true); // Ativo ou Inativo
            $table->text('provider_description'); // Descrição do Prestador
            $table->string('service_category'); // Categoria de Serviço
            $table->text('service_description'); // Descrição do Serviço
            $table->integer('work_radius'); // Distância em km
            $table->json('availability'); // ou use uma tabela separada se quiser algo mais detalhado
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('providers');
    }
}
