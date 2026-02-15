# Claude Code Instructions

## Git Workflow

Після завершення будь-якої задачі **ОБОВ'ЯЗКОВО** виконати:

1. **PHPStan** - `./vendor/bin/phpstan analyse` (через Docker: `docker compose exec -T app ./vendor/bin/phpstan analyse`)
2. **Тести** - `php artisan test`
3. **Biome Lint** - `npm run lint` (автофікс: `npm run lint:fix`)
4. **TypeScript** - `npx tsc --noEmit`
5. **Build** - `npm run build`
6. **Коміт** - якщо все пройшло
7. **Push** - `git push -u origin <branch>`

**НЕ ЗАБУВАЙ PUSH!**

## TypeScript Conventions

- Використовуй `type` замість `interface`
- Структура `types/` відзеркалює структуру React компонентів
- Імпорт типів через `import type { ... } from '@/types'`

## Project Structure

- `app/Repositories/` - GET запити (читання)
- `app/Services/` - POST/PUT/DELETE + бізнес-логіка
- `resources/js/types/` - TypeScript типи

## Media Library (Spatie)

Для роботи з зображеннями використовується **Spatie Media Library**.

### Налаштування моделі

```php
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Restaurant extends Model implements HasMedia
{
    use InteractsWithMedia;

    // Акцесор для отримання URL зображення
    public function getImageUrlAttribute(): ?string
    {
        return $this->getFirstMedia('images')?->getUrl();
    }
}
```

### Використання в Filament

```php
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;

SpatieMediaLibraryFileUpload::make('image')
    ->label('Зображення')
    ->collection('images')
    ->image()
    ->maxSize(5120)
```

### Відображення у frontend

Модель автоматично повертає `image_url` через акцесор, який можна використовувати у React:

```tsx
{restaurant.image_url && (
    <img src={restaurant.image_url} alt={restaurant.name} />
)}
```
