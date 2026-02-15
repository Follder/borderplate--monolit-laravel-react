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

## Встановлення проекту

### 1. Клонування репозиторію

```bash
git clone <repo-url> <project-name>
cd <project-name>
```

### 2. Підвʼязка темплейту під новий репозиторій

```bash
rm -rf .git
git init
git remote add origin <repo-url>
git branch -M main
git push -u origin main

```

### 3. Запуск контейнерів

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

---

## Розробка

### Запуск dev сервера

```bash
# З Docker
docker compose exec app sh
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
```bash
docker compose exec -t app  ./vendor/bin/phpstan analyse

# TypeScript перевірка
docker compose exec -t app  npx tsc --noEmit

# Biome lint
docker compose exec -t app  npm run lint
docker compose exec -t app  npm run lint:fix

# Форматування
docker compose exec -t app  npm run format
```

### Тестування

```bash
docker compose exec -t app php artisan test
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
