# CRM & Analytics Platform

Учебный проект для отработки навыков full-stack разработки на PHP + Laravel + Vue (Inertia.js) с элементами Data Engineering.

## 📋 Описание

Проект представляет собой CRM-систему с полноценной аналитической платформой. Разработан для демонстрации навыков работы с современным стеком технологий: от контейнеризации и настройки веб-сервера до построения ETL-пайплайнов и витрин данных.

**Цель проекта:** 
- Практика в построении production-ready инфраструктуры на Docker
- Работа с высоконагруженными SQL-запросами в PostgreSQL
- Проектирование Data Lake и Data Warehouse архитектуры
- Реализация полнотекстового поиска
- Интеграция фронтенда на Vue с серверным рендерингом (SSR)

## 🧩 Модули

### 🐳 Инфраструктура
- **Docker** — контейнеризация всех сервисов
- **Nginx** — reverse proxy с HTTPS (самоподписанный сертификат для локальной разработки)
- **Docker Compose** — оркестрация контейнеров

### 🖥️ Backend (Laravel)
- **Аутентификация и авторизация** — регистрация, вход, управление профилем (Laravel Fortify)
- **Управление продавцами (Sellers)** — CRUD операции, фильтрация, поиск
- **Управление транзакциями (Transactions)** — создание, просмотр, аналитика по транзакциям
- **Управление товарами и поставщиками** — единый справочник товаров, импорт прайс-листов
- **API Resources** — форматирование ответов API
- **Валидация** — Form Requests для всех входящих данных

### 🗄️ Базы данных
- **PostgreSQL** — основная реляционная БД для операционных данных
- **Redis** — кеширование, очереди, сессии

### 📊 Data Engineering & Аналитика
- **ClickHouse** — колоночная аналитическая БД для хранения событий
- **Data Lake** — сырые данные о событиях пользователей (клики, просмотры, поиски)
- **Data Warehouse** — агрегированные витрины данных (daily/hourly метрики)
- **Материализованные представления** — автоматическая агрегация в ClickHouse
- **Kafka** (опционально) — буферизация потока событий

### 🎨 Frontend
- **Vue 3** — реактивный интерфейс
- **Inertia.js** — связка Laravel + Vue без API
- **SSR** — серверный рендеринг для SEO и быстрой загрузки

### 🔍 Поиск
- **Полнотекстовый поиск** — поиск по товарам и поставщикам
- **Фильтрация и ранжирование** — сортировка по покрытию, ценам, регионам

## 🚀 Быстрый старт

### Предварительные требования
- Docker Desktop (последняя версия)
- WSL2 (для Windows)
- Git

### Установка и запуск

```bash
# 1. Клонировать репозиторий
git clone https://github.com/Kittienessless123/laravel-crm.git
cd laravel-crm

# 2. Создать SSL-сертификат для локальной разработки
mkdir -p docker/nginx/ssl
openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout docker/nginx/ssl/privkey.pem \
  -out docker/nginx/ssl/fullchain.pem \
  -subj "/CN=localhost"

# 3. Запустить все сервисы
docker-compose up -d

# 4. Выполнить миграции
docker exec -it app_laravel php artisan migrate

# 5. Сгенерировать ключ приложения
docker exec -it app_laravel php artisan key:generate
# 6. Открыть в браузере
# https://localhost
#(принять предупреждение о сертификате)
```
Остановка
```bash
docker-compose down
```
Полная пересборка
```bash
docker-compose down -v
docker-compose build --no-cache
docker-compose up -d
```
🏗️ Архитектура
```text
Пользователь → Nginx (HTTPS) → Laravel (PHP-FPM) → PostgreSQL
                                  ↓
                             Redis (кеш/сессии)
                                  ↓
                             Kafka (события)
                                  ↓
                             ClickHouse (аналитика)
                                  ↓
                             Grafana (дашборды)

Vue Frontend ← Inertia.js ← Laravel SSR
```
📁 Структура проекта
```text
laravel-crm/
├── docker/
│   ├── app/           # Dockerfile для Laravel + Node
│   ├── nginx/         # Конфигурация Nginx + SSL
│   ├── clickhouse/    # Конфигурация ClickHouse
│   ├── kafka/         # Конфигурация Kafka (опционально)
│   └── grafana/       # Конфигурация Grafana
├── server/            # Laravel приложение
│   ├── app/
│   │   ├── Models/
│   │   ├── Services/
│   │   │   └── Analytics/
│   │   └── Http/
│   ├── config/
│   ├── database/
│   ├── resources/
│   │   └── js/        # Vue компоненты
│   └── routes/
├── docker-compose.yml
└── README.md
```
🔧 Технологический стек
Категория	Технологии
Backend	PHP 8.4, Laravel 13, Inertia.js
Frontend	Vue 3, Vite
Базы данных	PostgreSQL 16, Redis 7
Аналитика	ClickHouse, Kafka (опционально), Grafana
Веб-сервер	Nginx (Alpine)
Контейнеризация	Docker, Docker Compose
SSL	Самоподписанный сертификат (разработка)
📮 Контакты
Автор: Kittienessless (Екатерина)

Email: paranina.thebell@gmail.com

GitHub: Kittienessless123

Год: 2026

📝 Лицензия
Проект создан в учебных целях. Свободное использование.




