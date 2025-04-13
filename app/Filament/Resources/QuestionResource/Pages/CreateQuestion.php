<?php

namespace App\Filament\Resources\QuestionResource\Pages;

use App\Filament\Resources\QuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Passage;

class CreateQuestion extends CreateRecord
{
    protected static string $resource = QuestionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['passage_id'])) {
            $passage = Passage::find($data['passage_id']);
            if ($passage) {
                $data['test_id'] = $passage->test_id;
            }
        }
        return $data;
    }
}
