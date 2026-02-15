# Laravel + React + Filament Template

Монолітний темплейт для швидкого старту проектів з Laravel, React (Inertia.js) та Filament.

## Технології

- **Laravel 12** - PHP фреймворк
- **React 19** - UI бібліотека
- **Inertia.js** - Modern monolith
- **Filament 3** - Admin панель
- **TypeScript** - Type-safe JS
- **Tailwind CSS** - Utility-first CSS
- **Spatie Media Library** - Робота з медіа файлами
- **PHPStan/Larastan** - Статичний аналізатор PHP
- **Biome** - Лінтер та форматер для JS/TS
- **Docker** - Контейнеризація

---

# User flow

## Mount container

```bash
docker compose up -d
```

## Unmount container
```bash
docker compose down
```

## Go to container
```bash
docker compose exec app sh
```

## Run php stun on local machine

```bash
docker compose exec -t app  ./vendor/bin/phpstan analyse
```

Якщо використовуєте Docker:

### Запустити контейнери
```bash
docker-compose up -d
```

### Зайти в контейнер та виконати команди
```bash
docker exec -it yaremche-app bash
```

### Всередині контейнера:
```bash
composer install
php artisan key:generate
php artisan migrate
php artisan storage:link
npm install
npm run build
```

---

## Встановлення проекту

### 1. Клонування репозиторію

```bash
git clone <repo-url> <project-name>
cd <project-name>
```

### 2. Запуск через Docker (рекомендовано)

```bash
# Піднімаємо контейнери
docker compose up -d

# Заходимо в контейнер
docker compose exec app sh

# Всередині контейнера:
composer install
php artisan key:generate
php artisan migrate
php artisan storage:link
npm install
npm run build
```

### 3. Локальна розробка (без Docker)

```bash
# PHP залежності
composer install

# Node.js залежності
npm install

# Налаштування
cp .env.example .env
php artisan key:generate

# Міграції (налаштуйте .env для вашої БД)
php artisan migrate

# Storage
php artisan storage:link
```

---

## Розробка

### Запуск dev сервера

```bash
# З Docker
docker compose exec app sh
npm run dev

# Локально
composer run dev
# або окремо:
php artisan serve
npm run dev
```

### Створення адміністратора Filament

```bash
php artisan make:filament-user
```

Доступ до адмін панелі: `http://localhost/admin`

---

## Корисні команди

### Code Quality

```bash
# PHPStan
./vendor/bin/phpstan analyse
# або
composer run phpstan

# TypeScript перевірка
npx tsc --noEmit

# Biome lint
npm run lint
npm run lint:fix

# Форматування
npm run format
```

### Тестування

```bash
php artisan test
# або
composer run test
```

### Docker команди

```bash
# Піднімаємо контейнери
docker compose up -d

# Зупиняємо
docker compose down

# Зайти в контейнер
docker compose exec app sh

# Логи
docker compose logs -f
```

---

## Структура проекту

```
app/
├── Http/
│   ├── Controllers/     # Контролери
│   └── Middleware/      # Middleware (Inertia)
├── Models/             # Eloquent моделі
├── Providers/
│   └── Filament/       # Filament панелі
├── Filament/
│   └── Resources/      # Filament Resources
├── Repositories/       # GET запити (читання)
└── Services/           # POST/PUT/DELETE + бізнес-логіка

resources/
├── js/
│   ├── Components/     # React компоненти
│   ├── Layouts/        # Layouts
│   ├── Pages/          # Inertia сторінки
│   ├── types/          # TypeScript типи
│   ├── app.tsx         # Entry point
│   └── bootstrap.ts    # Axios конфігурація
├── css/
│   └── app.css         # Tailwind CSS
└── views/
    └── app.blade.php   # Inertia root template

database/
├── migrations/         # Міграції
├── seeders/           # Seeders
└── factories/         # Factories
```

---

## Конвенції

### TypeScript

- Використовуй `type` замість `interface`
- Структура `types/` відзеркалює структуру React компонентів
- Імпорт типів через `import type { ... } from '@/types'`

### Структура Backend

- `app/Repositories/` - GET запити (читання)
- `app/Services/` - POST/PUT/DELETE + бізнес-логіка
- `resources/js/types/` - TypeScript типи

---

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

```tsx
{restaurant.image_url && (
    <img src={restaurant.image_url} alt={restaurant.name} />
)}
```

---

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

---

## Ліцензія

MIT

