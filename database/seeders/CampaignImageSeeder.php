<?php

namespace Database\Seeders;

use App\Models\CampaignImage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CampaignImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $cami = new CampaignImage();
        $cami->filename = "abc";
        $cami->campaign_id = 1;
        $cami->is_primary = 1;
        $cami->save();
        
        $cami2 = new CampaignImage();
        $cami2->filename = "abc2";
        $cami2->is_primary = 1;
        $cami2->campaign_id = 2;
        $cami2->save();

        $cam3 = new CampaignImage();
        $cam3->filename = "abc3";
        $cam3->campaign_id = 3;
        $cam3->save();
    }
}
