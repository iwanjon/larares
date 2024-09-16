<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Campaign;
use App\Models\CampaignImage;
use Database\Seeders\CampaignImageSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Database\Seeders\CampaignSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

use function PHPUnit\Framework\assertCount;

class CampaignTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    // public function test_example(): void
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }
    protected function setUp():void
    {
        parent::setUp();
       //  DB
       DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    //    CampaignImage::truncate();
    //    Campaign::truncate();
  
       DB::table('campaign_images')->truncate();
       DB::table('campaigns')->truncate();
       DB::table('users')->truncate();
       DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
    public function test_insert_campaign()
    {
        $this->seed(CampaignSeeder::class);

        $res = Campaign::query()->where("id","=",1)->first() ;
        // $res = Category::query()->find("gogo");
    //    $res =  Category::find("gogo");
       Log::info("===test_insert_campaign====");
        Log::info(json_encode($res));
        self::assertEquals(1,$res->id);

    }

    public function test_campaign_relation()
    {
        $this->seed(CampaignSeeder::class);
        $this->seed(CampaignImageSeeder::class);

        $res = Campaign::query()->where("id","=",1)->first()   ;
        // $res = Category::query()->find("gogo");
    //    $res =  Category::find("gogo");
         Log::info("===test_campaign_relation====");
        Log::info(json_encode($res));
        Log::info(json_encode($res->name));
        $campi = $res->campaign_images;
        Log::info(json_encode($campi));
        self::assertCount(1,$campi);
        assertCount(1,$campi);

        self::assertEquals("abc",$campi[0]->filename);
        
    }
    public function test_campaign_image_relation()
    {
        $this->seed(CampaignSeeder::class);
        $this->seed(CampaignImageSeeder::class);

        $res = CampaignImage::query()->where("id","=",1)->first()   ;
        // $res = Category::query()->find("gogo");
    //    $res =  Category::find("gogo");
         Log::info("===test_campaign_image_relation====");
        Log::info(json_encode($res));
        $campi = $res->campaign;
        Log::info(json_encode($campi));
        self::assertEquals("first", $campi->name);
        
    }
    public function test_insert_campaign_images()
    {
        $this->seed(CampaignSeeder::class);
        $this->seed(CampaignImageSeeder::class);

        $res = CampaignImage::query()->where("id","=",1)->first()   ;
        // $res = Category::query()->find("gogo");
    //    $res =  Category::find("gogo");
       Log::info("===test_insert_campaign_images====");
        Log::info(json_encode($res));
        self::assertEquals(1,$res->id);

    }


    public function testCresateCampaignSucccess(){
        $this->seed([UserSeeder::class]);
        $input = [
            
            "short_description"=>"short des",
            "name"=>"nanme",
            "description"=>"description",
            "perks"=>"oerks",
            "goal_amount"=>123000000,
        ];
        $header = 
        [
            'Authorization' => 'test'
        ];
        $this->post("api/campaign",$input,$header)->assertStatus(201)
        ->assertJson(
            [
                "data"=> [
                    'id' => 1,
                            'name' => 'nanme',
                         'short_description' => 'short des',
                         'description' => 'description',
                         'goal_amount' => 123000000,
                         'slug' => '1_nanme',
                ]
            ]
        );
    }

    public function testCresateCampaignFailedUnauthorized(){
        $this->seed([UserSeeder::class]);
        $input = [
            "short_description"=>"short des",
            "name"=>"nanme",
            "description"=>"description",
            "perks"=>"oerks",
            "goal_amount"=>123000000,
        ];
        $header = 
        [
            'Authorization' => 'testq'
        ];
        $this->post("api/campaign",$input,$header)->assertStatus(401)
        ->assertJson(
            [
                "errors"=> [
                    "message"=> [
                        "unauthorized"
                    ]
                    ]
            ]
        );
    }


    public function testCresateCampaignFailedRequired(){
        $this->seed([UserSeeder::class]);
        $input = [
            "short_description"=>"short des",
            "name"=>"nanme",
        
            "perks"=>"oerks",
            "goal_amount"=>123000000,
        ];
        $header = 
        [
            'Authorization' => 'test'
        ];
        $this->post("api/campaign",$input,$header)->assertStatus(400)
        ->assertJson(
            [
                "errors"=> [
                    "description"=> [
                        "The description field is required."
                    ]
                    ]
            ]
        );
    }


    public function testUploadImagesSuccessIsPrimary(){
        $this->seed([UserSeeder::class]);
        $this->test_insert_campaign_images();

        $cami = new CampaignImage();
        $cami->filename = "abcd";
        $cami->campaign_id = 1;
        $cami->is_primary = 1;
        $cami->save();

        $cami = new CampaignImage();
        $cami->filename = "abcd3";
        $cami->campaign_id = 1;
        $cami->is_primary = 0;
        $cami->save();

        $ims = UploadedFile::fake()->image("gori.png");
        $input = [
            "is_primary"=>1,
            // "name"=>"nanme update",
            // 'description' => 'description',
            "image"=> $ims,
        ];
        $header = 
        [
            'Authorization' => 'test'
        ];
        $this->post("api/campaign/1/upload/image",$input,$header)->assertStatus(200)
        ->assertJson(
            [
                "data"=> [
                    'image_urls' => 'http://localhost:8000/storage/campaign_images/gori_gon',
                    'mime' => 'image/png',
                ]
            ]
        );

        
    }


    public function testUpdateCampaignSuccess(){
        // $this->seed([UserSeeder::class]);
        $this->testCresateCampaignSucccess();
        $input = [
            "short_description"=>"short  update des",
            // "name"=>"nanme update",
            // 'description' => 'description',
            "perks"=>"oerksupdate",
            "goal_amount"=>12000000,
        ];
        $header = 
        [
            'Authorization' => 'test'
        ];
        $this->patch("api/campaign/1",$input,$header)->assertStatus(200)
        ->assertJson(
            [
                "data"=> [
                    'id' => 1,
                    "short_description"=>"short  update des",
                    "name"=>"nanme",
                    "perks"=>"oerksupdate",
                    "goal_amount"=>12000000,
                    // 'slug' => '1_nanme update',
                ]
            ]
        );
    }
    public function testUpdateCampaignFailedDataNotFound(){
        // $this->seed([UserSeeder::class]);
        $this->testCresateCampaignSucccess();
        $input = [
            "short_description"=>"short  update des",
            "name"=>"nanme update",
            // 'description' => 'description',
            "perks"=>"oerksupdate",
            "goal_amount"=>12000000,
        ];
        $header = 
        [
            'Authorization' => 'test'
        ];
        $this->patch("api/campaign/3",$input,$header)->assertStatus(400)
        ->assertJson(
            [
                "errors"=> [
                    "message"=> [
                        "data not found"
                    ]
                ]
            ]
        );
    }

    public function testUpdateCampaignFailedUnauthorizedd(){
        // $this->seed([UserSeeder::class]);
        $this->testCresateCampaignSucccess();
        $input = [
            "short_description"=>"short  update des",
            "name"=>"nanme update",
            // 'description' => 'description',
            "perks"=>"oerksupdate",
            "goal_amount"=>12000000,
        ];
        $header = 
        [
            'Authorization' => 'testwe'
        ];
        $this->patch("api/campaign/3",$input,$header)->assertStatus(401)
        ->assertJson(
            [
                "errors"=> [
                    "message"=> [
                        "unauthorized"
                    ]
                ]
            ]
        );
    }

    public function testGetCampaignsSuccess(){
        $this->testCresateCampaignSucccess();
        $this->get("api/campaigns")->assertStatus(200)
        ->assertJson(            
            [
            "data"=>[
                [
                    // '   id' => 1,
                        'name' => 'nanme',
                        'short_description' => 'short des',
                        'description' => 'description',
                        'goal_amount' => 123000000,
                        'slug' => '1_nanme',
                        "perks"=>"oerks"
                ]
            ]
        ]);
    }

    public function testGetCampaignsFailed(){
        // $this->testCresateCampaignSucccess();
        $this->get("api/campaigns")->assertStatus(400)
        ->assertJson(            
            [
                "errors"=> [       
                    "message" => 
                    [
                        "data not found"
                    ]
            ]
        ]);
    }

    public function testGetCampaignSuccess(){
        $this->testCresateCampaignSucccess();
        $this->get("api/campaign/1")->assertStatus(200)
        ->assertJson(            
            [
            "data"=>[
                
                    // '   id' => 1,
                        'name' => 'nanme',
                        'short_description' => 'short des',
                        'description' => 'description',
                        'goal_amount' => 123000000,
                        'slug' => '1_nanme',
                        "perks"=>"oerks"
                
            ]
        ]);
    }

    public function testGetCampaignFailed(){
        // $this->testCresateCampaignSucccess();
        $this->get("api/campaign/1")->assertStatus(400)
        ->assertJson(            
            [
                "errors"=> [       
                    "message" => 
                    [
                        "data not found"
                    ]
            ]
        ]);
    }


}
