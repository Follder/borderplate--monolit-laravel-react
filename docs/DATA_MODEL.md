# Модель даних проекту Yaremche

## Огляд

Система побудована з **окремими таблицями для кожного типу закладів**. Це забезпечує чітку структуру даних та валідацію на рівні БД.

### Підхід: окремі таблиці

Кожен тип закладу має власну таблицю зі специфічними полями:

- `restaurants` — ресторани, кафе, бари
- `hotels` — готелі (Phase 2)
- `attractions` — визначні місця (Phase 2)
- `routes` — туристичні маршрути (Phase 2)

**Переваги:**
- Чітка структура для кожного типу
- Валідація на рівні БД (NOT NULL, типи даних)
- Індекси на специфічні поля
- Простіша підтримка та розширення

## Основні таблиці

### 1. `restaurants` — Заклади харчування

```sql
restaurants
├── id (bigint, PK)
├── name (string)
├── slug (string, unique)
├── description (text, nullable)
├── short_description (string, nullable)
├── address (string)
├── latitude (decimal, nullable)
├── longitude (decimal, nullable)
├── phone (string, nullable)
├── email (string, nullable)
├── website (string, nullable)
├── type (enum: 'restaurant', 'cafe', 'bar')
├── cuisine (json, nullable) // ["ukrainian", "european"]
├── price_range (tinyint, nullable) // 1-4
├── average_check (integer, nullable)
├── opening_hours (json, nullable)
├── has_delivery (boolean, default: false)
├── has_takeaway (boolean, default: false)
├── is_active (boolean, default: true)
├── is_featured (boolean, default: false)
├── created_at (timestamp)
├── updated_at (timestamp)
└── deleted_at (timestamp, nullable)
```

### 2. `categories` — Категорії

```sql
categories
├── id (bigint, PK)
├── parent_id (bigint, FK → categories, nullable)
├── name (string)
├── slug (string, unique)
├── icon (string, nullable)
├── sort_order (integer, default: 0)
├── created_at (timestamp)
└── updated_at (timestamp)
```

### 3. `tags` — Теги

```sql
tags
├── id (bigint, PK)
├── name (string)
├── slug (string, unique)
├── type (enum: 'feature', 'cuisine', 'amenity')
├── created_at (timestamp)
└── updated_at (timestamp)
```

## Архітектура коду

### Repository Pattern

Всі запити до БД виконуються через Repositories:

```
app/
├── Models/
│   └── Restaurant.php          # Тільки fillable, casts, relationships
├── Repositories/
│   └── Restaurant/
│       └── RestaurantRepository.php  # GET запити
└── Services/
    └── Restaurant/
        └── RestaurantService.php     # POST/PUT/DELETE (Phase 2)
```

### Приклад використання

```php
// В контролері
class RestaurantController extends Controller
{
    public function __construct(
        private RestaurantRepository $repository
    ) {}

    public function index()
    {
        $restaurants = $this->repository->getActive();
        return Inertia::render('Restaurants/Index', compact('restaurants'));
    }

    public function cafes()
    {
        $cafes = $this->repository->getCafes();
        return Inertia::render('Restaurants/Index', ['restaurants' => $cafes]);
    }
}
```

## Майбутні таблиці (Phase 2+)

### `hotels` — Готелі

```sql
hotels
├── id, name, slug, description, address, coordinates...
├── stars (tinyint) // 1-5
├── rooms_count (integer)
├── has_parking (boolean)
├── has_wifi (boolean)
├── has_pool (boolean)
├── check_in_time (time)
├── check_out_time (time)
└── ...
```

### `attractions` — Визначні місця

```sql
attractions
├── id, name, slug, description, address, coordinates...
├── type (enum: 'monument', 'museum', 'park', 'waterfall')
├── entrance_fee (integer, nullable)
├── duration (string) // рекомендований час відвідування
├── requires_booking (boolean)
└── ...
```

### `routes` — Туристичні маршрути

```sql
routes
├── id, name, slug, description...
├── difficulty (enum: 'easy', 'moderate', 'hard')
├── distance (decimal) // в км
├── duration (string) // "2 години"
├── gpx_file (string, nullable)
└── ...
```

---

**Останнє оновлення**: 7 лютого 2026
