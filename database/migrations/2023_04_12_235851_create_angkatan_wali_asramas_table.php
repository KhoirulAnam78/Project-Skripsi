<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('angkatan_wali_asrama', function (Blueprint $table) {
            $table->id();
            $table->foreignId('angkatan_id')->constrained('angkatans')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('wali_asrama_id')->constrained('wali_asramas');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('angkatan_wali_asramas');
    }
};
