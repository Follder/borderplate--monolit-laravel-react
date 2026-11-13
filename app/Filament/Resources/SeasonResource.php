<?php

namespace App\Filament\Resources;

use App\Enums\SeasonType;
use App\Filament\Resources\SeasonResource\Pages;
use App\Models\Season;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SeasonResource extends Resource
{
    protected static ?string $model = Season::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $navigationLabel = 'Сезони';

    protected static ?string $modelLabel = 'Сезон';

    protected static ?string $pluralModelLabel = 'Сезони';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Назва')
                    ->placeholder('Новорічні свята 2025')
                    ->required()
                    ->maxLength(255),

                Select::make('type')
                    ->label('Тип сезону')
                    ->options(
                        collect(SeasonType::cases())
                            ->mapWithKeys(fn (SeasonType $type) => [$type->value => $type->label()])
                    )
                    ->required(),

                DatePicker::make('date_from')
                    ->label('Дата початку')
                    ->required()
                    ->native(false)
                    ->displayFormat('d.m.Y'),

                DatePicker::make('date_to')
                    ->label('Дата закінчення')
                    ->required()
                    ->native(false)
                    ->displayFormat('d.m.Y')
                    ->after('date_from'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Назва')
                    ->searchable(),

                TextColumn::make('type')
                    ->label('Тип')
                    ->formatStateUsing(fn (SeasonType $state) => $state->label())
                    ->badge()
                    ->color(fn (SeasonType $state) => match ($state) {
                        SeasonType::Low => 'gray',
                        SeasonType::High => 'warning',
                        SeasonType::Holiday => 'danger',
                    }),

                TextColumn::make('date_from')
                    ->label('Початок')
                    ->date('d.m.Y')
                    ->sortable(),

                TextColumn::make('date_to')
                    ->label('Кінець')
                    ->date('d.m.Y')
                    ->sortable(),
            ])
            ->defaultSort('date_from', 'desc')
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
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
            'index' => Pages\ListSeasons::route('/'),
            'create' => Pages\CreateSeason::route('/create'),
            'edit' => Pages\EditSeason::route('/{record}/edit'),
        ];
    }
}
