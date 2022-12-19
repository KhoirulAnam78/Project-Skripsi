<?php

namespace Database\Seeders;

use App\Models\Guru;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GuruSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Guru::create([
            'nip' => '123456789876543212',
            'kode_guru' => 'KA',
            'nama' => 'Khoirul Anam',
            'no_telp' => '0857887872427',
            'status' => 'aktif',
            'user_id' => 1
        ]);
    }
}
