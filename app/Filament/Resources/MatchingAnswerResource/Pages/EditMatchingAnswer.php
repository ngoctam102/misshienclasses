<?php

namespace App\Filament\Resources\MatchingAnswerResource\Pages;

use App\Filament\Resources\MatchingAnswerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMatchingAnswer extends EditRecord
{
    protected static string $resource = MatchingAnswerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
