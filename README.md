# CampVentory - Aplikasi Manajemen Inventaris Peralatan Camping

**Link Website Live:** [https://campventory-inlife.vercel.app/](https://campventory-inlife.vercel.app/)

Aplikasi Manajemen Inventaris Peralatan Camping (**CampVentory**) adalah sistem berbasis web yang digunakan untuk mengelola inventarisasi alat-alat lapangan/camping, manajemen kategori barang, serta pencatatan transaksi peminjaman dan pengembalian barang secara real-time. Aplikasi ini dibangun menggunakan **Laravel 12** dan **MySQL**, serta telah terkonfigurasi menggunakan **Docker** untuk kemudahan deployment.

---

## Fitur Utama
1. **Dashboard & Statistik:** Menampilkan grafik peminjaman per bulan, status stok barang (peringatan stok tipis), total barang, dan transaksi aktif.
2. **Manajemen Kategori:** CRUD (Create, Read, Update, Delete) kategori peralatan lapangan.
3. **Manajemen Barang / Produk:** CRUD data barang lengkap dengan kode unik, lokasi penyimpanan, status kondisi barang, dan pencatatan sisa stok.
4. **Transaksi Peminjaman:** Pencatatan nama peminjam, tanggal peminjaman, estimasi tanggal pengembalian, dan barang yang dipinjam beserta kuantitasnya.
5. **Transaksi Pengembalian:** Memproses pengembalian barang yang memulihkan jumlah stok barang secara otomatis.
6. **Ekspor PDF & Laporan:** Fitur cetak bukti transaksi peminjaman dalam format PDF.
7. **REST API & Autentikasi Sanctum:** Tersedia API terproteksi Bearer Token untuk integrasi mobile/pihak ketiga.

---

## Akun Login Testing
Berikut adalah akun default hasil database seeding untuk kebutuhan pengujian:

| Role / Akses | Email | Password | Hak Akses |
|---|---|---|---|
| **Admin** | `admin@telkomsel.com` | `password` | Akses penuh seluruh sistem & manajemen user |
| **Staff** | `staff@telkomsel.com` | `password` | Mengelola data master barang & transaksi peminjaman |
| **Manager** | `manager@telkomsel.com` | `password` | Hanya akses dashboard, laporan, dan grafik statistik |

---

## Cara Instalasi & Menjalankan Project

### Persyaratan Sistem (Prerequisites)
Sebelum memulai, pastikan perangkat Anda telah terinstall:
- **Docker Desktop** (Sangat Direkomendasikan)
- *Atau* secara manual: **PHP >= 8.2**, **Composer**, **Node.js & NPM**, **MySQL**.

---

### Metode 1: Menjalankan Menggunakan Docker (Rekomendasi)

Dengan Docker, Anda tidak perlu menginstall PHP, Composer, Node.js, atau MySQL secara lokal di komputer Anda. Semuanya dijalankan di dalam container yang terisolasi.

1. **Clone Repository / Unduh Source Code:**
   Buka terminal di folder project Anda.

2. **Salin File Environment:**
   ```bash
   cp .env.example .env
   ```
   *(Secara default, konfigurasi koneksi database di file `.env` sudah diarahkan ke container database Docker).*

3. **Jalankan Docker Compose:**
   Jalankan perintah berikut untuk mengunduh image dan menjalankan container di background:
   ```bash
   docker compose up -d
   ```

4. **Install Dependencies & Lakukan Migrasi Database:**
   Eksekusi perintah di dalam container Laravel (`campventory-app`) untuk menginstall package, melakukan migrasi database, dan mengisi data awal (seeding):
   ```bash
   # Masuk ke container app dan jalankan script setup otomatis
   docker compose exec app composer run setup
   ```
   Atau jika ingin menjalankan perintahnya secara terpisah:
   ```bash
   # Install Laravel dependencies
   docker compose exec app composer install
   
   # Generate Application Key
   docker compose exec app php artisan key:generate
   
   # Migrasi Database + Seeding data testing
   docker compose exec app php artisan migrate --seed
   
   # Install & build aset frontend (Vite & Tailwind)
   docker compose exec app npm install
   docker compose exec app npm run build
   ```

5. **Akses Aplikasi:**
   Buka browser Anda dan akses link berikut:
   - **Aplikasi Web:** [http://localhost:8000](http://localhost:8000)
   - **Database (MySQL):** Tersedia pada `127.0.0.1:3306` (gunakan client seperti DBeaver/Navicat dengan Username: `campventory_user` dan Password: `password`).

---

### Metode 2: Menjalankan Secara Manual (Tanpa Docker)

Jika Anda ingin menjalankan aplikasi secara langsung di sistem operasi lokal Anda:

1. **Salin File Environment & Sesuaikan Database:**
   ```bash
   cp .env.example .env
   ```
   Buka file `.env` dan ubah bagian koneksi database agar mengarah ke database MySQL lokal Anda:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nama_database_lokal_anda
   DB_USERNAME=username_mysql_anda
   DB_PASSWORD=password_mysql_anda
   ```

2. **Install PHP Dependencies:**
   ```bash
   composer install
   ```

3. **Generate Application Key:**
   ```bash
   php artisan key:generate
   ```

4. **Jalankan Migrasi & Seed Database:**
   Pastikan MySQL lokal Anda sudah menyala dan database yang ditentukan di `.env` sudah dibuat, lalu jalankan:
   ```bash
   php artisan migrate --seed
   ```

5. **Install & Jalankan Node Dependencies (Vite):**
   ```bash
   npm install
   npm run dev
   ```

6. **Jalankan Local Server Laravel:**
   Di terminal baru, jalankan server PHP:
   ```bash
   php artisan serve
   ```
   Aplikasi dapat diakses di: [http://127.0.0.1:8000](http://127.0.0.1:8000).

---

## File Database (.sql) & Dokumentasi API

- **File Database SQL Backup:** Terlampir di root folder project dengan nama [`campventory.sql`](file:///d:/Kuliah/Kerja%20Praktik/Intership-InlifeTelkomsel/campventory.sql).
- **Dokumentasi Endpoint REST API:** Silakan lihat dokumen lengkapnya di [`API_DOCUMENTATION.md`](file:///d:/Kuliah/Kerja%20Praktik/Intership-InlifeTelkomsel/API_DOCUMENTATION.md).
