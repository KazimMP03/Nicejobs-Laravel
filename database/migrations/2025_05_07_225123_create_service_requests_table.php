<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServiceRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('service_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_user_id')
                ->constrained('custom_users')
                ->onDelete('cascade');
            $table->foreignId('service_id')
                ->constrained('services')
                ->onDelete('cascade');
            $table->foreignId('provider_id')
                ->constrained('providers')
                ->onDelete('cascade');
            $table->enum('status', ['requested', 'accepted', 'rejected', 'cancelled'])
                ->default('requested');
            $table->text('message')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('service_requests');
    }
}
