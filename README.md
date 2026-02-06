# MS-EL Backoffice (Laravel 10)

Через **Файл->Импорт** в Postman закидываем запросы для работы с API по эндпоинту `{{host}}/api/products`:  

```bash
tests/Postman/api-get-product.json
```

Бэкофис на **Laravel 10** для лендинга на **Tilda (ms-el.ru)**.  
Предназначен для управления товарами, категориями, складами и предоставляет HTTP API для получения списка товаров с фильтрацией, сортировкой и пагинацией.

---

## Стек технологий

- PHP 8.2+
- Laravel 10
- MySQL / PostgreSQL
- Eloquent ORM
- REST API
- Auth + Policies
- Throttling API
- Redis queue
- Redis cache

---

## Основной функционал

### Web (Backoffice)

- Авторизация пользователей
- Управление пользователями (CRUD, policies)
- Управление складами (Warehouse)
- Импорт данных
- Производители и вендоры
- Смена локали
- Защищённый доступ к админке
- Асинхронная отдача CSV и фоновая очистка диска от файлов экспорта
    - Стандартный запуск — удалить csv старше 1 дня из exports/

    ```bash
    php artisan exports:clean
    ```

    - Посмотреть что удалится, без удаления

    ```bash
    php artisan exports:clean --dry-run
    ```

    - Удалить xlsx файлы старше 7 дней

    ```bash
    php artisan exports:clean exports xlsx --days=7
    ```

    - Короткий флаг dry-run

    ```bash
    php artisan exports:clean -D
    ```

    - Другая папка

    ```bash
    php artisan exports:clean reports csv --days=30
    ```

### API (для фронта / Тильды)

- Получение списка товаров
- Фильтрация
- Сортировка
- Пагинация
- Ограничение запросов (rate limit)

---

## Структура роутов

### API Routes (`routes/api.php`)

```php
Route::get('/products', [ApiWarehouseController::class, 'index']);
```

### Web Routes (routes/web.php)

- `/dashboard` — управление пользователями
- `/warehouse` — склад и товары
- `/vendors`, `/manufacturer` — справочники
- `/import` — импорт данных
- `/setlocale/{locale}` — смена языка
- `/clear-cache` — очистка кэша (доступно только авторизованным)

## Модель Product

Товар содержит следующие поля:

| Поле        | Тип      | Описание        |
| ----------- | -------- | --------------- |
| id          | int      | ID товара       |
| name        | string   | Название        |
| price       | decimal  | Цена            |
| category_id | int      | Категория       |
| in_stock    | boolean  | В наличии       |
| rating      | float    | Рейтинг (0–5)   |
| created_at  | datetime | Дата создания   |
| updated_at  | datetime | Дата обновления |

## API: Получение товаров

Endpoint

```bash
GET /api/products
```

### Query-параметры фильтрации

| Параметр    | Тип     | Описание                       |
| ----------- | ------- | ------------------------------ |
| q           | string  | Поиск по `name` (LIKE / ILIKE) |
| price_from  | decimal | Минимальная цена               |
| price_to    | decimal | Максимальная цена              |
| category_id | int     | Категория                      |
| in_stock    | bool    | В наличии                      |
| rating_from | float   | Минимальный рейтинг            |

### Сортировка

| Значение    | Описание            |
| ----------- | ------------------- |
| price_asc   | Цена по возрастанию |
| price_desc  | Цена по убыванию    |
| rating_desc | Рейтинг по убыванию |
| newest      | Сначала новые       |

Пример:

```bash
/api/products?sort=price_desc
```

### Пагинация

```bash
/api/products?page=2&per_page=20
```

### Пример запроса

```bash
GET /api/products?q=iphone&price_from=50000&in_stock=true&sort=rating_desc
```

### Пример ответа

```bash
{
  "data": [
    {
      "id": 1,
      "name": "iPhone 15 Pro",
      "price": 129990,
      "category_id": 2,
      "in_stock": true,
      "rating": 4.8,
      "created_at": "2024-01-10T12:00:00",
      "updated_at": "2024-01-12T09:30:00"
    }
  ],
  "links": {
    "first": "...",
    "last": "...",
    "prev": null,
    "next": "..."
  },
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 124
  }
}

```

## Безопасность

- Web-раздел защищён auth и verified
- Используются Laravel Policies
- API можно ограничивать по IP или токену
- Rate limit для API (throttle)

## Установка

```bash
git clone https://github.com/username/ms-el-backoffice.git
cd ms-el-backoffice

composer install
cp .env.example .env
php artisan key:generate

php artisan migrate --seed
php artisan serve

```

## Тестовое задание

В рамках тестового задания реализовано:

- REST API для товаров
- Гибкая фильтрация
- Сортировка
- Пагинация
- Чистая архитектура контроллера
- Использование Query Builder / Eloquent
