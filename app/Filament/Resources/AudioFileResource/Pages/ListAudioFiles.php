<?php

namespace App\Filament\Resources\AudioFileResource\Pages;

use App\Filament\Resources\AudioFileResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAudioFiles extends ListRecords
{
    protected static string $resource = AudioFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
