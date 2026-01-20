<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tunnel_logs', function (Blueprint $table) {
            $table->id();
            $table->string('vps_ip');
            $table->string('vps_user');
            $table->integer('proxy_port');
            $table->enum('status', ['running', 'stopped', 'error'])->default('stopped');
            $table->integer('process_id')->nullable();
            $table->text('output')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('stopped_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tunnel_logs');
    }
};
