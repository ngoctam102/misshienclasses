<?php

namespace App\Filament\Resources\MatchingOptionResource\Pages;

use App\Filament\Resources\MatchingOptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMatchingOption extends EditRecord
{
    protected static string $resource = MatchingOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
