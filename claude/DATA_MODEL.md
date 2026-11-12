# Elite Ski Club — Модель даних

## Діаграма зв'язків

```
users (клієнти)
  │
  ├── hasMany → students (учні)
  │               │
  └── hasMany → orders (замовлення) ← belongsTo products
                  │
                  └── hasMany → order_students ← belongsTo students

products
  │
  ├── hasMany → orders
  └── hasMany → product_prices ← belongsTo seasons

admins (окрема таблиця для адмінів)
```

---

## Таблиці

### admins

Адміністратори системи (Filament).

| Поле | Тип | Опис |
|------|-----|------|
| id | bigint | PK |
| name | string | Ім'я адміна |
| email | string, unique | Email |
| password | string | Пароль |
| role | tinyint | 1=admin, 2=superadmin |
| remember_token | string, nullable | |
| created_at | timestamp | |
| updated_at | timestamp | |

```php
enum AdminRole: int
{
    case Admin = 1;
    case SuperAdmin = 2;
}
```

---

### users

Клієнти (платники).

| Поле | Тип | Опис |
|------|-----|------|
| id | bigint | PK |
| first_name | string | Ім'я |
| last_name | string | Прізвище |
| email | string, unique | Email |
| phone | string | Телефон |
| password | string | Пароль |
| email_verified_at | timestamp, nullable | Підтвердження пошти |
| remember_token | string, nullable | |
| created_at | timestamp | |
| updated_at | timestamp | |

---

### students

Учні (ті, хто катається).

| Поле | Тип | Опис |
|------|-----|------|
| id | bigint | PK |
| user_id | bigint, FK → users | Власник (платник) |
| first_name | string | Ім'я |
| phone | string, nullable | Телефон (nullable для дітей) |
| birth_date | date | Дата народження |
| skill_level | tinyint | 1=beginner, 2=novice, 3=intermediate, 4=pro |
| lessons_count | int, default 0 | Кількість відкатаних занять |
| created_at | timestamp | |
| updated_at | timestamp | |

```php
enum SkillLevel: int
{
    case Beginner = 1;     // Початківець
    case Novice = 2;       // Новачок
    case Intermediate = 3; // Умілий
    case Pro = 4;          // Профі
}
```

**Вікові групи (обчислюється з birth_date):**
- 3-6 років — тільки індивідуально
- 7-12 років — тільки з однолітками
- 13+ років — можуть з дорослими

---

### seasons

Сезони з цінами.

| Поле | Тип | Опис |
|------|-----|------|
| id | bigint | PK |
| name | string | Назва (напр. "Новорічні свята 2025") |
| type | tinyint | 1=low, 2=high, 3=holiday |
| date_from | date | Початок періоду |
| date_to | date | Кінець періоду |
| created_at | timestamp | |
| updated_at | timestamp | |

```php
enum SeasonType: int
{
    case Low = 1;     // Низький сезон
    case High = 2;    // Високий сезон
    case Holiday = 3; // Святковий сезон
}
```

**Примітка:** Один тип сезону може мати кілька записів (періодів).

---

### products

Послуги (заняття).

| Поле | Тип | Опис |
|------|-----|------|
| id | bigint | PK |
| name | string | Системна назва (напр. "1 година") |
| title | string | Назва для фронту |
| description | text, nullable | Опис |
| hours | tinyint | Кількість годин (1-8) |
| is_active | boolean, default true | Чи активний |
| created_at | timestamp | |
| updated_at | timestamp | |

---

### product_prices

Ціни продуктів по сезонах.

| Поле | Тип | Опис |
|------|-----|------|
| id | bigint | PK |
| product_id | bigint, FK → products | Продукт |
| season_id | bigint, FK → seasons | Сезон |
| price | int | Базова ціна (в копійках або грн) |
| additional_price | int | Ціна за додаткового учня |
| created_at | timestamp | |
| updated_at | timestamp | |

**Unique:** product_id + season_id

---

### orders

Замовлення.

| Поле | Тип | Опис |
|------|-----|------|
| id | bigint | PK |
| user_id | bigint, FK → users | Клієнт (платник) |
| product_id | bigint, FK → products | Продукт |
| lesson_date | date | Дата заняття |
| hours | tinyint | Кількість годин (snapshot) |
| base_price | int | Snapshot базової ціни |
| extra_student_price | int | Snapshot ціни за додаткового учня |
| total_price | int | Загальна сума |
| status | tinyint | 1=pending, 2=paid, 3=completed, 4=cancelled, 5=refunded |
| payment_id | string, nullable | ID платежу MonoPay |
| created_at | timestamp | |
| updated_at | timestamp | |

```php
enum OrderStatus: int
{
    case Pending = 1;   // Неоплачено
    case Paid = 2;      // Оплачено
    case Completed = 3; // Виконано
    case Cancelled = 4; // Скасовано
    case Refunded = 5;  // Повернено
}
```

---

### order_students

Учні в замовленні (pivot).

| Поле | Тип | Опис |
|------|-----|------|
| id | bigint | PK |
| order_id | bigint, FK → orders | Замовлення |
| student_id | bigint, FK → students | Учень |
| price | int | Ціна за цього учня |
| is_primary | boolean | Перший учень (повна ціна) чи додатковий |
| receipt_url | string, nullable | URL фіскального чеку |
| created_at | timestamp | |
| updated_at | timestamp | |

---

## Зв'язки

```php
// Admin
Admin::class — немає зв'язків

// User
User::students() → hasMany(Student::class)
User::orders() → hasMany(Order::class)

// Student
Student::user() → belongsTo(User::class)
Student::orderStudents() → hasMany(OrderStudent::class)
Student::orders() → belongsToMany(Order::class, 'order_students')

// Season
Season::productPrices() → hasMany(ProductPrice::class)
Season::products() → belongsToMany(Product::class, 'product_prices')

// Product
Product::productPrices() → hasMany(ProductPrice::class)
Product::seasons() → belongsToMany(Season::class, 'product_prices')

// ProductPrice
ProductPrice::product() → belongsTo(Product::class)
ProductPrice::season() → belongsTo(Season::class)

// Order
Order::user() → belongsTo(User::class)
Order::product() → belongsTo(Product::class)
Order::orderStudents() → hasMany(OrderStudent::class)
Order::students() → belongsToMany(Student::class, 'order_students')

// Product (додаємо)
Product::orders() → hasMany(Order::class)

// OrderStudent
OrderStudent::order() → belongsTo(Order::class)
OrderStudent::student() → belongsTo(Student::class)
```

---

## Індекси

- `users.email` — unique
- `admins.email` — unique
- `students.user_id` — index
- `product_prices.product_id + season_id` — unique
- `orders.user_id` — index
- `orders.product_id` — index
- `orders.status` — index
- `orders.lesson_date` — index
- `order_students.order_id` — index
- `order_students.student_id` — index
- `seasons.date_from, date_to` — index (для пошуку сезону по даті)
