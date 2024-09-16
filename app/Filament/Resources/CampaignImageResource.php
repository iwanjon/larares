<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\CampaignImage;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CampaignImageResource\Pages;
use App\Filament\Resources\CampaignImageResource\RelationManagers;

class CampaignImageResource extends Resource
{
    protected static ?string $model = CampaignImage::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\TextInput::make('filename'),
                Forms\Components\FileUpload::make('filename'),
                Select::make('is_primary')->required()
                    ->options([
                        1,0
                    ])->label("primary"),
                Forms\Components\Select::make('campaign_id')
                ->relationship(name:"campaign",titleAttribute:"name"),
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('filename')->searchable(),
                Tables\Columns\TextColumn::make('is_primary')->searchable(),
                Tables\Columns\TextColumn::make('campaign_id')->searchable(),
                Tables\Columns\TextColumn::make('Campaign.name')->searchable(),
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCampaignImages::route('/'),
            'create' => Pages\CreateCampaignImage::route('/create'),
            'edit' => Pages\EditCampaignImage::route('/{record}/edit'),
        ];
    }
}
