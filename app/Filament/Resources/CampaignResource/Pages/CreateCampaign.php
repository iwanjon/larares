<?php

namespace App\Filament\Resources\CampaignResource\Pages;

use Filament\Actions;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\CampaignResource;
use App\Models\Campaign;

class CreateCampaign extends CreateRecord
{
    protected static string $resource = CampaignResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $bef_data = new Campaign();
        $bef_data->name = $data["name"];
        $bef_data->user_id = $data["user_id"];
        $bef_data->setSlug($data["user_id"]);
        $data['slug'] = $bef_data->slug;


        // ddd($data);
 
        return $data;
    }

    // protected function handleRecordCreation(array $data): Model
    // {
    //     ddd(static::getModel(), "koko", static::getRecord());
    //     return static::getModel()::create($data);
    // }
}
