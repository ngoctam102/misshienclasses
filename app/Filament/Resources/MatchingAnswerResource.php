<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MatchingAnswerResource\Pages;
use App\Models\MatchingAnswer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MatchingAnswerResource extends Resource
{
    protected static ?string $model = MatchingAnswer::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';

    protected static ?string $navigationGroup = 'Quản lý Matching';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('test_id')
                    ->relationship('test', 'title')
                    ->required()
                    ->live(),
                Forms\Components\Select::make('group_id')
                    ->relationship(
                        'group',
                        'description',
                        fn(Builder $query, Forms\Get $get) =>
                        $query->when(
                            $get('test_id'),
                            fn(Builder $q, $testId) =>
                            $q->where('test_id', $testId)
                        )
                    )
                    ->required()
                    ->live(),
                Forms\Components\Select::make('question_id')
                    ->relationship(
                        'question',
                        'question_content',
                        fn(Builder $query, Forms\Get $get) =>
                        $query->when(
                            $get('group_id'),
                            fn(Builder $q, $groupId) =>
                            $q->where('group_id', $groupId)
                        )
                    )
                    ->nullable()
                    ->live(),
                Forms\Components\Select::make('matching_question_id')
                    ->relationship(
                        'matchingQuestion',
                        'matching_text',
                        fn(Builder $query, Forms\Get $get) =>
                        $query->when(
                            $get('question_id'),
                            fn(Builder $q, $questionId) =>
                            $q->where('question_id', $questionId)
                        )
                    )
                    ->nullable()
                    ->live(),
                Forms\Components\Select::make('matching_option_id')
                    ->relationship(
                        'matchingOption',
                        'option_text',
                        fn(Builder $query, Forms\Get $get) =>
                        $query->when(
                            $get('test_id'),
                            fn(Builder $q, $testId) =>
                            $q->where('test_id', $testId)
                        )
                            ->when(
                                $get('group_id'),
                                fn(Builder $q, $groupId) =>
                                $q->where('group_id', $groupId)
                            )
                    )
                    ->required()
                    ->live(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('test.title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('group.description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('question.question_content')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('matchingQuestion.matching_text')
                    ->limit(50)
                    ->searchable()
                    ->label('Matching Question'),
                Tables\Columns\TextColumn::make('matchingOption.option_text')
                    ->limit(50)
                    ->searchable()
                    ->label('Matching Option'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('test')
                    ->relationship('test', 'title'),
                Tables\Filters\SelectFilter::make('group')
                    ->relationship('group', 'description'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMatchingAnswers::route('/'),
            'create' => Pages\CreateMatchingAnswer::route('/create'),
            'edit' => Pages\EditMatchingAnswer::route('/{record}/edit'),
        ];
    }
}
