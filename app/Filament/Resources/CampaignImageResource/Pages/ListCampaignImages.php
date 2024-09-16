<?php

namespace App\Filament\Resources\CampaignImageResource\Pages;

use App\Filament\Resources\CampaignImageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCampaignImages extends ListRecords
{
    protected static string $resource = CampaignImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
