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
        Schema::create('cuci_gudangs', function (Blueprint $table) {
            $table->id('id_cuci_gudang');
            $table->unsignedBigInteger('id_produk');
            $table->integer('persen_diskon')->default(0);
            $table->dateTime('waktu_mulai')->nullable();
            $table->dateTime('waktu_selesai')->nullable();
            
            $table->foreign('id_produk')->references('id_produk')->on('produks')->onDelete('cascade');
            
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuci_gudangs');
    }
};
