Тестовое приложение REST API для Yii 2
===============================

### Первоначальное развёртывание

Предполагается, что базовое программное окружение настроено (docker, docker-compose).

1. Склонируйте проект с Гитхаба и перейдите в папку проекта.
2. Выполните в терминале команду `docker-compose run --rm backend composer install`
3. Выполните команду `docker-compose run --rm backend php /app/init --env=Development --overwrite=All --delete=All`
4. Обновите переменные в `common/config/main-local.php`:
```
'components' => [
    'db' => [
        'dsn' => env('DB_DSN'),
        'username' => env('DB_USER'),
        'password' => env('DB_PASSWORD'),
    ],
    
    ...
],
```
5. Запустите контейнеры: `docker-compose up -d`
6. Запустите миграции для основной базы данных: `docker-compose run --rm backend yii migrate`
7. Разверните сиды / фикстуры: `docker-compose run --rm backend yii seed/articles`
8. Проверьте работу тестовой страницы в браузере: http://127.0.0.1:20080

Команду `yii seed/articles` можно выполнять повторно. В этом случае данные в базе будут перезаписаны новыми.

### Работа с API

Доступны следующие эндпоинты API (общий префикс - http://127.0.0.1:21080/index.php):
* GET `/authors` - коллекция авторов статей
* GET `/authors/{id}` - автор статей
* GET `/categories` - коллекция категорий статей
* GET `/categories/{id}` - категория статьи
* GET `/articles` - коллекция статей
* GET `/articles/{id}` - статья

### Тесты

Для запуска тестов требуется развернуть тестовое окружение:

1. Обновите переменную DSN в `common/config/test-local.php`:
```
'components' => [
    'db' => [
        'dsn' => env('TEST_DB_DSN'),
    ],
    
    ...
],
```
2. Создайте тестовую базу с названием, указанным в `.env`-переменной `TEST_DB_DSN`. Если контейнеры запущены, перезапустите их.
3. Запустите миграции для тестовой базы данных: `docker-compose run --rm backend yii_test migrate`
4. Разверните сиды / фикстуры для тестовой базы данных: `docker-compose run --rm backend yii_test seed/articles`
5. Сгенерируйте вспомогательные файлы для тестового окружения: `docker-compose run --rm backend vendor/bin/codecept build`
6. Запустите тесты: `docker-compose run --rm backend vendor/bin/codecept run backend/tests`

### Дорожная карта

* DTO - ArticleDtoBuilder и т.д.
* readme - поиск, пагинация, сортировка
* картинки + генерация превью
* полноценные фикстуры тестов
* FIXME и TODO
* Apache -> nginx
* роутинг через `/api/v1` + pluralize

### Дополнительная информация

За основу взят пакет [yiisoft/yii2-app-advanced](https://github.com/yiisoft/yii2-app-advanced).

Для тестирования используется библиотека [Codeception](https://github.com/Codeception/Codeception).