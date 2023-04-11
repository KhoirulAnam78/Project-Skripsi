<?php

namespace Database\Seeders;

use App\Models\TahunAkademik;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TahunAkademikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TahunAkademik::create([
            'nama' => '2022/2023',
            'tgl_mulai' => '2022-06-07',
            'tgl_berakhir' => '2023-06-07',
            'status' => 'aktif'
        ]);
    }
}
