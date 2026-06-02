# Pertemuan 5 — Backend (Laravel REST API)

Backend pendukung modul Praktikum **Pertemuan 5** (mobile programming). Mahasiswa
mengonsumsi endpoint di sini dari aplikasi Flutter sebagai pengganti SQLite (P4).

## Endpoint

| Method | URL                  | Body                                   | Sukses             | Auth |
|--------|----------------------|----------------------------------------|--------------------|------|
| GET    | `/api/catatan`       | —                                      | 200                | ✔    |
| GET    | `/api/catatan/{id}`  | —                                      | 200 / 404          | ✔    |
| POST   | `/api/catatan`       | `{judul,isi,kategori,dibuat_pada?}`    | 201 / 422          | ✔    |
| PUT    | `/api/catatan/{id}`  | `{judul,isi,kategori}`                 | 200 / 404 / 422    | ✔    |
| DELETE | `/api/catatan/{id}`  | —                                      | 200 / 404          | ✔    |

Semua endpoint butuh header `X-API-Key: <API_KEY env>`.

Format response standar: `{ "success": bool, "data": ..., "message"?: ... }`.

### Contoh response `Catatan`

```json
{
  "id": 7,
  "judul": "Tugas Mobile",
  "isi": "Selesaikan modul P5",
  "kategori": "Tugas",
  "dibuat_pada": "2026-06-02T10:30:00.000000Z"
}
```

## Setup Lokal

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
php artisan serve   # http://127.0.0.1:8000
```

Cek cepat:

```bash
curl -H "X-API-Key: dev-secret-123" http://127.0.0.1:8000/api/catatan
```

## Test

```bash
php artisan test
```

Suite `CatatanApiTest` mencakup 11 skenario (CRUD + auth + 404 + validasi).

## Deploy ke Railway

1. Push repo ke GitHub.
2. Railway → **New Project → Deploy from GitHub** → pilih repo.
3. Tambah **Postgres** plugin → Railway inject `DATABASE_URL`.
4. Set env vars:
   - `APP_KEY` (`php artisan key:generate --show` lokal lalu paste)
   - `API_KEY` (string rahasia, **bukan** `dev-secret-123`)
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `DB_CONNECTION=pgsql`
5. Setelah deploy: buka **Railway shell** → `php artisan migrate --force` → opsional `php artisan db:seed --force`.
6. Salin domain publik (mis. `https://xxxx.up.railway.app`) ke modul mahasiswa.

## Catatan Keamanan

- `API_KEY` di `.env.example` (`dev-secret-123`) **hanya untuk development**. Wajib diganti di production.
- File `database/database.sqlite` di-ignore git secara default.
- Bila publik, batasi rate-limit di middleware Laravel (`throttle`) sebelum diserahkan ke mahasiswa.
