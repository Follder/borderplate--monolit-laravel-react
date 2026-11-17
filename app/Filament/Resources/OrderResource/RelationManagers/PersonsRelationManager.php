<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use App\Enums\SkillLevel;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Actions\DetachAction;
use Filament\Tables\Actions\DetachBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
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
                TextInput::make('price')
                    ->label('Ціна')
                    ->numeric()
                    ->required()
                    ->suffix('грн'),

                Toggle::make('is_primary')
                    ->label('Основний учасник')
                    ->helperText('Перший учасник платить повну ціну'),

                TextInput::make('receipt_url')
                    ->label('URL чека')
                    ->url()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('first_name')
                    ->label("Ім'я"),

                TextColumn::make('age')
                    ->label('Вік')
                    ->suffix(' р.'),

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

                IconColumn::make('pivot.is_primary')
                    ->label('Основний')
                    ->boolean(),

                TextColumn::make('pivot.price')
                    ->label('Ціна')
                    ->money('UAH'),
            ])
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect(),
                        TextInput::make('price')
                            ->label('Ціна')
                            ->numeric()
                            ->required()
                            ->suffix('грн'),
                        Toggle::make('is_primary')
                            ->label('Основний учасник'),
                    ]),
            ])
            ->actions([
                EditAction::make(),
                DetachAction::make(),
            ])
            ->bulkActions([
                DetachBulkAction::make(),
            ]);
    }
}
