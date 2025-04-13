<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionGroupResource\Pages;
use App\Filament\Resources\QuestionGroupResource\RelationManagers;
use App\Models\QuestionGroup;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestionGroupResource extends Resource
{
    protected static ?string $model = QuestionGroup::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    protected static ?string $navigationGroup = 'Quản lý bài kiểm tra';

    protected static ?int $navigationSort = 3;

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
                    ->required(),
                Forms\Components\Select::make('audio_file_id')
                    ->relationship(
                        'audioFile',
                        'title',
                        fn(Builder $query, Forms\Get $get) =>
                        $query->when(
                            $get('test_id'),
                            fn(Builder $q, $testId) =>
                            $q->where('test_id', $testId)
                        )
                    )
                    ->visible(
                        fn(Forms\Get $get) =>
                        $get('test_id') ?
                            \App\Models\Test::find($get('test_id'))?->type === 'listening'
                            : false
                    ),
                Forms\Components\RichEditor::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\RichEditor::make('content')
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
                Tables\Columns\TextColumn::make('audioFile.title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('test')
                    ->relationship('test', 'title'),
                Tables\Filters\SelectFilter::make('passage')
                    ->relationship('passage', 'title'),
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
            'index' => Pages\ListQuestionGroups::route('/'),
            'create' => Pages\CreateQuestionGroup::route('/create'),
            'edit' => Pages\EditQuestionGroup::route('/{record}/edit'),
        ];
    }
}
