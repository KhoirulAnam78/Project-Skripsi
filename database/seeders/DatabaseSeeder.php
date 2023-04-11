<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\JadwalPelajaran;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        User::create([
            'username' => 'admin',
            'password' => bcrypt('monitoring2023'),
            'role' => 'admin'
        ]);
        User::create([
            'username' => '123456789876543212',
            'password' => bcrypt('123456789876543212'),
            'role' => 'guru'
        ]);
        User::create([
            'username' => '2052994922',
            'password' => bcrypt('2052994922'),
            'role' => 'siswa'
        ]);
        $this->call([
            GuruSeeder::class,
            SiswaSeeder::class,
            TahunAkademikSeeder::class,
            AngkatanSeeder::class,
            KelasSeeder::class,
            MataPelajaranSeeder::class,
            JadwalPelajaranSeeder::class
        ]);
    }
}
