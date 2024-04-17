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
        Schema::create('m_toko', function (Blueprint $table) {
            $table->id();
            $table->string('nama_toko', 100);
            $table->text('alamat');
            $table->unsignedBigInteger('bisnis_id');
            $table->timestamps();

            $table->foreign('bisnis_id')->references('id')->on('m_tipe_bisnis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('m_toko');
    }
};
