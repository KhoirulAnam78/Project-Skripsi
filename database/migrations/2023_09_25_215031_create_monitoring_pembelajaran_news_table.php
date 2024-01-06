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
        Schema::create('monitoring_pembelajaran_news', function (Blueprint $table) {
            $table->id('monitoring_pembelajaran_id');
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_berakhir');
            $table->string('topik');
            $table->enum('status_validasi', ['terlaksana', 'tidak terlaksana', 'belum tervalidasi']);
            $table->string('keterangan')->nullable();
            $table->foreignId('kelas_id')->constrained('kelas');
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajarans');
            $table->foreignId('guru_id')->constrained('gurus');
            $table->foreignId('guru_piket_id')->nullable()->constrained('gurus');
            
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
        Schema::dropIfExists('monitoring_pembelajaran_news');
    }
};
