# Promodata

Тестовое задание. Laravel-приложение для формирования CSV-отчётов по ценам товаров.

PHP 8.3+ · Laravel 12 · PostgreSQL 17

## Генерация отчёта

```bash
php artisan report:generate {category_id}
```

Формирует CSV с минимальной и максимальной ценой по каждому товару категории за последние 7 дней.
Файл сохраняется в `storage/app/reports/`. Страница со списком запусков — `GET /`.

## Структура

```
app/Console/Commands/GenerateReport.php
app/Http/Controllers/ReportProcessController.php
app/Models/
app/Enums/ProcessStatus.php
app/Repositories/
app/Services/ReportService.php
database/migrations/
database/seeders/DatabaseSeeder.php
resources/views/reports/index.blade.php
routes/web.php
```

В ТЗ указан формат имени файла `report_{manufacturer_id}_{category_id}_...csv`, однако команда принимает один параметр `category_id` и формирует единый отчёт по всем производителям категории. Использование `manufacturer_id` в имени файла в данном контексте невозможно — в качестве идентификатора используется `category_id`.