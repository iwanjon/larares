<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        $data = [
          ["user_id"=>1 , "campaign_id"=>1,"amount"=>1000000,"status"=>1, "code"=>"gori1"],  
          ["user_id"=>1 , "campaign_id"=>1,"amount"=>2000000,"status"=>1, "code"=>"gori2"],  
          ["user_id"=>1 , "campaign_id"=>1,"amount"=>3000000,"status"=>1, "code"=>"gori3"],  
          ["user_id"=>1 , "campaign_id"=>1,"amount"=>4000000,"status"=>1, "code"=>"gori4"],  
          ["user_id"=>1 , "campaign_id"=>2,"amount"=>1000000,"status"=>1, "code"=>"gori21"],  
          ["user_id"=>1 , "campaign_id"=>2,"amount"=>2000000,"status"=>1, "code"=>"gori22"],  
          ["user_id"=>1 , "campaign_id"=>3,"amount"=>3000000,"status"=>1, "code"=>"gori31"],  
          ["user_id"=>1 , "campaign_id"=>4,"amount"=>4000000,"status"=>1, "code"=>"gori41"],  
        ];
        Transaction::insert($data);

    }
}
