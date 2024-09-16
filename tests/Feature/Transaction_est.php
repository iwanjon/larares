<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Campaign;
use App\Models\Transaction;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\DB;
use Database\Seeders\CampaignSeeder;
use Database\Seeders\TransactionSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertNotNull;

class TransactionTest extends TestCase
{

    protected function setUp():void
    {
        parent::setUp();
       //  DB
       DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    //    CampaignImage::truncate();
    //    Campaign::truncate();
  
        DB::table('transactions')->truncate();
       DB::table('campaign_images')->truncate();
       DB::table('campaigns')->truncate();
       DB::table('users')->truncate();
       DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    /**
     * A basic feature test example.
     */
    public function test_create_transaction_success(){
        $this->seed(UserSeeder::class);
        $this->seed(CampaignSeeder::class);
        $campaign = Campaign::query()->get()->first();


        $input = [
            "amount"=>123000000,
        ];
        $header = 
        [
            'Authorization' => 'test'
        ];
        $trans = $this->post("api/campaign/$campaign->id/transaction",$input,$header)->assertStatus(201)
        ->assertJson(
            [
                "data"=> [
                    "id"=> 1,
                    "status"=> 0,
                    "amount"=> 123000000,
                    "code"=> "1_1_123000000",
                    // "payment_url"=> null,
                    "user_id"=> 1,
                    "campaign_id"=> 1
                ]
            ]
        );
        // dd($trans->json()["data"]);
        assertNotNull($trans->json()["data"]["payment_url"]);


    }
    public function test_create_transaction_failed_already_exists(){
        $this->seed(UserSeeder::class);
        $this->seed(CampaignSeeder::class);
        $campaign = Campaign::query()->get()->first();


        $input = [
            "amount"=>123000000,
        ];
        $header = 
        [
            'Authorization' => 'test'
        ];
        $trans = $this->post("api/campaign/$campaign->id/transaction",$input,$header)->assertStatus(400)
        ->assertJson(
            [
                "errors"=> [
                        "message"=>[
                            "invalid Transaction",
                            'Midtrans API is returning API error. HTTP status code: 400 API response: {"error_messages":["transaction_details.order_id has already been taken"]}'
                        ]
                ]
            ]
        );
        // dd($trans->json()["data"]);
        // assertNotNull($trans->json()["data"]["payment_url"]);


    }





    public function test_create_transaction_failed_not_found_campaign(){
        $this->seed(UserSeeder::class);
        // $this->seed(CampaignSeeder::class);
        // $campaign = Campaign::query()->get()->first();
        $input = [
            "amount"=>123000000,
        ];
        $header = 
        [
            'Authorization' => 'test'
        ];
        $this->post("api/campaign/12/transaction",$input,$header)->assertStatus(400)
        ->assertJson(
            [
                "errors"=>[

                "message"=> [
                    "invalid campaign"
                    ]
                ]
            ]
        );
    }

    public function test_create_transaction_failed_unauthorized(){
        $this->seed(UserSeeder::class);
        // $this->seed(CampaignSeeder::class);
        // $campaign = Campaign::query()->get()->first();
        $input = [
            "amount"=>123000000,
        ];
        $header = 
        [
            'Authorization' => 'teste'
        ];
        $this->post("api/campaign/12/transaction",$input,$header)->assertStatus(401)
        ->assertJson(
            [
                "errors"=>[

                "message"=> [
                    "unauthorized"
                    ]
                ]
            ]
        );
    }

    public function test_create_transaction_failed_goal_amount_fullfiled(){
        $this->seed(UserSeeder::class);
        $this->seed(CampaignSeeder::class);
        $campaign = Campaign::query()->where([
            ["goal_amount","=",3000000],
            ["current_amount","=",3000000]
            ])->first();
        // dd($campaign);
        $input = [
            "amount"=>123000000,
        ];
        $header = 
        [
            'Authorization' => 'test'
        ];
        $this->post("api/campaign/$campaign->id/transaction",$input,$header)->assertStatus(400)
        ->assertJson(
            [
                "errors"=>[

                "message"=> [
                    "campaign goal amount is reached"
                    ]
                ]
            ]
        );
    }


    public function test_get_user_transaction_success(){
        $this->seed(UserSeeder::class);
        $this->seed(CampaignSeeder::class);
        $this->seed(TransactionSeeder::class);

        $header = 
        [
            'Authorization' => 'test'
        ];

        $user = User::query()->first();
        // dd($user);

        $transactions= Transaction::query()->where("user_id",$user->id)->count();
        // $count = $transactions->count;
        // dd($transactions);
        $data = $this->get("api/user/$user->id/transactions",$header)->assertStatus(200)
        ->assertJson(
            [
                "data"=> [
                    [
                        "id"=> 1,
                        "status"=> 1,
                        "amount"=> 1000000,
                        "code"=> "gori1",
                        "payment_url"=> null,
                        "user_id"=> 1,
                        "campaign_id"=> 1
                    ],
                    [
                        "id"=> 2,
                        "status"=> 1,
                        "amount"=> 2000000,
                        "code"=> "gori2",
                        "payment_url"=> null,
                        "user_id"=> 1,
                        "campaign_id"=> 1
                    ],
                ]
            ]
        );

        $dd = $data->json()["data"];
        // dd(count($dd));
        assertCount($transactions, $dd);
        self::assertCount($transactions, $dd);
        $this->assertCount($transactions, $dd);
    }
    public function test_get_user_transaction_failed_unauthorized(){
        $this->seed(UserSeeder::class);
        $this->seed(CampaignSeeder::class);
        $this->seed(TransactionSeeder::class);

        $header = 
        [
            'Authorization' => 'testq'
        ];

        $user = User::query()->first();
        // dd($user);

        $transactions= Transaction::query()->where("user_id",$user->id)->count();
        // $count = $transactions->count;
        // dd($transactions);
        $data = $this->get("api/user/$user->id/transactions",$header)->assertStatus(401)
        ->assertJson(
            [
                "errors"=> [
                    "message"=>["unauthorized"]

                ]
            ]
        );

        // $dd = $data->json()["data"];
        // // dd(count($dd));
        // assertCount($transactions, $dd);
        // self::assertCount($transactions, $dd);
        // $this->assertCount($transactions, $dd);
    }

    public function test_get_user_transaction_failed_unauthorized_user_id_not_same(){
        $this->seed(UserSeeder::class);
        $this->seed(CampaignSeeder::class);
        $this->seed(TransactionSeeder::class);

        $header = 
        [
            'Authorization' => 'test2'
        ];

        $user = User::query()->first();
        // dd($user);

        // $transactions= Transaction::query()->where("user_id",$user->id)->count();
        // $count = $transactions->count;
        // dd($transactions);
        $data = $this->get("api/user/$user->id/transactions",$header)->assertStatus(400)
        ->assertJson(
            [
                "errors"=> [
                    "message"=>["invalid user"]

                ]
            ]
        );

    }


    public function test_get_camapign_transaction_success(){
        $this->seed(UserSeeder::class);
        $this->seed(CampaignSeeder::class);
        $this->seed(TransactionSeeder::class);

        // $header = 
        // [
        //     'Authorization' => 'test'
        // ];

        $campaign = Campaign::query()->first();
        // dd($user);

        $campaign_count= Transaction::query()->where("campaign_id",$campaign->id)->count();
        // $count = $transactions->count;
        // dd($transactions);
        $data = $this->get("api/campaign/$campaign->id/transactions")->assertStatus(200)
        ->assertJson(
            [
                "data"=> [
                    [
                        "id"=> 1,
                        "status"=> 1,
                        "amount"=> 1000000,
                        "code"=> "gori1",
                        "payment_url"=> null,
                        "user_id"=> 1,
                        "campaign_id"=> 1
                    ],
                    [
                        "id"=> 2,
                        "status"=> 1,
                        "amount"=> 2000000,
                        "code"=> "gori2",
                        "payment_url"=> null,
                        "user_id"=> 1,
                        "campaign_id"=> 1
                    ],
                ]
            ]
        );

        $dd = $data->json()["data"];
        // dd(count($dd));
        assertCount($campaign_count, $dd);
        self::assertCount($campaign_count, $dd);
        $this->assertCount($campaign_count, $dd);
    }

    public function test_get_camapign_transaction_failed(){
        $this->seed(UserSeeder::class);
        $this->seed(CampaignSeeder::class);
        $this->seed(TransactionSeeder::class);

        // $header = 
        // [
        //     'Authorization' => 'test'
        // ];

        $campaign = Campaign::query()->first();
        // $campaign2 = Campaign::query()->findOrFail(40);
        // dd($campaign2);

        $campaign_count= Transaction::query()->where("campaign_id",$campaign->id)->count();
        // $count = $transactions->count;
        // dd($transactions);
        $data = $this->get("api/campaign/12/transactions")->assertStatus(400)
        ->assertJson(
            [
                "errors"=>[
                    "message"=>[
                        "campaign not found"
                    ]
            ]
            ]
        );

        // $dd = $data->json()["data"];
        // // dd(count($dd));
        // assertCount($campaign_count, $dd);
        // self::assertCount($campaign_count, $dd);
        // $this->assertCount($campaign_count, $dd);
    }

    // public function test_transaction_notification_success(){

    //     $trans = new Transaction();
    //     $trans->amount = 123000000;
    //     $trans->status= 0 ;
    //     $trans->user_id= 1 ;
    //     $trans->campaign_id= 1 ;
    //     $trans->code= "1_1_123000000" ;
    //     $trans ->save();

    //     // $input=[
    //     //     "order_id"=> "1_1_123000000",
    //     //     "transaction_status"=>"settlement",
    //     //     "payment_type"=>"zero" ,
    //     //       "fraud_status"=>false ,
    //     //       "transaction_id"=>1_1_123000000,
    //     //     ];

    //     $input =[

    //             "transaction_time"=>"2020-01-09 18:27:19",
    //             "transaction_status"=>"capture",
    //             "transaction_id"=>"57d5293c-e65f-4a29-95e4-5959c3fa335b",
    //             "status_message"=>"midtrans payment notification",
    //             "status_code"=>"200",
    //             "signature_key"=>"16d6f84b2fb0468e2a9cf99a8ac4e5d803d42180347aaa70cb2a7abb13b5c6130458ca9c71956a962c0827637cd3bc7d40b21a8ae9fab12c7c3efe351b18d00a",
    //             "payment_type"=>"credit_card",
    //             "order_id"=>"Postman-1578568851",
    //             "merchant_id"=>"G141532850",
    //             "masked_card"=>"48111111-1114",
    //             "gross_amount"=>"10000.00",
    //             "fraud_status"=>"accept",
    //             "eci"=>"05",
    //             "currency"=>"IDR",
    //             "channel_response_message"=>"Approved",
    //             "channel_response_code"=>"00",
    //             "card_type"=>"credit",
    //             "bank"=>"bni",
    //             "approval_code"=>"1578569243927"
              
    //     ];

    //     $this->post("api/midtrans/notification",$input)->assertStatus(201);



    // }




}
