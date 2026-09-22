# ECOCASH

ECOCASH is a Laravel 12 + Livewire application for turning sorted waste into auditable ECOPOINT value. The project includes a Laravel web app, a MySQL database, and a FastAPI AI service for waste classification.

## Quick start with Docker

Requirements:

- Docker Desktop / Docker Engine
- Docker Compose

### 1) Start the full stack

```bash
docker compose up --build -d
```

This starts:

- Laravel app: http://localhost:8000
- AI service: http://localhost:8001/health
- MySQL: localhost:3307

### 2) Seed the database

```bash
docker compose exec app php artisan migrate --seed
```

If the app needs a fresh key:

```bash
docker compose exec app php artisan key:generate --force
```

### 3) Stop the stack

```bash
docker compose down
```

To delete the persistent database volume:

```bash
docker compose down -v
```

## Default login

The seeded demo accounts are created when you run the migration seeder.

Use one of these accounts:

- Admin: `admin@ecocash.test` / `password`
- User: `user@ecocash.test` / `password`
- Partner: `partner@ecocash.test` / `password`

## Local development (without Docker)

Requirements: PHP 8.3+, Composer, Node/npm, MySQL 8+, Python 3.11+

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

## AI service

```bash
cd ai-service
python -m venv .venv
.venv\Scripts\activate
pip install -r requirements.txt
python scripts/download_model.py
uvicorn app.main:app --host 0.0.0.0 --port 8001
```

The AI service reads the YOLO model under `ai-service/models/` and exposes the classification API on port `8001`.

## Notes

- The Docker configuration uses `.env.docker` for the app container environment.
- MySQL is exposed on port `3307` on the host to avoid conflicts with local MySQL services.
- The AI model file is not committed to Git; ensure it is available in the `ai-service/models/` directory before running the inference service.

## Scope note

This repository is an MVP. The next implementation stages include deeper admin flows, partner approval, analytics, and additional integration coverage before treating it as a complete production system.
