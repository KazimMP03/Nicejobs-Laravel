<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id(); // ID do Review
            $table->foreignId('provider_id')->constrained()->onDelete('cascade'); // Chave estrangeira de Provider
            $table->foreignId('custom_user_id')->constrained()->onDelete('cascade'); // Chave estrangeira de CustomUser
            $table->tinyInteger('rating')->unsigned(); // nota de 1 a 5
            $table->text('comment')->nullable(); // Comentário
            $table->timestamps();

            // Impede que o mesmo usuário avalie o mesmo provider mais de uma vez
            $table->unique(['provider_id', 'custom_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
