<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Product;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Продукти';

    protected static ?string $modelLabel = 'Продукт';

    protected static ?string $pluralModelLabel = 'Продукти';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Системна назва')
                    ->placeholder('1 година')
                    ->required()
                    ->maxLength(255),

                TextInput::make('title')
                    ->label('Назва для сайту')
                    ->placeholder('Індивідуальне заняття 1 година')
                    ->required()
                    ->maxLength(255),

                TextInput::make('hours')
                    ->label('Кількість годин')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->maxValue(8),

                TextInput::make('max_persons')
                    ->label('Максимум учасників')
                    ->numeric()
                    ->default(4)
                    ->minValue(1)
                    ->maxValue(10),

                TextInput::make('min_age')
                    ->label('Мінімальний вік')
                    ->numeric()
                    ->minValue(3)
                    ->maxValue(99)
                    ->placeholder('Без обмежень'),

                TextInput::make('max_age')
                    ->label('Максимальний вік')
                    ->numeric()
                    ->minValue(3)
                    ->maxValue(99)
                    ->placeholder('Без обмежень'),

                Textarea::make('description')
                    ->label('Опис')
                    ->rows(3)
                    ->columnSpanFull(),

                Toggle::make('is_active')
                    ->label('Активний')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Назва')
                    ->searchable(),

                TextColumn::make('hours')
                    ->label('Години')
                    ->suffix(' год')
                    ->sortable(),

                TextColumn::make('max_persons')
                    ->label('Макс. учасників')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Активний')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('Створено')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('hours')
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
