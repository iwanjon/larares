<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use App\Models\Transaction;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // $data['user_id'] = auth()->id();
        $bef_data = new Transaction();
        $bef_data->amount = $data["amount"];
        $bef_data->user_id = $data["user_id"];
        $bef_data->campaign_id = $data["campaign_id"];
        // $bef_data->setSlug($data["user_id"]);
        $data['code'] = $bef_data->setCode();


        // ddd($data);
 
        return $data;
    }


}
