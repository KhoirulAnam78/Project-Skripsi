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
        Schema::create('kehadiran_kegiatans', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['hadir', 'sakit', 'alfa', 'izin', 'dinas luar', 'dinas dalam']);
            $table->foreignId('siswa_id')->constrained('siswas');
            $table->foreignId('monitoring_kegiatan_id')->constrained('monitoring_kegiatans');
            $table->foreignId('kegiatan_id')->constrained('kegiatans');
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
        Schema::dropIfExists('kehadiran_kegiatans');
    }
};
