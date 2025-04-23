<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressProviderTable extends Migration
{
    public function up()
    {
        Schema::create('address_provider', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')->constrained()->onDelete('cascade');
            $table->foreignId('address_id')->constrained()->onDelete('cascade');
            $table->boolean('is_default')->default(false); // Adiciona o campo is_default na tabela Pivot
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('address_provider');
    }
}
