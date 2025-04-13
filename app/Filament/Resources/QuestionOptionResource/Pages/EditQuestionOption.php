<?php

namespace App\Filament\Resources\QuestionOptionResource\Pages;

use App\Filament\Resources\QuestionOptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuestionOption extends EditRecord
{
    protected static string $resource = QuestionOptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
