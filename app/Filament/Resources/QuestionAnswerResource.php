<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionAnswerResource\Pages;
use App\Models\QuestionAnswer;
use App\Models\QuestionOption;
use App\Models\MatchingOption;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Session;

class QuestionAnswerResource extends Resource
{
    protected static ?string $model = QuestionAnswer::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';

    protected static ?string $navigationGroup = 'Quản lý câu hỏi';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('test_id')
                    ->relationship('test', 'title')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->default(fn() => Session::get('last_test_id'))
                    ->afterStateUpdated(function ($state) {
                        Session::put('last_test_id', $state);
                    }),
                Forms\Components\Select::make('passage_id')
                    ->relationship(
                        'passage',
                        'title',
                        fn(Builder $query, $get) =>
                        $query->when(
                            $get('test_id'),
                            fn(Builder $q, $testId) =>
                            $q->where('test_id', $testId)
                        )
                    )
                    ->required()
                    ->live()
                    ->default(fn() => Session::get('last_passage_id'))
                    ->afterStateUpdated(function ($state) {
                        Session::put('last_passage_id', $state);
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
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->default(fn() => Session::get('last_group_id'))
                    ->afterStateUpdated(function ($state) {
                        Session::put('last_group_id', $state);
                    }),
                Forms\Components\Select::make('question_id')
                    ->relationship(
                        'question',
                        'order',
                        fn(Builder $query, Forms\Get $get) =>
                        $query->when(
                            $get('group_id'),
                            fn(Builder $q, $groupId) =>
                            $q->where('group_id', $groupId)
                        )
                    )
                    ->searchable()
                    ->preload()
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                        if ($state) {
                            $question = \App\Models\Question::find($state);
                            if ($question) {
                                $set('question_type', $question->question_type);
                            }
                        }
                    }),
                Forms\Components\Hidden::make('question_type'),
                Forms\Components\Section::make('Answer')
                    ->schema(function (Forms\Get $get) {
                        $questionType = $get('question_type');
                        $questionId = $get('question_id');

                        switch ($questionType) {
                            case 'fill_in_blank':
                                return [
                                    Forms\Components\TextInput::make('correct_answer')
                                        ->label('Đáp án đúng')
                                        ->required()
                                        ->maxLength(255),
                                ];

                            case 'fill_in_blank_with_options':
                            case 'matching':
                            case 'correct_answer':
                                return [
                                    Forms\Components\Select::make('correct_answer')
                                        ->label('Đáp án đúng')
                                        ->options(function () use ($questionId, $questionType) {
                                            if (!$questionId) return [];

                                            if ($questionType === 'matching') {
                                                $question = \App\Models\Question::find($questionId);
                                                if (!$question) return [];

                                                return MatchingOption::where('group_id', $question->group_id)
                                                    ->pluck('option_text', 'id')
                                                    ->toArray();
                                            } else {
                                                return QuestionOption::where('question_id', $questionId)
                                                    ->pluck('option_text', 'id')
                                                    ->toArray();
                                            }
                                        })
                                        ->required()
                                        ->live(),
                                ];

                            case 'true_false_not_given':
                                return [
                                    Forms\Components\Select::make('correct_answer')
                                        ->label('Đáp án đúng')
                                        ->options([
                                            'true' => 'true',
                                            'false' => 'false',
                                            'not_given' => 'not_given'
                                        ])
                                        ->required(),
                                ];

                            case 'multiple_choice':
                                return [
                                    Forms\Components\CheckboxList::make('correct_answers')
                                        ->label('Đáp án đúng')
                                        ->options(function () use ($questionId) {
                                            if (!$questionId) return [];
                                            return QuestionOption::where('question_id', $questionId)
                                                ->pluck('option_text', 'id')
                                                ->toArray();
                                        })
                                        ->required()
                                        ->afterStateUpdated(function ($state, Forms\Set $set) {
                                            // Lưu các đáp án đúng dưới dạng JSON
                                            $set('correct_answer', json_encode($state));
                                        }),
                                ];

                            default:
                                return [
                                    Forms\Components\TextInput::make('correct_answer')
                                        ->label('Đáp án đúng')
                                        ->required()
                                        ->maxLength(255),
                                ];
                        }
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
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('question.question_type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'fill_in_blank' => 'gray',
                        'fill_in_blank_with_options' => 'gray',
                        'correct_answer' => 'success',
                        'true_false_not_given' => 'warning',
                        'multiple_choice' => 'info',
                        'matching' => 'primary',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('correct_answer')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('test')
                    ->relationship('test', 'title'),
                Tables\Filters\SelectFilter::make('passage')
                    ->relationship('passage', 'title'),
                Tables\Filters\SelectFilter::make('group')
                    ->relationship('group', 'description'),
                Tables\Filters\SelectFilter::make('question_type')
                    ->options([
                        'fill_in_blank' => 'Fill in blank',
                        'correct_answer' => 'Correct answer',
                        'true_false_not_given' => 'True/False/Not given',
                        'multiple_choice' => 'Multiple choice',
                        'matching' => 'Matching',
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
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuestionAnswers::route('/'),
            'create' => Pages\CreateQuestionAnswer::route('/create'),
            'edit' => Pages\EditQuestionAnswer::route('/{record}/edit'),
        ];
    }
}
