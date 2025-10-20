<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class ResepsionisSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate([
            'email' => 'resepsionis@example.com'
        ], [
            'name' => 'Resepsionis',
            'password' => bcrypt('resepsionis'),
            'role' => 'resepsionis',
        ]);
    }
}
