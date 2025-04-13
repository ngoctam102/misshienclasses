<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionResource\Pages;
use App\Filament\Resources\QuestionResource\RelationManagers;
use App\Models\Question;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationGroup = 'Quản lý bài kiểm tra';

    protected static ?int $navigationSort = 4;

    public static function fillFormWithDefaults(array $data): array
    {
        $data['test_id'] = session('last_test_id');
        $data['passage_id'] = session('last_passage_id');
        $data['group_id'] = session('last_group_id');
        $data['question_type'] = session('last_question_type');
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
                Forms\Components\Select::make('question_type')
                    ->required()
                    ->options([
                        'fill_in_blank' => 'Fill in the blank',
                        'correct_answer' => 'Correct answer',
                        'true_false_not_given' => 'True/False/Not Given',
                        'multiple_choice' => 'Multiple choice',
                        'matching' => 'Matching',
                        'fill_in_blank_with_options' => 'Fill in the blank with options'
                    ])
                    ->live()
                    ->default(session('last_question_type'))
                    ->afterStateUpdated(function ($state) {
                        session(['last_question_type' => $state]);
                    }),
                Forms\Components\RichEditor::make('question_content')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('order')
                    ->numeric()
                    ->default(1),
                Forms\Components\RichEditor::make('explanation')
                    ->label('Explanation')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('test.title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('passage.title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('group.description')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('question_type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'fill_in_blank' => 'gray',
                        'correct_answer' => 'success',
                        'true_false_not_given' => 'warning',
                        'multiple_choice' => 'info',
                        'matching' => 'primary',
                        'fill_in_blank_with_options' => 'danger',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
            ])
            ->defaultSort('order', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('test')
                    ->relationship('test', 'title')
                    ->searchable(),
                Tables\Filters\SelectFilter::make('passage')
                    ->relationship('passage', 'title')
                    ->searchable(),
                Tables\Filters\SelectFilter::make('group')
                    ->relationship('group', 'description')
                    ->searchable(),
                Tables\Filters\SelectFilter::make('question_type')
                    ->options([
                        'fill_in_blank' => 'Fill in blank',
                        'correct_answer' => 'Correct answer',
                        'true_false_not_given' => 'True/False/Not given',
                        'multiple_choice' => 'Multiple choice',
                        'matching' => 'Matching',
                        'fill_in_blank_with_options' => 'Fill in blank with options'
                    ])
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
            ->paginated([10, 25, 50, 100])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data): array {
                        session([
                            'last_test_id' => $data['test_id'],
                            'last_passage_id' => $data['passage_id'],
                            'last_group_id' => $data['group_id'],
                            'last_question_type' => $data['question_type'],
                            'last_order' => $data['order'] ?? 1,
                        ]);
                        return $data;
                    }),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuestions::route('/'),
            'create' => Pages\CreateQuestion::route('/create'),
            'edit' => Pages\EditQuestion::route('/{record}/edit'),
        ];
    }

    public static function afterCreate(array $data): void
    {
        session([
            'last_test_id' => $data['test_id'],
            'last_passage_id' => $data['passage_id'],
            'last_group_id' => $data['group_id'],
            'last_question_type' => $data['question_type'],
            'last_order' => $data['order'] ?? 1,
        ]);
    }
}
