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
            $table->string('user_name');
            $table->enum('user_type', ['PF', 'PJ']);
            $table->string('tax_id')->unique();
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('profile_photo')->nullable();
            $table->date('birth_date')->nullable();
            $table->date('foundation_date')->nullable();
            $table->boolean('status')->default(true);
            $table->text('provider_description');
            $table->string('service_category');
            $table->text('service_description');
            $table->integer('work_radius'); // em km
            $table->json('availability'); // ou use uma tabela separada se quiser algo mais detalhado
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('providers');
    }
}
