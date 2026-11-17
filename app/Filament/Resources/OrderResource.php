<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers\PersonsRelationManager;
use App\Models\Order;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationLabel = 'Замовлення';

    protected static ?string $modelLabel = 'Замовлення';

    protected static ?string $pluralModelLabel = 'Замовлення';

    protected static ?int $navigationSort = 20;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Основна інформація')
                    ->schema([
                        Select::make('user_id')
                            ->label('Клієнт')
                            ->relationship('user', 'email')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('product_id')
                            ->label('Продукт')
                            ->relationship('product', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        DatePicker::make('lesson_date')
                            ->label('Дата заняття')
                            ->required()
                            ->native(false)
                            ->displayFormat('d.m.Y'),

                        TextInput::make('hours')
                            ->label('Години')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->maxValue(8),
                    ])
                    ->columns(2),

                Section::make('Ціна')
                    ->schema([
                        TextInput::make('base_price')
                            ->label('Базова ціна')
                            ->numeric()
                            ->required()
                            ->suffix('грн'),

                        TextInput::make('extra_person_price')
                            ->label('За додаткового учасника')
                            ->numeric()
                            ->required()
                            ->suffix('грн'),

                        TextInput::make('total_price')
                            ->label('Загальна сума')
                            ->numeric()
                            ->required()
                            ->suffix('грн'),
                    ])
                    ->columns(3),

                Section::make('Статус та оплата')
                    ->schema([
                        Select::make('status')
                            ->label('Статус')
                            ->options(
                                collect(OrderStatus::cases())
                                    ->mapWithKeys(fn (OrderStatus $status) => [$status->value => $status->label()])
                            )
                            ->required(),

                        TextInput::make('payment_id')
                            ->label('ID платежу')
                            ->maxLength(255),

                        TextInput::make('payment_url')
                            ->label('URL оплати')
                            ->url()
                            ->maxLength(255),

                        TextInput::make('ticket_url')
                            ->label('URL квитка')
                            ->url()
                            ->maxLength(255),
                    ])
                    ->columns(2),

                Section::make('Додатково')
                    ->schema([
                        Textarea::make('notes')
                            ->label('Коментарі клієнта')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                TextColumn::make('user.full_name')
                    ->label('Клієнт')
                    ->searchable(['user.first_name', 'user.last_name'])
                    ->sortable(),

                TextColumn::make('product.name')
                    ->label('Продукт')
                    ->sortable(),

                TextColumn::make('lesson_date')
                    ->label('Дата')
                    ->date('d.m.Y')
                    ->sortable(),

                TextColumn::make('hours')
                    ->label('Год.')
                    ->suffix(' год'),

                TextColumn::make('total_price')
                    ->label('Сума')
                    ->money('UAH')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Статус')
                    ->formatStateUsing(fn (OrderStatus $state) => $state->label())
                    ->badge()
                    ->color(fn (OrderStatus $state) => match ($state) {
                        OrderStatus::Pending => 'gray',
                        OrderStatus::Paid => 'info',
                        OrderStatus::Completed => 'success',
                        OrderStatus::Cancelled => 'danger',
                        OrderStatus::Refunded => 'warning',
                    }),

                TextColumn::make('created_at')
                    ->label('Створено')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Статус')
                    ->options(
                        collect(OrderStatus::cases())
                            ->mapWithKeys(fn (OrderStatus $status) => [$status->value => $status->label()])
                    ),
            ])
            ->actions([
                ViewAction::make(),
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
            PersonsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
