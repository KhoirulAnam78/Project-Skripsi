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
        Schema::create('kehadiran_apels', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->enum('apel', ['pagi', 'malam']);
            $table->foreignId('siswa_id')->constrained('siswas');
            $table->enum('status', ['hadir', 'sakit', 'alfa', 'izin', 'dinas luar', 'dinas dalam']);
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
        Schema::dropIfExists('kehadiran_apels');
    }
};
