# future-group
Test task by Future

**[Описание задания](./examples/task.md)**
_____

## Annotation:
- Тестовое выполнено согласно приложению, за исключением: update записи реализорван http методами PUT|PATCH
- Так же методы POST|PUT|PATCH|DELETE защищены аутентификацией, [подробнее:](#usage)
- Тестирование api произвожу инструментами postman и аналогов, внутреннее тестирование кода произвожу посредством дампа опасных участков кода и продумыванием всех возможных пограничных случаев. Общий принцип тестирование - скормить как можно больше разношерстных данных в тестируемые методы. В будущем для облегчения тестирования планирую изучить Unit тесты.
- Проект собран в docker-compose связке php-8.1 fpm + Nginx + Mysql:8.0
## Description:
- ссылка на swagger документацию: /api/documentation
- [swagger файл](./app-future/storage/api-docs/api-docs.json)


## Build:
Предварительно необходимо на основе .env.example создать файл .env

Cборка осуществляется вызовами:
```
# загрузка образов и построение контейнеров
docker-compose up --build -d
```
```
# Пробрасывание переменных окружения в контейнер
docker-compose cp ./.env app:/var/www/app-future
```
```
# Переход в контейнер приложения
docker-compose exec app bash
```
Либо вызов команд выше через make:
```
make docker
```
Далее внутри контейнера:
```
# установка зависимостей
composer install
```
```
# установка миграций БД
php artisan migrate
```
```
# заполнение БД
php artisan db:seed
```
Либо вызов команд выше через make:
```
make install
```
## Usage:
Тестовый юзер после установки миграций и заполнения БД:
```json
"email": "testing@test.org"
"password": "1234567890"
```
Необходимо по роуту `/auth` пройти аутентификацию и получить токен `-H Authorization: Bearer <token>`

Далее можно делать запросы по роутам согласно заданию:

| **Method** |          **PATH**      | **Need auth** |          **Description**     |
|:----------:|:----------------------:|:-------------:|:----------------------------:|
| GET\|HEAD  | /api/v1/notebooks      | false         | Получение всего списка записей (доступны query параметры limit и offset) |
| GET\|HEAD  | /api/v1/notebooks/{id} | false         | Получение записи по id |
| POST       | /api/v1/notebooks      | true          | Создание новой записи |
| PUT\|PATCH | /api/v1/notebooks/{id} | true          | Обновление записи по id |
| DELETE     | /api/v1/notebooks/{id} | true          | Удаление записи по id |

**Прочие роуты**
| **Method** |      **PATH**     |          **Description**     |
|:----------:|:-----------------:|:----------------------------:|
| POST       | / api/auth        | Аутентификация API |
| GET        | api/documentation | документация swagger |

## Structure:
Стуктура проекта:
- app-future >> Корень проекта
    - app
        - Exceptions/[Handler.php](./app-future/app/Exceptions/Handler.php) >> Обработчик исключений
        - Http
            - Controllers
                - Api/[AuthController.php](./app-future/app/Http/Controllers/Api/AuthController.php) >> Контроллер api аутентификации
                - [NotebookController.php](./app-future/app/Http/Controllers/NotebookController.php) >> Notebook контроллер
            - Requests/[NotebookRequest.php](./app-future/app/Http/Requests/NotebookRequest.php) >> обертка Notebook запросов
        - Models
            - [Notebook.php](./app-future/app/Models/Notebook.php) >> Notebook модель
            - [User.php](./app-future/app/Models/User.php) >> User модель
    - database
        - factories >> директория фабрик БД
        - migrations >> директория миграций БД
        - seeders >> директория заполнителей БД
    - routes
        - [api.php]() >> Обработчик роутов api
    - storage/api-docs/[api-docs.json](./app-future/storage/api-docs/api-docs.json) >> документация swagger
    - `.env` >> переменные окружения laravel (автоматически пробрасывается внутрь контейнера)
- examples >> директория вводных файлов
- images >> директория образов и настроек docker
- project_database >> volume базы данных из контейнера
- `.env` >> переменные окружения проекта
- [.env.example](./.env.example) >> переменные окружения проекта (пример)
- [docker-compose.yml](./docker-compose.yml) >> развертывание контейнеров
