# Структура проекту Elite Ski Club API

## Загальні принципи

- Контролери максимально тонкі — тільки валідація та виклик сервісів/репозиторіїв
- Моделі максимально тонкі — тільки relationships, casts, attributes
- Вся логіка в сервісах
- Всі запити до БД в репозиторіях
- Активне використання Enums, DTO, Resources

---

## 1. Controllers

**Шлях:** `app/Http/Controllers/`

Кожен ресурсний контролер має свою папку, назва папки = назва контролера.

```
app/Http/Controllers/
├── UserController/
│   ├── UserController.php           # Ресурсний контролер (index, show, store, update, destroy)
│   ├── UserAvatarController.php     # Додатковий __invoke контролер
│   └── UserStatusController.php     # Ще один __invoke контролер
├── CourseController/
│   ├── CourseController.php
│   └── CourseEnrollController.php   # __invoke для запису на курс
└── ...
```

**Правила:**
- Назва контролера похідна від моделі: `User` → `UserController`
- Контролер НЕ містить логіки
- Тільки валідація через `$data = $request->validated()`
- Виклик репозиторіїв для GET запитів
- Виклик сервісів для POST/PUT/DELETE
- Повернення через Resources

**Приклад тонкого контролера:**

```php
class UserController extends Controller
{
    public function __construct(
        private UserRepository $repository,
        private UserService $service,
    ) {}

    public function index(UserIndexRequest $request): AnonymousResourceCollection
    {
        $data = $request->validated();
        $users = $this->repository->getAll($data);

        return UserResource::collection($users);
    }

    public function store(UserStoreRequest $request): UserResource
    {
        $data = $request->validated();
        $user = $this->service->create($data);

        return new UserResource($user);
    }

    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request, User $user): UserResource
    {
        $data = $request->validated();
        $user = $this->service->update($user, $data);

        return new UserResource($user);
    }

    public function destroy(User $user): JsonResponse
    {
        $this->service->delete($user);

        return response()->json(null, 204);
    }
}
```

---

## 2. Repositories

**Шлях:** `app/Repositories/`

Всі запити на **отримання даних** з БД. Кожна модель має свою папку.

```
app/Repositories/
├── Product/
│   └── ProductRepository.php
├── ProductAttribute/
│   └── ProductAttributeRepository.php
├── User/
│   └── UserRepository.php
├── Course/
│   └── CourseRepository.php
└── ...
```

**Правила:**
- Тільки SELECT запити (читання)
- Кожна модель = окрема папка
- Назва папки і файлу похідна від моделі: `Product` → `Product/ProductRepository.php`
- Фільтрація, сортування, пагінація
- Eager loading relationships

**Приклад:**

```php
class UserRepository
{
    public function getAll(array $filters = []): LengthAwarePaginator
    {
        return User::query()
            ->when($filters['role'] ?? null, fn($q, $role) => $q->where('role', $role))
            ->when($filters['search'] ?? null, fn($q, $search) => $q->where('name', 'like', "%{$search}%"))
            ->latest()
            ->paginate($filters['per_page'] ?? 15);
    }

    public function getById(int $id): ?User
    {
        return User::find($id);
    }

    public function getByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function getInstructors(): Collection
    {
        return User::where('role', UserRole::Instructor)->get();
    }
}
```

---

## 3. Services

**Шлях:** `app/Services/`

Сервіси діляться на 2 типи:

```
app/Services/
├── Model/                    # Запис в БД (CRUD операції)
│   ├── UserService.php
│   ├── CourseService.php
│   └── ...
└── Logic/                    # Бізнес-логіка
    ├── EnrollmentService.php
    ├── PaymentService.php
    └── ...
```

### Model Services

Відповідають за запис в конкретну таблицю БД. Групування по моделях.

```
app/Services/Model/
├── User/
│   └── UserService.php
├── Course/
│   └── CourseService.php
└── ...
```

```php
// app/Services/Model/User/UserService.php
class UserService
{
    public function create(array $data): User
    {
        return User::create($data);
    }

    public function update(User $user, array $data): User
    {
        $user->update($data);
        return $user->fresh();
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}
```

### Logic Services

Відповідають за бізнес-логіку (може працювати з кількома моделями).

```php
// app/Services/Logic/EnrollmentService.php
class EnrollmentService
{
    public function __construct(
        private CourseService $courseService,
        private UserService $userService,
        private PaymentService $paymentService,
    ) {}

    public function enrollUser(User $user, Course $course, EnrollmentDTO $dto): Enrollment
    {
        // Перевірка чи є місця
        // Створення оплати
        // Запис на курс
        // Відправка повідомлення
    }
}
```

---

## 4. Requests

**Шлях:** `app/Http/Requests/`

Кастомні Form Requests для валідації вхідних даних.

```
app/Http/Requests/
├── UserRequest/
│   ├── UserIndexRequest.php
│   ├── UserStoreRequest.php
│   └── UserUpdateRequest.php
├── CourseRequest/
│   ├── CourseStoreRequest.php
│   └── CourseUpdateRequest.php
└── ...
```

**Приклад:**

```php
class UserStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'phone' => ['required', 'string'],
            'role' => ['required', Rule::enum(UserRole::class)],
        ];
    }
}
```

---

## 5. Resources

**Шлях:** `app/Http/Resources/`

API Resources для форматування даних на фронтенд.

```
app/Http/Resources/
├── UserResource/
│   ├── UserResource.php
│   └── UserShortResource.php    # Скорочена версія
├── CourseResource/
│   ├── CourseResource.php
│   └── CourseListResource.php
└── ...
```

**Приклад:**

```php
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'role' => $this->role,
            'avatar_url' => $this->avatar_url,
            'created_at' => $this->created_at->toISOString(),
        ];
    }
}
```

---

## 6. DTOs

**Шлях:** `app/DTO/`

Data Transfer Objects для передачі даних між сервісами. Групування по моделях/доменах.

```
app/DTO/
├── Enrollment/
│   └── EnrollmentDTO.php
├── Payment/
│   ├── PaymentDTO.php
│   └── RefundDTO.php
└── ...
```

**Приклад:**

```php
readonly class EnrollmentDTO
{
    public function __construct(
        public int $userId,
        public int $courseId,
        public PaymentMethod $paymentMethod,
        public ?string $promoCode = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            userId: $data['user_id'],
            courseId: $data['course_id'],
            paymentMethod: PaymentMethod::from($data['payment_method']),
            promoCode: $data['promo_code'] ?? null,
        );
    }
}
```

---

## 7. Enums

**Шлях:** `app/Enums/`

```
app/Enums/
├── UserRole.php
├── CourseStatus.php
├── PaymentStatus.php
└── ...
```

**Приклад:**

```php
enum UserRole: string
{
    case Admin = 'admin';
    case Instructor = 'instructor';
    case Client = 'client';
}
```

---

## 8. Models

**Шлях:** `app/Models/`

Моделі максимально тонкі.

**Що має бути:**
- `$fillable` / `$guarded`
- `$casts` (мінімум)
- Relationships
- Attributes (accessors)

**Чого НЕ має бути:**
- Бізнес-логіки
- Запитів до БД (scopes допускаються мінімально)

**Приклад:**

```php
class User extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'role' => UserRole::class,
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'enrollments');
    }

    // Attributes
    public function getAvatarUrlAttribute(): ?string
    {
        return $this->getFirstMedia('avatar')?->getUrl();
    }
}
```

---

## 9. Tests

**Шлях:** `tests/`

Тести дзеркалять структуру `app/`.

```
tests/
├── Feature/
│   ├── Controllers/
│   │   └── UserController/
│   │       └── UserControllerTest.php
│   └── Services/
│       └── Logic/
│           └── EnrollmentServiceTest.php
└── Unit/
    ├── Repositories/
    │   └── User/
    │       └── UserRepositoryTest.php
    ├── Services/
    │   └── Model/
    │       └── User/
    │           └── UserServiceTest.php
    └── DTO/
        └── Enrollment/
            └── EnrollmentDTOTest.php
```

**Правила:**
- Feature тести — для контролерів, інтеграційні
- Unit тести — для репозиторіїв, сервісів, DTO
- Структура папок = структура `app/`

---

## 10. Filament (Admin Panel)

**Шлях:** `app/Filament/`

Стандартна структура Filament, але з правилами використання.

```
app/Filament/
├── Pages/                    # Сторінки для налаштувань, контенту
│   ├── Settings.php          # Загальні налаштування
│   ├── SeoSettings.php       # SEO налаштування
│   └── ContactsPage.php      # Контактна інформація
└── Resources/                # ТІЛЬКИ для сутностей зі списками
    ├── UserResource.php      # Користувачі (багато записів)
    ├── OrderResource.php     # Замовлення (багато записів)
    └── CourseResource.php    # Курси (багато записів)
```

**Правила:**

### Resources (списки) — використовувати коли:
- Багато записів (користувачі, замовлення, курси, інструктори)
- Потрібна таблиця з фільтрацією/сортуванням
- CRUD операції над колекцією

### Pages — використовувати коли:
- Один запис налаштувань (settings, конфігурація)
- Контент сторінки (про нас, контакти)
- Немає сенсу показувати таблицю з 1 рядком

**Приклад Page для налаштувань:**

```php
class Settings extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-cog';
    protected static string $view = 'filament.pages.settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill(Setting::getAllAsArray());
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('site_name')->required(),
                TextInput::make('site_email')->email(),
                Textarea::make('site_description'),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        Setting::setMany($this->form->getState());
        Notification::make()->success()->title('Збережено')->send();
    }
}
```

---

## Повна структура проекту

```
app/
├── DTO/
│   └── {Domain}/
│       └── {Name}DTO.php
├── Enums/
│   └── {Name}.php
├── Filament/
│   ├── Pages/                           # Налаштування, контент
│   └── Resources/                       # Списки (users, orders)
├── Http/
│   ├── Controllers/
│   │   └── {Model}Controller/
│   │       ├── {Model}Controller.php
│   │       └── {Action}Controller.php
│   ├── Requests/
│   │   └── {Model}Request/
│   │       └── {Model}{Action}Request.php
│   └── Resources/
│       └── {Model}Resource/
│           └── {Model}Resource.php
├── Models/
│   └── {Model}.php
├── Repositories/
│   └── {Model}/
│       └── {Model}Repository.php
├── Services/
│   ├── Model/
│   │   └── {Model}/
│   │       └── {Model}Service.php
│   └── Logic/
│       └── {Feature}Service.php
└── Providers/

tests/
├── Feature/                             # Дзеркалить app/Http/Controllers
│   └── Controllers/
│       └── {Model}Controller/
│           └── {Model}ControllerTest.php
└── Unit/                                # Дзеркалить app/ (Repositories, Services, DTO)
    ├── Repositories/
    ├── Services/
    └── DTO/
```
