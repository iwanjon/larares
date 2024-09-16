<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        User::create([
            "name"=>"gori",
            "email"=>"gori@gori.gori",
            "password"=>Hash::make("gori"),
            "token"=>"test",
            "role"=>"test",
            "occupation"=>"test"

        ]);

        User::create([
            "name"=>"gori2",
            "email"=>"gori2@gori2.gori2",
            "password"=>Hash::make("gori2"),
            "token"=>"test2",
            "role"=>"test2",
            "occupation"=>"test2"

        ]);

    }
}
