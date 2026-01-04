Ниже — **ключевые преимущества этой архитектуры**, с пояснениями *почему они возникают именно из описанных ограничений*, и с **наглядными примерами на PHP**.

---

## 1. Жёсткое разделение ответственности (Strong SRP)

### Почему это преимущество

Каждый тип Unit выполняет **строго одну роль**:

| Тип      | Ответственность       |
| -------- | --------------------- |
| Servitor | бизнес-правило        |
| DAO      | доступ к данным       |
| Mapper   | преобразование данных |
| Unit     | технический контракт  |

Это сильнее классического SRP из SOLID, потому что:

* SRP enforced **на уровне архитектуры**, а не договорённостей
* невозможно «случайно» смешать бизнес-логику и инфраструктуру

### Пример

```php
final class GetUserByIdDao extends Unit
{
    public int $id;
    public ?UserEntity $result = null;

    public function execute(): void
    {
        $row = $this->db->fetch('SELECT * FROM users WHERE id = ?', [$this->id]);

        $mapper = new UserRowToEntityMapper();
        $mapper->row = $row;
        $mapper->execute();

        $this->result = $mapper->entity;
    }
}
```

👉 DAO **не знает**, зачем нужен пользователь
👉 Mapper **не знает**, откуда данные
👉 Servitor **не знает**, как именно данные хранятся

---

## 2. Прозрачный и предсказуемый flow выполнения

### Почему это преимущество

У каждого Unit:

* один публичный метод
* нет аргументов
* нет возврата значений
* вход/выход только через свойства

➡ **выполнение всегда выглядит одинаково**

### Пример Servitor

```php
class RegisterUserServitor extends Unit
{
    public string $email;
    public string $password;
    public UserEntity $user;

    public function execute(): void
    {
        $dao = new InsertUserDao();
        $dao->email = $this->email;
        $dao->passwordHash = password_hash($this->password, PASSWORD_BCRYPT);
        $dao->execute();

        $this->user = $dao->user;
    }
}
```

Читая код, ты сразу видишь:

1. какие данные нужны
2. что будет результатом
3. порядок шагов

Без «магии» аргументов и return’ов.

---

## 3. Минимальная связность (Low Coupling)

### Почему это преимущество

Запреты работают как **архитектурные предохранители**:

* DAO ❌ не может вызвать Servitor
* Mapper ❌ не может вызвать DAO
* Servitor ❌ не может вызвать Mapper
* DAO ❌ не может вызвать DAO

➡ невозможно создать:

* циклические зависимости
* «божественные» классы
* скрытые побочные эффекты

### Пример: допустимая цепочка

```
Controller
  → Servitor
      → DAO
          → Mapper
```

❌ **невозможно**:

```
Mapper → DAO → Servitor → Mapper
```

---

## 4. Очень простое тестирование (без мок-фреймворков)

### Почему это преимущество

* нет конструкторных зависимостей
* нет аргументов методов
* всё состояние — публичные свойства

➡ тест = заполнить свойства → execute() → проверить свойства

### Пример unit-теста

```php
public function testRegisterUser(): void
{
    $servitor = new RegisterUserServitor();

    $servitor->email = 'test@example.com';
    $servitor->password = '123456';

    $servitor->execute();

    $this->assertInstanceOf(UserEntity::class, $servitor->user);
}
```

Без:

* моков
* DI-фреймворков
* сложной инициализации

---

## 5. Архитектура масштабируется без деградации

### Почему это преимущество

Когда проект растёт:

* бизнес-правила **не раздуваются**
* каждый Servitor = одно правило
* каждая операция = отдельный DAO

➡ вместо:

```
UserService.php (2000 строк)
```

ты получаешь:

```
User/
 ├── RegisterUserServitor.php
 ├── ChangePasswordServitor.php
 ├── GetUserByIdDao.php
 ├── InsertUserDao.php
 └── UserRowToEntityMapper.php
```

---

## 6. Отсутствие «скрытых контрактов»

### Почему это преимущество

Запрет:

* сеттеров/геттеров
* аргументов
* return значений

➡ контракт класса **виден сразу**

### Пример

```php
class ChangeUserEmailServitor extends Unit
{
    public int $userId;
    public string $newEmail;
    public bool $success = false;

    public function execute(): void
    {
        // логика
        $this->success = true;
    }
}
```

Без открытия метода ты уже знаешь:

* что требуется
* что будет результатом

---

## 7. Идеально подходит для CQRS и Clean Architecture

### Почему

* Servitor ≈ UseCase
* DAO ≈ Gateway
* Mapper ≈ Presenter / Data Mapper

Но:

* **проще**
* **жёстче**
* **меньше абстракций**

➡ это Clean Architecture без overengineering.

---

## 8. Архитектура защищает от ошибок команды

Это **архитектура с запретами**, а не рекомендациями.

Даже junior:

* не сможет вернуть массив
* не сможет положить SQL в Servitor
* не сможет вызвать DAO из DAO

➡ ошибки **структурно невозможны**

---

## Краткое резюме

### Основные преимущества

✔ строгий SRP
✔ предсказуемый execution flow
✔ минимальная связность
✔ простейшее тестирование
✔ высокая масштабируемость
✔ отсутствие скрытых контрактов
✔ защита архитектуры от деградации
