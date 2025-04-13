<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionOptionResource\Pages;
use App\Filament\Resources\QuestionOptionResource\RelationManagers;
use App\Models\QuestionOption;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestionOptionResource extends Resource
{
    protected static ?string $model = QuestionOption::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $navigationGroup = 'Quản lý câu hỏi';

    protected static ?int $navigationSort = 2;

    public static function fillFormWithDefaults(array $data): array
    {
        $data['test_id'] = session('last_test_id');
        $data['passage_id'] = session('last_passage_id');
        $data['group_id'] = session('last_group_id');
        $data['question_id'] = session('last_question_id');
        $data['option_text'] = session('last_option_text');
        $data['order'] = session('last_order', 1);

        return $data;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('test_id')
                    ->relationship('test', 'title')
                    ->required()
                    ->live()
                    ->default(session('last_test_id'))
                    ->afterStateUpdated(function ($state) {
                        session(['last_test_id' => $state]);
                    }),
                Forms\Components\Select::make('passage_id')
                    ->relationship(
                        'passage',
                        'order',
                        fn(Builder $query, Forms\Get $get) =>
                        $query->when(
                            $get('test_id'),
                            fn(Builder $q, $testId) =>
                            $q->where('test_id', $testId)
                        )
                    )
                    ->required()
                    ->live()
                    ->default(session('last_passage_id'))
                    ->afterStateUpdated(function ($state) {
                        session(['last_passage_id' => $state]);
                    }),
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
                    ->live()
                    ->default(session('last_group_id'))
                    ->afterStateUpdated(function ($state) {
                        session(['last_group_id' => $state]);
                    }),
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
                    ->required()
                    ->live()
                    ->default(session('last_question_id'))
                    ->afterStateUpdated(function ($state) {
                        session(['last_question_id' => $state]);
                    }),
                Forms\Components\Textarea::make('option_text')
                    ->required()
                    ->maxLength(65535)
                    ->columnSpanFull()
                    ->default(session('last_option_text'))
                    ->afterStateUpdated(function ($state) {
                        session(['last_option_text' => $state]);
                    }),
                Forms\Components\TextInput::make('order')
                    ->numeric()
                    ->default(session('last_order', 1))
                    ->required()
                    ->afterStateUpdated(function ($state) {
                        session(['last_order' => $state]);
                    }),
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
                Tables\Filters\SelectFilter::make('question')
                    ->relationship('question', 'question_content'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order', 'asc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuestionOptions::route('/'),
            'create' => Pages\CreateQuestionOption::route('/create'),
            'edit' => Pages\EditQuestionOption::route('/{record}/edit'),
        ];
    }

    public static function afterCreate(array $data): void
    {
        session([
            'last_test_id' => $data['test_id'],
            'last_passage_id' => $data['passage_id'],
            'last_group_id' => $data['group_id'],
            'last_question_id' => $data['question_id'],
            'last_option_text' => $data['option_text'],
            'last_order' => $data['order'] ?? 1,
        ]);
    }
}
