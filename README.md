# 🌿 FoodLoop

**FoodLoop** adalah aplikasi web berbasis Laravel untuk manajemen penyelamatan dan distribusi surplus makanan (*food rescue*), pemantauan titik mitra, pelaporan makanan berlebih, serta kalkulasi dampak lingkungan (pengurangan emisi $\text{CO}_2\text{e}$).

---

## ✨ Fitur Utama

- **Dashboard Status & Monitoring:** Ringkasan laporan penyelamatan makanan dan status distribusi secara real-time.
- **Pelaporan Surplus Makanan:** Form pencatatan laporan makanan berlebih lengkap dengan dukungan unggah foto.
- **Peta Interaktif Mitra & Titik Distribusi:** Visualisasi lokasi mitra menggunakan Leaflet & OpenStreetMap.
- **Kalkulator Dampak Lingkungan (`ImpactCalculator`):** Estimasi otomatis pengurangan emisi karbon ($\text{CO}_2\text{e}$) berdasarkan total kilogram makanan yang terselamatkan.
- **Riwayat Distribusi:** Arsip seluruh transaksi penyelamatan dan penyaluran makanan.

---

## 🛠️ Tech Stack

- **Backend:** PHP 8.2+ & Laravel 11/12
- **Database:** SQLite (default, tanpa perlu konfigurasi server DB tambahan) atau MySQL/MariaDB
- **Frontend:** Laravel Blade, Tailwind CSS, Alpine.js, dan Leaflet.js (via CDN — tidak memerlukan proses *build* Node.js/NPM untuk menjalankan demo)

---

## 🚀 Instalasi Cepat (Sekali Jalan)

Pastikan **PHP** (`>= 8.2`), **Composer**, dan **Git** sudah terpasang di sistem Anda.

### 1. Clone Repositori & Masuk ke Folder Proyek

```bash
git clone [https://github.com/dimasz77/foodloop_ingodwetrust.git](https://github.com/dimasz77/foodloop_ingodwetrust.git)
cd foodloop_ingodwetrust

```

### 2. Setup Otomatis (Linux / macOS / Git Bash)

Jalankan blok perintah berikut sekaligus untuk menginstal dependensi, menyiapkan `.env`, membuat database SQLite beserta data demo, dan menjalankan server:

```bash
composer install && \
cp -n .env.example .env && \
php artisan key:generate && \
php artisan migrate:fresh --seed && \
php artisan storage:link && \
php artisan serve

```

### 2b. Setup Otomatis (Windows Command Prompt / CMD)

```cmd
composer install && copy .env.example .env && php artisan key:generate && php artisan migrate:fresh --seed && php artisan storage:link && php artisan serve

```

### 3. Buka Aplikasi

Akses aplikasi melalui browser di:
👉 **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## ⚙️ Konfigurasi Database Opsional (MySQL)

Secara default, aplikasi menggunakan **SQLite** (`DB_CONNECTION=sqlite` pada `.env`) sehingga langsung berjalan tanpa setup tambahan. Jika ingin menggunakan **MySQL**, ubah konfigurasi berikut di file `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=foodloop
DB_USERNAME=root
DB_PASSWORD=

```

Lalu jalankan ulang migrasi:

```bash
php artisan migrate:fresh --seed

```

---

## 🔄 Perintah Berguna

| Perintah | Fungsi |
| --- | --- |
| `php artisan serve` | Menjalankan server lokal di `http://127.0.0.1:8000` |
| `php artisan migrate:fresh --seed` | Mereset ulang database ke data demo awal |
| `php artisan optimize:clear` | Membersihkan cache konfigurasi, route, dan view |

---

## 📁 Struktur Utama Aplikasi

```text
├── app/
│   ├── Http/Controllers/    # PageController & ReportController
│   ├── Models/              # Partner & Report
│   └── Services/            # ImpactCalculator (perhitungan CO2e)
├── database/
│   ├── migrations/          # Skema tabel partners & reports
│   └── seeders/             # Data awal mitra & laporan demo
├── resources/views/         # Tampilan Blade (home, status, map, impact, report, history)
└── routes/web.php           # Definisi seluruh route aplikasi

```

---

## 📝 Catatan

- Tile peta menggunakan **OpenStreetMap** sehingga memerlukan koneksi internet saat membuka halaman **Peta**.
- Jika menggunakan Linux/macOS dan file `database/database.sqlite` belum otomatis dibuat, jalankan `touch database/database.sqlite` sebelum `php artisan migrate:fresh --seed`.
