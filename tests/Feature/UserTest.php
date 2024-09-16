<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\WithFaker;

use function PHPUnit\Framework\assertNotNull;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
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


    public function testRegisterSuccess(){
        $this->post("api/user",[
            "name"=> "koko",
            "password"=>"koko",
            "email"=>"email@email.com",
            "occupation"=> "koko",
            "role"=>"kokorole"
        ])-> assertStatus(201)
        ->assertJson(
            [
                "data"=> [
                    "name"=>"koko",
                    "email"=>"email@email.com"
                ]
            ]
        );
    }


    public function testRegisterFailed(){
        $this->post("api/user",[
            "name"=> "",
            "password"=>"",
            "email"=>"email@email.com",
            "occupation"=> "",
            "role"=>"kokorole"
        ])-> assertStatus(400)
        ->assertJson(
            [
                "errors"=> [
                    "name"=>['The name field is required.'],
                    "password"=>['The password field is required.'],
                    "occupation"=>['The occupation field is required.']
                ]
            ]
        );
    }


    public function testRegisterAlreadyExists(){
        $this->testRegisterSuccess();
        
        // $this->post("api/user",[
            //     "name"=> "koko",
        //     "password"=>"koko",
        //     "email"=>"email@email.com",
        //     "occupation"=> "koko",
        //     "role"=>"kokorole"
        // ])-> assertStatus(201);

        $this->post("api/user",[
            "name"=> "koko",
            "password"=>"koko",
            "email"=>"email@email.com",
            "occupation"=> "koko",
            "role"=>"kokorole"
        ])->assertStatus(400)
        ->assertJson(
            [
                "errors"=> [
                    "email"=>[
                        "       email already exist"
                        ]
                        ]
                        ]
        );
    }
    
    public function testLoginSuccess(){
        $this->testRegisterSuccess();

        $input = [
            "email"=>"email@email.com",
            // "name"=>"koko",
            "password"=>"koko",
        ];
        $this->post("api/user/login",$input)->assertStatus(200)
        ->assertJson([
            "data"=> [
                "name"=>"koko",
                "email"=>"email@email.com"
            ]
        ]);

        $user = User::where("email",$input["email"])->first();
        self::assertNotNull($user->token);
        
    }
    public function testLoginFailed(){
        $this->testRegisterSuccess();

        $input = [
            "email"=>"email@emaiql.com",
            "password"=>"koko",
        ];
        $this->post("api/user/login",$input)->assertStatus(401)
        ->assertJson([
            "errors"=> [
                "message"=>["wrong email or password"]
            ]
        ]);
        
    }


    public function testGetCurrentUserSuccess(){
        // $this->testLoginSuccess();
         $this->seed([UserSeeder::class]);
        $this->get("api/user/current",[
            "Authorization"=>"test"
        ])
        ->assertStatus(200)
        ->assertJson([
            "data"=> [
                "name"=>"gori",
                "email"=>"gori@gori.gori"
            ]
        ]);
    }

    public function testGetCurrentUserWrongToken(){
        // $this->testLoginSuccess();
         $this->seed([UserSeeder::class]);
        $this->get("api/user/current",[
            "Authorization"=>"testq"
        ])
        ->assertStatus(401)
        ->assertJson([
            "errors"=> [
                "message"=>["unauthorized"]
            ]
        ]);
    }
    public function testGetCurrentUserNoToken(){
        // $this->testLoginSuccess();
         $this->seed([UserSeeder::class]);
        $this->get("api/user/current",[
           
        ])
        ->assertStatus(401)
        ->assertJson([
            "errors"=> [
                "message"=>["unauthorized"]
            ]
        ]);
    }


    public function testUpdateSuccess(){
        $this->testGetCurrentUserSuccess();
        $input = [
            
            "name"=>"gorio",
            "email"=>"gori@gori.o",
            "password"=>"kokoo",
        ];
        $header = 
        [
            'Authorization' => 'test'
        ];
        $this->patch("api/user/current",$input,$header)->assertStatus(200)
        ->assertJson([
            "data"=> [
                "name"=>"gorio",
                "email"=>"gori@gori.o"
            ]
        ]);
    }

    public function testUpdateUnauthorized(){
        $this->testGetCurrentUserSuccess();
        $input = [
            
            "name"=>"gorio",
            "email"=>"gori@gori.o",
            "password"=>"kokoo",
            "Authorization"=>"test"
        ];
        $header = 
        [
            'Authorization' => 'testr'
        ];
        $this->patch("api/user/current",$input,$header)->assertStatus(401)
        ->assertJson([
            "errors"=> [
                "message"=>["unauthorized"]
            ]
        ]);
    }

    public function testUpdateFailedNoInput(){
        $this->testGetCurrentUserSuccess();
        $input = [

        ];
        $header = 
        [
            'Authorization' => 'test'
        ];
        $this->patch("api/user/current",$input,$header)->assertStatus(400)
        ->assertJson([
            "errors"=> [
                "email"=> ["The email field is required when none of password / name / occupation / role are present."],
                "password"=> ["The password field is required when none of email / name / occupation / role are present."],
                "occupation"=> ["The occupation field is required when none of password / name / email / role are present."],
                "role"=> ["The role field is required when none of password / name / occupation / email are present."],
                "name"=> ["The name field is required when none of password / email / occupation / role are present."],
            ]
        ]);
    }


    public function testUserLogoutSuccess(){
        $this->testGetCurrentUserSuccess();
        $header = 
        [
            'Authorization' => 'test'
        ];

        $this->delete("api/user/logout",[], $header)->assertStatus(200)
        ->assertJson([
            "data"=> true
        ]);
    }
    public function testUserLogoutUnauthorized(){
        $this->testGetCurrentUserSuccess();
        $header = 
        [
            'Authorization' => 'teste'
        ];

        $this->delete("api/user/logout",[], $header)->assertStatus(401)
        ->assertJson([
            "errors"=> [
                "message"=>["unauthorized"]
            ]
        ]);
    }
    public function testUserLogoutNoUser(){
        // $this->testGetCurrentUserSuccess();
        $header = 
        [
            // 'Authorization' => 'teste'
        ];

        $this->delete("api/user/logout",[], $header)->assertStatus(401)
        ->assertJson([
            "errors"=> [
                "message"=>["unauthorized"]
            ]
        ]);
    }

}
