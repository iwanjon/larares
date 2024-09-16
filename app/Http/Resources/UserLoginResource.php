<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserLoginResource extends JsonResource
{



    // public static $wrap = 'Data';
    // public  $def_meta = ["Message"=>"", "Code"=>"","Status"=>0];
    public function __construct(
                                $resource, 
                                $meta=["Meta"=> ["Message"=>"", "Code"=>"","Status"=>0]]
                                 )
    {
        $this->resource = $resource;
    
        // $this->additional= $meta;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            "email"=> $this->email,
            "name"=>$this->name,
            "token"=>$this->token
        ];
    }
}
