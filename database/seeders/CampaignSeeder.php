<?php

namespace Database\Seeders;

use App\Models\Campaign;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CampaignSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $camp = new Campaign();
        $camp->name = "first";
        $camp->short_description = "short_description";
        $camp->description = "description";
        $camp->perks = "perks";
        $camp->goal_amount = 1000000;
        $camp->slug = "first";
        $camp->name = "first";
        $camp->save();

        $camp2 = new Campaign();
        $camp2->name = "first2";
        $camp2->short_description = "short_description2";
        $camp2->description = "description2";
        $camp2->perks = "perks2";
        $camp2->goal_amount = 2000000;
        $camp2->slug = "first2";
        $camp2->name = "firs2";
        $camp2->save();
        
        $camp3 = new Campaign();
        $camp3->name = "first3";
        $camp3->short_description = "short_description3";
        $camp3->description = "description3";
        $camp3->perks = "perks3";
        $camp3->goal_amount = 3000000;
        $camp3->slug = "first3";
        $camp3->name = "first3";
        $camp3->save();


        $camp4 = new Campaign();
        $camp4->name = "first4";
        $camp4->short_description = "short_description4";
        $camp4->description = "description4";
        $camp4->perks = "perks4";
        $camp4->goal_amount = 3000000;
        $camp4->current_amount = 3000000;
        $camp4->slug = "first4";
        $camp4->name = "first4";
        $camp4->save();

    }
}
