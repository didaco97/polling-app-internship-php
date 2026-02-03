<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vote_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('poll_id')->constrained()->onDelete('cascade');
            $table->foreignId('option_id')->constrained('poll_options')->onDelete('cascade');
            $table->string('ip_address', 45);
            $table->enum('action', ['voted', 'released']);
            $table->timestamp('voted_at')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            
            $table->index(['poll_id', 'ip_address']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vote_history');
    }
};
