<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AudioFileResource\Pages;
use App\Models\AudioFile;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AudioFileResource extends Resource
{
    protected static ?string $model = AudioFile::class;

    protected static ?string $navigationIcon = 'heroicon-o-musical-note';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('test_id')
                    ->relationship('test', 'title')
                    ->required()
                    ->live(),
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('audio_url')
                    ->label('Audio File')
                    ->required()
                    ->acceptedFileTypes(['audio/mpeg', 'audio/wav', 'audio/ogg'])
                    ->directory('audio-files'),
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
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('order')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('test')
                    ->relationship('test', 'title'),
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
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAudioFiles::route('/'),
            'create' => Pages\CreateAudioFile::route('/create'),
            'edit' => Pages\EditAudioFile::route('/{record}/edit'),
        ];
    }
}
