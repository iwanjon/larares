<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreateTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            "id"=> $this->id,
            "status"=> $this->status,
            "amount"=> $this->amount,
            "code"=> $this->code,
            "payment_url"=> $this->payment_url,
            "user_id"=> $this->user_id,
            "campaign_id"=> $this->campaign_id,
        ];
    }
}
