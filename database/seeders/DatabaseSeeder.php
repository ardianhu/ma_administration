<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Dorm;
use App\Models\IslamicClass;
use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        Role::create([
            'name' => 'admin',
        ]);
        Role::create([
            'name' => 'sekretaris',
        ]);
        Role::create([
            'name' => 'keamanan',
        ]);
        Role::create([
            'name' => 'kesehatan',
        ]);
        Dorm::create([
            'block' => 'A',
            'room_number' => 1,
            'capacity' => 16,
            'zone' => 'putra',
        ]);
        IslamicClass::create([
            'name' => 'Awwaliyah',
            'class' => 1,
            'sub_class' => 1,
        ]);
        User::factory()->create([
            'name' => 'jaka',
            'email' => 'jaka@gmail.com',
            'role_id' => 1,
        ]);
    }
}
