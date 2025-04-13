<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MatchingOptionResource\Pages;
use App\Models\MatchingOption;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class MatchingOptionResource extends Resource
{
    protected static ?string $model = MatchingOption::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $navigationGroup = 'Quản lý Matching';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('test_id')
                    ->relationship('test', 'title')
                    ->required()
                    ->live(),
                Forms\Components\Select::make('passage_id')
                    ->relationship(
                        'passage',
                        'title',
                        fn(Builder $query, Forms\Get $get) =>
                        $query->when(
                            $get('test_id'),
                            fn(Builder $q, $testId) =>
                            $q->where('test_id', $testId)
                        )
                    )
                    ->required()
                    ->live(),
                Forms\Components\Select::make('group_id')
                    ->relationship(
                        'group',
                        'description',
                        fn(Builder $query, Forms\Get $get) =>
                        $query->when(
                            $get('passage_id'),
                            fn(Builder $q, $passageId) =>
                            $q->where('passage_id', $passageId)
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
                    ->live(),
                Forms\Components\Textarea::make('option_text')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('order')
                    ->numeric()
                    ->default(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('test.title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('passage.title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('group.description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('question.question_content')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('option_text')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('test')
                    ->relationship('test', 'title'),
                Tables\Filters\SelectFilter::make('passage')
                    ->relationship('passage', 'title'),
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
            'index' => Pages\ListMatchingOptions::route('/'),
            'create' => Pages\CreateMatchingOption::route('/create'),
            'edit' => Pages\EditMatchingOption::route('/{record}/edit'),
        ];
    }
}
