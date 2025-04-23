<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAddressCustomUserTable extends Migration
{
    public function up()
    {
        Schema::create('address_custom_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_user_id')->constrained()->onDelete('cascade');
            $table->foreignId('address_id')->constrained()->onDelete('cascade');
            $table->boolean('is_default')->default(false); // Adiciona o campo is_default na tabela Pivot
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('address_custom_user');
    }
}
