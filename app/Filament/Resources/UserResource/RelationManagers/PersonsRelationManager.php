<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Enums\Sex;
use App\Enums\SkillLevel;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PersonsRelationManager extends RelationManager
{
    protected static string $relationship = 'persons';

    protected static ?string $title = 'Учні';

    protected static ?string $modelLabel = 'Учень';

    protected static ?string $pluralModelLabel = 'Учні';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('first_name')
                    ->label("Ім'я")
                    ->required()
                    ->maxLength(255),

                TextInput::make('phone')
                    ->label('Телефон')
                    ->tel()
                    ->maxLength(255),

                DatePicker::make('birth_date')
                    ->label('Дата народження')
                    ->required()
                    ->native(false)
                    ->displayFormat('d.m.Y')
                    ->maxDate(now()),

                Select::make('skill_level')
                    ->label('Рівень катання')
                    ->options(
                        collect(SkillLevel::cases())
                            ->mapWithKeys(fn (SkillLevel $level) => [$level->value => $level->label()])
                    )
                    ->required(),

                Select::make('sex')
                    ->label('Стать')
                    ->options(
                        collect(Sex::cases())
                            ->mapWithKeys(fn (Sex $sex) => [$sex->value => $sex->label()])
                    )
                    ->required(),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')
                    ->label("Ім'я")
                    ->searchable(),

                TextColumn::make('age')
                    ->label('Вік')
                    ->suffix(' р.'),

                TextColumn::make('age_group')
                    ->label('Група')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'children' => 'Діти (3-6)',
                        'juniors' => 'Юніори (7-12)',
                        'adults' => 'Дорослі (13+)',
                        default => $state,
                    })
                    ->color(fn (string $state) => match ($state) {
                        'children' => 'danger',
                        'juniors' => 'warning',
                        'adults' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('skill_level')
                    ->label('Рівень')
                    ->formatStateUsing(fn (SkillLevel $state) => $state->label())
                    ->badge()
                    ->color(fn (SkillLevel $state) => match ($state) {
                        SkillLevel::Beginner => 'gray',
                        SkillLevel::Novice => 'info',
                        SkillLevel::Intermediate => 'warning',
                        SkillLevel::Pro => 'success',
                    }),

                TextColumn::make('sex')
                    ->label('Стать')
                    ->formatStateUsing(fn (Sex $state) => $state->label()),

                TextColumn::make('lessons_count')
                    ->label('Занять'),
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }
}
