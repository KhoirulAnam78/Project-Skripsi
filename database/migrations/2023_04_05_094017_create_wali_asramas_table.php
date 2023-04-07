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
        Schema::create('wali_asramas', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 50);
            $table->char('no_telp', 14);
            $table->enum('status', ['aktif', 'tidak aktif']);
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('kelas_id')->constrained('kelas');
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
        Schema::dropIfExists('wali_asramas');
    }
};
