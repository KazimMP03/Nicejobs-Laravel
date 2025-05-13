<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            // Review atrelada a um service_request
            $table->foreignId('service_request_id')->constrained()->onDelete('cascade');

            // Quem foi avaliado (alvo da review)
            $table->foreignId('provider_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('custom_user_id')->nullable()->constrained()->onDelete('cascade');

            // Quem avaliou (o reviewer)
            $table->unsignedBigInteger('reviewer_id');
            $table->string('reviewer_type'); // 'provider' ou 'custom_user'

            // Dados da avaliação
            $table->tinyInteger('rating')->unsigned(); // Nota de 1 a 5
            $table->text('comment')->nullable();

            $table->timestamps();

            // Impedir avaliações duplicadas para a mesma service_request pelo mesmo reviewer
            $table->unique(['service_request_id', 'reviewer_id', 'reviewer_type'], 'unique_review_per_request');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
