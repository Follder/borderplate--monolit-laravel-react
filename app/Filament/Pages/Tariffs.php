<?php

namespace App\Filament\Pages;

use App\Enums\SeasonType;
use App\Models\Product;
use App\Models\ProductPrice;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

/**
 * @property Form $form
 */
class Tariffs extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $navigationLabel = 'Тарифи';

    protected static ?string $title = 'Тарифи';

    protected static ?int $navigationSort = 3;

    protected static string $view = 'filament.pages.tariffs';

    /** @var array<string, mixed> */
    public array $data = [];

    /** @var Collection<int, Product> */
    public Collection $products;

    public function mount(): void
    {
        $this->products = Product::query()
            ->where('is_active', true)
            ->orderBy('hours')
            ->get();

        $formData = [];

        foreach (SeasonType::cases() as $type) {
            foreach ($this->products as $product) {
                $price = ProductPrice::query()
                    ->where('product_id', $product->id)
                    ->where('season_type', $type->value)
                    ->first();

                $key = "type_{$type->value}_product_{$product->id}";
                $formData["{$key}_price"] = $price->price ?? 0;
                $formData["{$key}_additional"] = $price->additional_price ?? 0;
            }
        }

        $this->form->fill($formData);
    }

    public function form(Form $form): Form
    {
        $columns = [];

        foreach (SeasonType::cases() as $type) {
            $productFields = [];

            foreach ($this->products as $product) {
                $key = "type_{$type->value}_product_{$product->id}";

                $productFields[] = Section::make("{$product->name} ({$product->hours} год)")
                    ->schema([
                        TextInput::make("{$key}_price")
                            ->label('Ціна')
                            ->numeric()
                            ->suffix('грн')
                            ->minValue(0),

                        TextInput::make("{$key}_additional")
                            ->label('Дод. учень')
                            ->numeric()
                            ->suffix('грн')
                            ->minValue(0),
                    ])
                    ->compact();
            }

            $columns[] = Section::make($type->label())
                ->schema($productFields)
                ->columnSpan(1);
        }

        return $form
            ->schema($columns)
            ->columns(3)
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach (SeasonType::cases() as $type) {
            foreach ($this->products as $product) {
                $key = "type_{$type->value}_product_{$product->id}";

                $priceValue = $data["{$key}_price"] ?? 0;
                $additionalValue = $data["{$key}_additional"] ?? 0;
                $price = is_numeric($priceValue) ? (int) $priceValue : 0;
                $additional = is_numeric($additionalValue) ? (int) $additionalValue : 0;

                if ($price > 0 || $additional > 0) {
                    ProductPrice::updateOrCreate(
                        [
                            'product_id' => $product->id,
                            'season_type' => $type->value,
                        ],
                        [
                            'price' => $price,
                            'additional_price' => $additional,
                        ]
                    );
                }
            }
        }

        Notification::make()
            ->success()
            ->title('Тарифи збережено')
            ->send();
    }
}
