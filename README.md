# FoodLoop

**FoodLoop** adalah aplikasi web Laravel untuk menyelamatkan surplus makanan. Pemberi (restoran, hotel, katering) melaporkan makanan berlebih. Penerima (relawan, panti) mengklaim dan menyalurkannya. Makanan yang tidak layak diarahkan ke pengolah kompos/maggot, dan dampak CO₂e-nya dihitung otomatis.

---

## Daftar Isi

1. [Yang Harus Terpasang](#1-yang-harus-terpasang)
2. [Setup Pertama Kali](#2-setup-pertama-kali)
3. [Menjalankan Sehari-hari](#3-menjalankan-sehari-hari)
4. [Setelah `git pull`](#4-setelah-git-pull)
5. [Akun Demo](#5-akun-demo)
6. [Masalah yang Sering Terjadi](#6-masalah-yang-sering-terjadi)
7. [Fitur & Struktur Proyek](#7-fitur--struktur-proyek)

---

## 1. Yang Harus Terpasang

| Kebutuhan | Versi | Cek dengan |
| --- | --- | --- |
| **PHP** | **8.3 atau lebih baru** | `php -v` |
| **Composer** | 2.x | `composer -V` |
| **Git** | apa saja | `git --version` |
| Ekstensi PHP | `pdo_sqlite`, `sqlite3`, `fileinfo`, `mbstring`, `openssl` | `php -m` |

> **Tidak perlu** Node.js/npm, MySQL, atau XAMPP. Database memakai SQLite (berupa satu file), sedangkan Tailwind, Alpine.js, dan Leaflet dimuat dari CDN.
> Yang **perlu** adalah koneksi internet saat membuka web, untuk CDN dan peta OpenStreetMap.

Belum punya PHP 8.3? Jalankan perintah ini lalu **tutup dan buka lagi terminal**:

- **Windows (PowerShell):**
  ```powershell
  Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://php.new/install/windows/8.4'))
  ```
- **macOS:** `/bin/bash -c "$(curl -fsSL https://php.new/install/mac/8.4)"`
- **Linux:** `/bin/bash -c "$(curl -fsSL https://php.new/install/linux/8.4)"`

---

## 2. Setup Pertama Kali

Cukup **sekali** setelah clone. Perintah di bawah berlaku sama untuk **Git Bash, CMD, maupun PowerShell**. Jalankan satu per satu dari atas ke bawah.

```bash
# 1) Ambil kode
git clone https://github.com/dimasz77/foodloop_ingodwetrust.git
cd foodloop_ingodwetrust

# 2) Pasang dependensi PHP (folder vendor/)
composer install

# 3) Buat file .env dari contoh (aman: tidak menimpa .env yang sudah ada)
php -r "file_exists('.env') || copy('.env.example', '.env');"

# 4) Buat kunci aplikasi
php artisan key:generate

# 5) Buat file database SQLite kosong
php -r "file_exists('database/database.sqlite') || touch('database/database.sqlite');"

# 6) Buat tabel + isi data demo
php artisan migrate --seed

# 7) Aktifkan akses foto yang diunggah
php artisan storage:link

# 8) Jalankan server
php artisan serve
```

Buka **http://127.0.0.1:8000**, lalu masuk dengan [akun demo](#5-akun-demo).

Kalau semua berhasil, langkah 6 diakhiri tulisan `DONE` untuk setiap migrasi, dan langkah 8 menampilkan `Server running on [http://127.0.0.1:8000]`.

---

## 3. Menjalankan Sehari-hari

Setelah setup pertama, untuk membuka web cukup jalankan:

```bash
php artisan serve
```

Hentikan server dengan **Ctrl + C**.

---

## 4. Setelah `git pull`

Setiap kali mengambil perubahan terbaru dari teman, jalankan:

```bash
git pull
composer install          # jika ada paket baru
php artisan migrate       # jika ada tabel/kolom baru (data Anda tetap aman)
php artisan optimize:clear
```

Ingin **mengembalikan database ke data demo awal**? Jalankan perintah berikut. ⚠️ Perintah ini **menghapus semua data** di database lokal Anda:

```bash
php artisan migrate:fresh --seed
```

> Jalankan perintah reset ini juga kalau countdown makanan di peta sudah tertulis "Habis". Data demo dibuat dengan batas waktu beberapa jam dari saat seeding.

---

## 5. Akun Demo

Semua akun memakai kata sandi **`password`**. Di halaman Masuk juga ada tombol isi cepat.

| Peran | Email | Yang bisa dilakukan |
| --- | --- | --- |
| Pemberi Makanan | `smiley@foodloop.test` | Lapor surplus, pantau status, lihat lencana |
| Pemberi Makanan | `italy@foodloop.test`, `sunhotel@foodloop.test`, `bakery@foodloop.test`, `katering@foodloop.test` | sama seperti di atas |
| Penerima Makanan | `relawan@foodloop.test` | Klaim makanan di peta, hubungi pemberi, unggah bukti |
| Penerima Makanan | `panti@foodloop.test` | sama seperti di atas |

**Coba alur lengkap:** masuk sebagai pemberi → **Lapor** → keluar → masuk sebagai penerima → **Peta** → klaim → **Klaim Saya** → unggah foto bukti → masuk lagi sebagai pemberi dan lihat pesan terima kasih.

---

## 6. Masalah yang Sering Terjadi

| Pesan / gejala | Penyebab | Solusi |
| --- | --- | --- |
| `Your PHP version (8.2.x) does not satisfy...` | PHP terlalu lama | Pasang PHP 8.3+ (lihat [bagian 1](#1-yang-harus-terpasang)) |
| `could not find driver` | Ekstensi SQLite belum aktif | Buka `php.ini` (lokasinya terlihat dari `php --ini`), hapus tanda `;` di depan `extension=pdo_sqlite` dan `extension=sqlite3` |
| `Database file at path [...] does not exist` | Langkah 5 terlewat | Jalankan ulang langkah 5, lalu langkah 6 |
| `No application encryption key has been specified` | Langkah 4 terlewat | `php artisan key:generate` |
| `Failed to listen on 127.0.0.1:8000` | Port sedang dipakai | `php artisan serve --port=8001` lalu buka `http://127.0.0.1:8001` |
| Foto yang diunggah tidak muncul | Link storage belum dibuat | `php artisan storage:link` |
| Tampilan berantakan / tanpa gaya | Tidak ada internet (CDN gagal dimuat) | Sambungkan internet lalu refresh |
| Perubahan kode tidak terlihat | Cache lama | `php artisan optimize:clear` lalu refresh browser (Ctrl + F5) |
| `vendor/autoload.php` tidak ditemukan | `composer install` belum dijalankan | Jalankan `composer install` |

Untuk memastikan semuanya berfungsi, jalankan tes otomatis:

```bash
php artisan test
```

---

## 7. Fitur & Struktur Proyek

### Fitur

- **Dua peran:** Pemberi Makanan & Penerima Makanan, dengan menu yang berbeda.
- **Lapor surplus** dengan checklist kelayakan yang menentukan jalur **Human-First** (ke manusia) atau **Earth-First** (ke kompos/maggot).
- **Peta interaktif** (Leaflet + OpenStreetMap) dengan filter kategori, radius, dan darurat, diperbarui otomatis tiap 30 detik.
- **Klaim, hubungi via WhatsApp, batalkan klaim, dan unggah bukti** serah terima beserta ucapan terima kasih.
- **Auto re-route:** makanan layak yang tidak diklaim sampai kedaluwarsa otomatis dialihkan ke jalur organik.
- **Halaman Dampak:** grafik bulanan, komposisi kategori, padanan CO₂e, dan papan peringkat.
- **Riwayat & Lencana:** level kontributor dan 6 lencana pencapaian.

### Struktur folder

```text
app/
├── Http/Controllers/     PageController (beranda, dampak), ReportController (lapor, peta, status), AuthController
├── Http/Middleware/      EnsureRole (membatasi halaman per peran)
├── Models/               User, Partner, Report
└── Services/             ImpactCalculator (rumus CO₂e, level & lencana)
database/
├── migrations/           skema tabel
└── seeders/              akun & data demo
resources/views/
├── layout.blade.php      header, footer, notifikasi
├── components/           page-hero, help, contour (komponen dipakai ulang)
└── *.blade.php           home, map, report, status, history, impact, auth/
routes/web.php            semua route
tests/Feature/            tes alur utama
```

### Foto latar (opsional)

Taruh foto `.jpg` di `public/images/` agar tampil sebagai latar header. Tanpa foto, header memakai latar hijau bermotif kontur.

| File | Dipakai di |
| --- | --- |
| `hero.jpg`, `fitur.jpg` | Beranda |
| `peta.jpg` | Peta |
| `lapor.jpg` | Lapor |
| `riwayat.jpg` | Riwayat & Lencana |
| `dampak.jpg` | Dampak |

Gunakan foto horizontal minimal 1920×1080 dan usahakan ukurannya di bawah 500 KB.

### Pakai MySQL (opsional)

Ubah bagian ini di `.env`, buat database `foodloop` di MySQL, lalu jalankan `php artisan migrate --seed`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=foodloop
DB_USERNAME=root
DB_PASSWORD=
```
