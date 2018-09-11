# TestHub

## Описание
Создание и прохождение тестов.
![Alt text](https://github.com/asfg90resklefegopreseglfdg/TestHub/blob/master/%D0%BB%D0%B8%D1%81%D1%82%D0%B8%D0%BD%D0%B3%20%D1%82%D0%B5%D1%81%D1%82%D0%BE%D0%B2.png?raw=true)
![Alt text](https://github.com/asfg90resklefegopreseglfdg/TestHub/blob/master/%D0%BF%D1%80%D0%BE%D1%85%D0%BE%D0%B6%D0%B4%D0%B5%D0%BD%D0%B8%D0%B5%20%D1%82%D0%B5%D1%81%D1%82%D0%B0.png)

## Установка
* Клонировать проект
* Запустите composer install
* Переименуйте .env.example в .env
* Откройте файл .env и измените настройки под свои
* Запустить php artisan key:generate
* Запустить php artisan migrate
* Запустить php artisan migrate tetshub_testing, для правильной работы всех тестов
* Готово

## Тесты
* Для корректной работы всех тестов, обязательно нужна отдельная таблица testhub_testing
* Для изменения тестового подключения нужно перейти в config/database.php -> mysql_testing
* После создания таблицы, введите phpunit
