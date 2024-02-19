<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->count(4)->create();

        User::factory()->create([
            'name' => 'drfoxg',
            'email' => 'drfoxg@gmail.com',
            'email_verified_at' => Carbon::now(),
            'is_admin' => 1,
            'password' => Hash::make(config('app.admin_password')),
        ]);
    }
}
