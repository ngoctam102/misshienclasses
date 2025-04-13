<?php

namespace App\Filament\Resources\PassageResource\Pages;

use App\Filament\Resources\PassageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPassages extends ListRecords
{
    protected static string $resource = PassageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
