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
        Schema::create('produk_ppob', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 50)->unique();
            $table->string('nama_produk', 255);
            $table->foreignId('sub_kategori_id')->nullable()->constrained('sub_kategori')->onDelete('set null');

            // Kolom harga
            $table->decimal('hpp', 15, 2)->default(0);
            $table->decimal('biaya_admin', 15, 2)->default(0);
            $table->decimal('fee_mitra', 15, 2)->default(0);
            $table->decimal('markup', 15, 2)->default(0);
            $table->decimal('harga_beli', 15, 2)->default(0);
            $table->decimal('harga_jual', 15, 2)->default(0);
            $table->decimal('profit', 15, 2)->default(0);

            $table->boolean('aktif')->default(true);
            $table->timestamps();

            // Indexes
            $table->index('sub_kategori_id');
            $table->index('nama_produk');
            $table->index('aktif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_ppob');
    }
};
