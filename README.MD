# Приложение Symfony CRUD

## Начало работы

1. Склонируйте данный репозиторий:

   ```shell
   git clone <repository_url>
   
2. Создайте файл .env по примеру env.example в корневой директории и настройте его, указав необходимые переменные окружения для Docker. Например:
3. Создайте файл www/.env по примеру www/.env.example в директории www и настройте его, указав необходимые переменные окружения приложения.
4. Запустите Docker контейнеры:

   ```shell
   docker-compose up -d
   
6. Выполните обновление зависимостей Composer(Внутри контейнера с php):  
   ```shell
   composer update

5. Создайте миграции(Внутри контейнера с php):
   ```shell
   php bin/console make:migration

5. Выполните миграции для создания необходимых таблиц в базе данных(Внутри контейнера с php):
   ```shell
   зphp bin/console doctrine:migrations:migrate