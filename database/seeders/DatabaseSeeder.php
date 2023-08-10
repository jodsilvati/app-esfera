<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Phone;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminUser = User::create([
            'name' => 'José Antônio da Silva',
            'email' => 'jose@app.com',
            'password' => Hash::make('123456'),
        ]);

        Phone::create([
            'user_id' => $adminUser->id,
            'description' => 'Residência',
            'phone' => '+55 11 1234 1234',
        ]);
    }
}
