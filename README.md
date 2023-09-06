### Application

Backend

| Т          |      В |
|------------|-------:|
| Laravel    | v10.10 |
| PHP        |  8.2.0 |
| PostgreSQL |  13.12 |
| Filament   | 3.0.39 |



# УСТАНОВКА
#### 1. Клонируем проект
```code
git clone https://github.com/NursultanIITU/business_partner.git
```

#### 2. Копируем env.example и создаем .env и создаем БД и конфигурируем 
```code
cp .env.example .env
```
#### 3. Заходим в проект и устанавливаем зависимости
```code
composer install && php artisan key:generate && php artisan jwt:secret
```
#### 4. Запускаем миграции и seeders
```code
php artisan migrate && php artisan db:seed
```
#### 5. Создаем админа
```code
php artisan make:filament-user
```
#### 5. Запускаем приложение
```code
php artisan serve
```
#### 6. Готово!
```code
✅
```
#### 7. Запустить тесты(Optional)
```code
php artisan test
```




## Cсылка на админ панель:
http://127.0.0.1:8000/panel

## Информация по API :
http://127.0.0.1:8000/docs/api



