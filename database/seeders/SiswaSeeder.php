<?php

namespace Database\Seeders;

use App\Models\Siswa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Siswa::create([
            'nisn' => '1234567898',
            'nama' => 'Anugrah Mukti',
            'no_telp' => '085788787427',
            'status' => 'aktif',
            'user_id' => 2
        ]);
    }
}
