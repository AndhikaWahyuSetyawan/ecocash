# ECOCASH

ECOCASH adalah platform digital berbasis web yang mengubah sampah yang sudah dipilah menjadi nilai ekonomi yang terukur melalui sistem ECOPOINT. Aplikasi ini menggabungkan Laravel, Livewire, MySQL, dan AI Vision untuk mengotomatisasi proses sortir sampah, penghitungan nilai, serta pendokumentasian transaksi secara transparan.

## Problem Statement

Indonesia masih menghadapi masalah besar dalam pengelolaan sampah rumah tangga dan skala komunitas:
- banyak sampah tercampur dan tidak tertata
- masyarakat kesulitan mengetahui nilai ekonomis dari sampah yang mereka kumpulkan
- proses pemantauan dan validasi transaksi masih belum terdigitalisasi
- bank sampah dan mitra belum memiliki sistem yang memudahkan verifikasi dan pemberian insentif

ECOCASH hadir sebagai solusi untuk menghubungkan masyarakat, bank sampah, dan mitra dalam ekosistem daur ulang yang lebih efisien dan terukur.

## Solusi

ECOCASH menyediakan:
- sistem dashboard untuk pengguna, admin, dan mitra
- pencatatan transaksi sampah berbasis saldo ECOPOINT
- proses pemindaian sampah menggunakan AI untuk mengklasifikasikan jenis sampah
- validasi kategori sampah dan nilai ekonomi per kilogram
- sistem reward dan histori transaksi yang transparan
- ekosistem yang mendorong perilaku memilah sampah sejak dari rumah

## Fitur Utama

- Login dan role-based access untuk admin, user, dan partner
- AI classification untuk sampah berbasis model YOLO
- Pencatatan deposit sampah dan saldo ECOPOINT
- Kelola bank sampah dan mitra
- Riwayat transaksi dan reward
- Dashboard ekologi dan dampak lingkungan

## Teknologi yang Digunakan

- Laravel 12
- Livewire 3
- MySQL
- FastAPI
- Ultralytics / YOLO
- Docker Compose

## Quick Start dengan Docker

### Persyaratan

- Docker Desktop atau Docker Engine
- Docker Compose

### 1) Jalankan seluruh stack

```bash
docker compose up --build -d
```

Setelah dijalankan, aplikasi akan tersedia pada:
- Aplikasi web: http://localhost:8000
- Health check AI: http://localhost:8001/health
- Database MySQL: localhost:3307

### 2) Seed database

```bash
docker compose exec app php artisan migrate --seed
```

Jika aplikasi membutuhkan APP_KEY baru:

```bash
docker compose exec app php artisan key:generate --force
```

### 3) Matikan stack

```bash
docker compose down
```

Jika ingin menghapus data database yang tersimpan:

```bash
docker compose down -v
```

## Login Default

Akun demo yang tersedia setelah proses seeding:

- Admin: `admin@ecocash.test` / `password`
- User: `user@ecocash.test` / `password`
- Partner: `partner@ecocash.test` / `password`

## Pengembangan Lokal (Tanpa Docker)

### Persyaratan

- PHP 8.3+
- Composer
- Node/npm
- MySQL 8+
- Python 3.11+

### Langkah

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm install
npm run build
php artisan serve
```

## AI Service

```bash
cd ai-service
python -m venv .venv
.venv\Scripts\activate
pip install -r requirements.txt
python scripts/download_model.py
uvicorn app.main:app --host 0.0.0.0 --port 8001
```

Model AI untuk klasifikasi sampah berada di folder `ai-service/models/` dan API service akan berjalan pada port `8001`.

## Struktur Project

- `app/` : logika aplikasi Laravel dan Livewire
- `database/` : migration dan seeder
- `routes/` : routing aplikasi
- `resources/` : view dan frontend assets
- `ai-service/` : backend AI untuk klasifikasi sampah
- `docker-compose.yml` : konfigurasi stack Docker

## Catatan Penting

- Konfigurasi Docker aplikasi menggunakan `.env.docker`
- Port MySQL di-host dipindahkan ke `3307` agar tidak bentrok dengan MySQL lokal yang sudah berjalan
- File model AI tidak disimpan di Git, pastikan model tersedia di `ai-service/models/`

## Roadmap

Tahap berikutnya untuk pengembangan lebih lanjut:
- peningkatan dashboard analitik real-time
- validasi mitra dan admin workflow lebih lengkap
- optimasi model AI untuk akurasi klasifikasi sampah
- integrasi transaksi berbasis blockchain / ledger terverifikasi
- fitur edukasi lingkungan dan gamifikasi reward

## Kesimpulan

ECOCASH adalah solusi digital yang menggabungkan teknologi lingkungan, ekonomi circular, dan kecerdasan buatan untuk mendorong perubahan perilaku masyarakat dalam memilah dan mengelola sampah secara lebih cerdas, efisien, dan bermanfaat. Project ini dirancang untuk menjadi solusi yang menarik untuk kompetisi hackathon karena menggabungkan nilai sosial, dampak lingkungan, dan inovasi teknologi yang nyata.
