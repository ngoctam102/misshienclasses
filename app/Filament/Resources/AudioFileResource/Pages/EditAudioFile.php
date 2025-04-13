<?php

namespace App\Filament\Resources\AudioFileResource\Pages;

use App\Filament\Resources\AudioFileResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAudioFile extends EditRecord
{
    protected static string $resource = AudioFileResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
