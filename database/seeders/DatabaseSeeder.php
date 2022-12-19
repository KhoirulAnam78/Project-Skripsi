<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

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
            'username' => 'guru',
            'password' => bcrypt('1234'),
            'role' => 'guru'
        ]);
        User::create([
            'username' => 'siswa',
            'password' => bcrypt('1234'),
            'role' => 'siswa'
        ]);
        $this->call([
            GuruSeeder::class,
            SiswaSeeder::class,
            TahunAkademikSeeder::class,
            KelasSeeder::class
        ]);
    }
}
