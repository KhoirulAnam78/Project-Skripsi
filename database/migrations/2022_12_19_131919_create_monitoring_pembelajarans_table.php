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
        Schema::create('monitoring_pembelajarans', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_berakhir');
            $table->string('topik');
            $table->enum('status_validasi', ['valid', 'tidak valid', 'belum tervalidasi']);
            $table->string('keterangan')->nullable();
            $table->foreignId('jadwal_pelajaran_id')->constrained('jadwal_pelajarans');
            $table->foreignId('guru_piket_id')->constrained('gurus')->nullable();
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
        Schema::dropIfExists('monitoring_pembelajarans');
    }
};
