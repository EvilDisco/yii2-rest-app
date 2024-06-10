Тестовое приложение REST API для Yii 2
===============================

### Первоначальное развёртывание

Предполагается, что базовое программное окружение настроено (docker, docker-compose).

1. Склонируйте проект с Гитхаба и перейдите в папку проекта.
2. Выполните в терминале команду `docker-compose run --rm backend composer install`
3. Выполните команду `docker-compose run --rm backend php /app/init --env=Development --overwrite=All --delete=All`
4. Запустите контейнеры: `docker-compose up -d`
5. Запустите миграции для базы данных: `docker-compose run --rm backend yii migrate`
6. Разверните сиды / фикстуры: `docker-compose run --rm backend yii seed/articles`
7. Проверьте работу тестовой страницы в браузере: http://127.0.0.1:20080

Команду `yii seed/articles` можно выполнять повторно. В этом случае данные в базе будут перезаписаны новыми.

### Работа с API

Доступны следующие эндпоинты API (общий префикс - http://127.0.0.1:21080/index.php):
* GET `/article-author` - коллекция авторов статей
* GET `/article-author/{id}` - автор статей
* GET `/article-category` - коллекция категорий статей
* GET `/article-category/{id}` - категория статьи
* GET `/articles` - коллекция статей
* GET `/articles/{id}` - статья

### Тесты

TODO

### Дорожная карта

* роутинг
* readme - поиск, пагинация, сортировка
* DTO - ArticleDtoBuilder и т.д.
* картинки
* тесты респонсов
* картинки + генерация превью
* FIXME и TODO
* Apache -> nginx
* роутинг через `/api/v1` + pluralize

### Дополнительная информация

За основу взят пакет [yiisoft/yii2-app-advanced](https://github.com/yiisoft/yii2-app-advanced).

Для тестирования используется библиотека [Codeception](https://github.com/Codeception/Codeception).