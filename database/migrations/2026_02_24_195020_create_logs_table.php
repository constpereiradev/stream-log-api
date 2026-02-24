<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();

            $table->string('level')->default('info'); // info, warning, error, critical

            $table->text('message');

            $table->json('context')->nullable();

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->foreignId('api_token_id')->nullable()
                ->constrained('personal_access_tokens')
                ->nullOnDelete();

            $table->string('ip_address')->nullable();
            $table->string('method')->nullable(); // GET, POST...
            $table->string('route')->nullable();
            $table->integer('status_code')->nullable();

            $table->timestamps();

            $table->index(['level']);
            $table->index(['user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};