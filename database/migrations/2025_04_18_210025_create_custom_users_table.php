<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomUsersTable extends Migration
{
    public function up()
    {
        Schema::create('custom_users', function (Blueprint $table) {
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
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('custom_users');
    }
}
