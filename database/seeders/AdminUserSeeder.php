<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;


class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'fullname' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('12345678'),
            'profilePic' => '/img/pic.png',
            'role_id' => 1
        ]);
    }
}
