<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        //    CampaignImage::truncate();
        //    Campaign::truncate();
      
        DB::table('transactions')->truncate();
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        DB::table('users')->truncate();
        DB::table('campaigns')->truncate();
           DB::table('campaign_images')->truncate();
           DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->call([

            UserSeeder::class,
            CampaignSeeder::class,
            CampaignImageSeeder::class,
            PermissionSeeder::class,
            RoleSeeder::class,
            TransactionSeeder::class,

        ]);

    }
}
