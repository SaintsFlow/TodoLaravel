## Todo-List api


### Слои архитектуры:
1. **Контроллеры** (`App\Http\Controllers\Api`) - обработка HTTP запросов
2. **Сервисы** (`App\Services`) - бизнес-логика
3. **Репозитории** (`App\Repositories`) - работа с данными
4. **Модели** (`App\Models`) - представление данных
5. **Ресурсы** (`App\Http\Resources`) - форматирование ответов
6. **Запросы** (`App\Http\Requests`) - валидация входящих данных
7. **Политики** (`App\Policies`) - авторизация и права доступа

## Аутентификация

### Регистрация
```bash
POST /api/auth/register
Content-Type: application/json

{
    "name": "Иван Иванов",
    "email": "ivan@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

### Вход
```bash
POST /api/auth/login
Content-Type: application/json

{
    "email": "ivan@example.com",
    "password": "password123"
}
```

### Получение информации о пользователе
```bash
GET /api/auth/me
Authorization: Bearer {token}
```

### Выход
```bash
POST /api/auth/logout
Authorization: Bearer {token}
```

## Работа с TODO

Все запросы к TODO требуют авторизации через Bearer токен.

### Получение списка TODO
```bash
GET /api/todos
Authorization: Bearer {token}

# С фильтрами
GET /api/todos?status=PENDING&priority=HIGH&search=работа&per_page=10
```

### Создание TODO
```bash
POST /api/todos
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "Завершить проект",
    "description": "Необходимо закончить разработку TODO API",
    "priority": "HIGH",
    "status": "PENDING",
    "color": "#FF5733",
    "assigned_to_id": 2,
    "due_date": "2025-07-15 15:00:00",
    "is_private": false
}
```

### Получение конкретного TODO
```bash
GET /api/todos/1
Authorization: Bearer {token}
```

### Обновление TODO
```bash
PUT /api/todos/1
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "Обновленное название",
    "status": "IN_PROGRESS"
}
```

### Удаление TODO
```bash
DELETE /api/todos/1
Authorization: Bearer {token}
```

### Отметить как выполненное
```bash
PATCH /api/todos/1/complete
Authorization: Bearer {token}
```

### Архивировать TODO
```bash
PATCH /api/todos/1/archive
Authorization: Bearer {token}
```

### Фильтрация TODO

#### По статусу
```bash
GET /api/todos/status/PENDING
Authorization: Bearer {token}
```

#### По приоритету
```bash
GET /api/todos/priority/HIGH
Authorization: Bearer {token}
```

#### Назначенные мне
```bash
GET /api/todos/assigned/me
Authorization: Bearer {token}
```

#### Созданные мной
```bash
GET /api/todos/created/me
Authorization: Bearer {token}
```

## Доступные значения

### Статусы (TodoStatus)
- `PENDING` - Ожидает выполнения
- `IN_PROGRESS` - В процессе
- `COMPLETED` - Выполнено
- `ARCHIVED` - Архивировано

### Приоритеты (TodoPriority)
- `LOW` - Низкий
- `NORMAL` - Обычный
- `HIGH` - Высокий
- `URGENT` - Срочный

## Права доступа

### Просмотр TODO:
- Автор может видеть все свои TODO
- Исполнитель может видеть назначенные ему TODO
- Публичные TODO (is_private=false) видны всем

### Редактирование/Выполнение TODO:
- Автор может редактировать свои TODO
- Исполнитель может отмечать как выполненные назначенные ему TODO

### Удаление/Архивирование TODO:
- Только автор может удалять и архивировать свои TODO

## Коды ответов

- `200` - Успешный запрос
- `201` - Ресурс создан
- `401` - Не авторизован
- `403` - Доступ запрещен
- `404` - Ресурс не найден
- `422` - Ошибка валидации

## Примеры ответов

### Успешный ответ при создании TODO
```json
{
    "message": "Todo создан успешно",
    "data": {
        "id": 1,
        "title": "Завершить проект",
        "description": "Необходимо закончить разработку TODO API",
        "color": "#FF5733",
        "priority": {
            "value": "HIGH",
            "label": "High"
        },
        "status": {
            "value": "PENDING", 
            "label": "Pending"
        },
        "is_completed": false,
        "is_archived": false,
        "is_private": false,
        "due_date": "2025-07-15 15:00:00",
        "completed_at": null,
        "archived_at": null,
        "author": {
            "id": 1,
            "name": "Иван Иванов",
            "email": "ivan@example.com",
            "created_at": "2025-07-07 12:00:00"
        },
        "assigned_to": {
            "id": 2,
            "name": "Петр Петров", 
            "email": "petr@example.com",
            "created_at": "2025-07-07 12:00:00"
        },
        "created_at": "2025-07-07 12:00:00",
        "updated_at": "2025-07-07 12:00:00"
    }
}
```

### Ошибка валидации
```json
{
    "success": false,
    "message": "Ошибка валидации",
    "errors": {
        "title": ["Название задачи обязательно"],
        "due_date": ["Дата выполнения должна быть в будущем"]
    }
}
```
