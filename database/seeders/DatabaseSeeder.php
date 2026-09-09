<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin MufrodatKu',
            'nis' => 'admin',
            'email' => 'admin@mufrodatku.local',
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Siswa Percobaan',
            'nis' => '221220001',
            'email' => '221220001@mufrodatku.local',
            'role' => 'siswa',
            'kelas' => 'IX',
        ]);
    }
}
