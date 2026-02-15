# Архітектура проекту Yaremche

## 🏗️ Загальна архітектура

Проект побудований за принципом **Modern Monolith** з використанням **Inertia.js**, що дозволяє створювати SPA (Single Page Application) без необхідності розробки окремого REST API.

```
┌─────────────────────────────────────────────────┐
│                   Browser                        │
│  ┌────────────────────────────────────────────┐ │
│  │         React + Inertia.js Client          │ │
│  └────────────────────────────────────────────┘ │
└──────────────────┬──────────────────────────────┘
                   │ Inertia Protocol
┌──────────────────┴──────────────────────────────┐
│            Laravel Application                   │
│  ┌────────────────────────────────────────────┐ │
│  │     Controllers (Inertia Responses)        │ │
│  └────────────────────────────────────────────┘ │
│  ┌────────────────────────────────────────────┐ │
│  │     Models & Business Logic                │ │
│  └────────────────────────────────────────────┘ │
│  ┌────────────────────────────────────────────┐ │
│  │     Filament Admin Panel                   │ │
│  └────────────────────────────────────────────┘ │
└──────────────────┬──────────────────────────────┘
                   │
┌──────────────────┴──────────────────────────────┐
│              MySQL Database                      │
└──────────────────────────────────────────────────┘
```

## 📂 Структура проекту

```
yaremche/
├── app/
│   ├── Filament/              # Filament Admin Resources
│   ├── Http/
│   │   ├── Controllers/       # Inertia Controllers
│   │   └── Middleware/        # Custom Middleware
│   ├── Models/                # Eloquent Models
│   ├── Repositories/          # GET запити до БД (читання)
│   │   └── {Controller}/      # Папка для кожного контролера
│   │       └── {Controller}Repository.php
│   └── Services/              # POST/PUT/DELETE + бізнес-логіка
│       └── {Controller}/      # Папка для кожного контролера
│           └── {Controller}Service.php
├── database/
│   ├── migrations/            # Database Migrations
│   ├── seeders/               # Data Seeders
│   └── factories/             # Model Factories
├── resources/
│   ├── js/
│   │   ├── Components/        # Shared React Components
│   │   ├── Layouts/           # Layout Components
│   │   ├── Pages/             # Page Components
│   │   ├── types/             # TypeScript Types (mirrors component structure)
│   │   └── app.tsx            # Entry Point
│   ├── css/
│   │   └── app.css            # Tailwind CSS
│   └── views/
│       └── app.blade.php      # Main Blade Template
├── routes/
│   └── web.php                # Web Routes
├── docs/                      # Project Documentation
└── public/                    # Public Assets
```

## 🔄 Архітектурні принципи

### 1. Separation of Concerns

**Frontend (React + Inertia.js)**
- Відповідає за презентаційну логіку
- Компоненти розділені на:
  - **Pages** - сторінки додатку
  - **Layouts** - обгортки для сторінок
  - **Components** - переіспользувані компоненти

**Backend (Laravel)**
- Відповідає за бізнес-логіку
- **Models** - Eloquent моделі (тільки зв'язки та атрибути)
- **Controllers** - обробка запитів та повернення Inertia відповідей
- **Repositories** - GET запити до бази даних (читання)
- **Services** - бізнес-логіка та POST/PUT/DELETE запити (запис)

### 2. Filament для адміністрування

Filament працює окремо від Inertia.js частини:
- URL: `/admin`
- Повністю автономний CRUD інтерфейс
- Власна аутентифікація
- Ресурси для кожної сутності

### 3. Repository/Service Pattern

Чітке розділення відповідальності для роботи з даними:

```
app/
├── Repositories/
│   └── Place/
│       └── PlaceRepository.php      # GET запити
└── Services/
    └── Place/
        └── PlaceService.php         # POST/PUT/DELETE + логіка
```

**Repository** — відповідає за читання даних:
- Всі SELECT запити до БД
- Фільтрація, сортування, пагінація
- Eager loading зв'язків

**Service** — відповідає за запис та логіку:
- CREATE, UPDATE, DELETE операції
- Валідація бізнес-правил
- Складна бізнес-логіка

**Приклад використання в контролері:**
```php
class PlaceController extends Controller
{
    public function __construct(
        private PlaceRepository $repository,
        private PlaceService $service
    ) {}

    public function index()
    {
        $places = $this->repository->getAll();
        return Inertia::render('Places/Index', compact('places'));
    }

    public function store(Request $request)
    {
        $place = $this->service->create($request->validated());
        return redirect()->route('places.show', $place);
    }
}
```

### 4. Data Flow

```
User Action (React)
    → Inertia Request
    → Laravel Controller
    → Repository (GET) / Service (POST/PUT/DELETE)
    → Database
    → Inertia Response (with data)
    → React Re-render
```

## 🔐 Безпека

### Аутентифікація
- **Публічна частина**: без аутентифікації (Phase 1)
- **Адмін панель**: Filament Authentication
- **Майбутнє (Phase 2)**: Laravel Sanctum для API

### Авторизація
- Filament Policies для адмін панелі
- Laravel Gates для складних правил
- Middleware для захисту маршрутів

### CSRF Protection
- Автоматично для всіх POST/PUT/DELETE запитів
- Вбудовано в Inertia.js

## 📊 База даних

### Підхід
- **Migrations First** - всі зміни через міграції
- **Seeders** - для тестових даних
- **Factories** - для генерації фейкових даних

### Індексація
- Індекси на поля для пошуку (name, type, category)
- Full-text search індекси (опціонально)
- Foreign keys з каскадним видаленням

## 🎨 Frontend Architecture

### TypeScript

Проект використовує **TypeScript** для типізації React компонентів.

**Конвенції:**
- Використовуємо `type` замість `interface` для всіх типів
- Імпортуємо типи через `import type { ... } from '@/types'`
- Структура `types/` відзеркалює структуру React компонентів

### Types Structure

```
resources/js/types/
├── index.ts                              # Базові моделі (Restaurant, Category, Tag тощо)
│                                         # + реекспорти всіх типів компонентів
├── Components/
│   └── Restaurants/
│       └── RestaurantCard.ts             # RestaurantCardProps
├── Layouts/
│   └── MainLayout.ts                     # NavigationItem, SocialLink, MainLayoutProps
└── Pages/
    ├── Home.ts                           # Feature
    └── Restaurants/
        ├── Index.ts                      # RestaurantsIndexProps
        └── Show.ts                       # RestaurantShowProps
```

**Приклад типу:**
```typescript
// resources/js/types/Pages/Restaurants/Index.ts
import type { Restaurant } from '../../index';

export type RestaurantsIndexProps = {
    restaurants: Restaurant[];
};
```

**Приклад використання в компоненті:**
```typescript
// resources/js/Pages/Restaurants/Index.tsx
import type { RestaurantsIndexProps } from '@/types';

export default function Index({ restaurants }: RestaurantsIndexProps) {
    // ...
}
```

### Component Structure

```
Components/
├── Common/              # Загальні компоненти
│   ├── Button.tsx
│   ├── Card.tsx
│   └── Modal.tsx
├── Restaurants/         # Компоненти для ресторанів
│   └── RestaurantCard.tsx
└── Map/                 # Карта
    └── MapView.tsx

Layouts/
└── MainLayout.tsx       # Головний layout з header/footer

Pages/
├── Home.tsx
├── Restaurants/
│   ├── Index.tsx        # Список
│   └── Show.tsx         # Деталі
└── Routes/
    └── Index.tsx
```

### State Management

**Phase 1 (MVP)**
- React useState/useContext для локального стану
- Inertia.js props для серверного стану
- Без додаткових state management бібліотек

**Phase 2 (якщо потрібно)**
- Zustand або React Query для клієнтського кешування

## 🚀 Performance

### Backend
- **Query Optimization**: Eager loading (with/load)
- **Caching**: Laravel Cache для статичних даних
- **Indexes**: На всі пошукові поля

### Frontend
- **Code Splitting**: Автоматично через Vite
- **Lazy Loading**: React.lazy() для великих компонентів
- **Image Optimization**: Responsive images, lazy loading

### Infrastructure
- **Nginx**: Static file caching
- **PHP-FPM**: Оптимізація пулу воркерів
- **Database**: Query caching, connection pooling

## 🧪 Testing Strategy

### Backend (Laravel)
- **Feature Tests**: Тести для контролерів та API
- **Unit Tests**: Тести для сервісів та моделей
- PHPUnit / Pest

### Frontend (React)
- **Component Tests**: Jest + React Testing Library
- **E2E Tests**: Cypress (опціонально)

## 📦 Deployment

### Development
```bash
docker-compose up
npm run dev
```

### Production
```bash
docker-compose -f docker-compose.prod.yml up -d
npm run build
php artisan optimize
```

### CI/CD (Майбутнє)
- GitHub Actions
- Automated tests
- Deployment на staging/production

## 🔮 Майбутні покращення

### Phase 2
- API для мобільного додатку (Laravel Sanctum)
- Redis для кешування та черг
- Elasticsearch для швидкого пошуку

### Phase 3
- GraphQL API (опціонально)
- WebSocket для real-time оновлень
- CDN для статичних файлів

## 📋 Architecture Decision Records

Детальні рішення про архітектуру знаходяться в папці [decisions/](decisions/):
- [ADR-001: Чому Inertia.js замість REST API](decisions/001-why-inertia.md)
- [ADR-002: Структура бази даних](decisions/002-database-structure.md)

---

**Останнє оновлення**: 8 лютого 2025
