<?php

namespace Database\Seeders;

use App\Models\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                "name" => "Admin",
                "email" => "admin@gmail.com",
                "password" => bcrypt("123456"),
                "level" => "admin",
                "qr_token" => Uuid::uuid4()->toString(),
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}