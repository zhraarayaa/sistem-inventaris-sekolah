# 🏫 Sistem Inventaris Sekolah

Aplikasi web Sistem Inventaris Sekolah berbasis **Laravel (Breeze stack)** yang dikembangkan untuk memenuhi standar profesional, teknis, dan fungsional dari **Gamelab Indonesia**. Sistem ini dirancang untuk mendigitalisasi proses pengelolaan aset sekolah serta manajemen alur peminjaman barang oleh siswa maupun guru.

---

## 🚀 Fitur Utama & Fungsionalitas Sistem

- **Autentikasi Berbasis Peran (Role-Based Access Control):** Pemisahan hak akses keamanan secara ketat antara **Admin** dan **User (Siswa/Guru)**.
- **Manajemen Data Komprehensif:** Modul CRUD lengkap untuk pengelolaan **Barang**, **Kategori**, dan **Pengguna**, dilengkapi dengan fitur pencarian instan (*search filter*) dan *pagination*.
- **Alur Peminjaman Dinamis:** Pengguna dapat mengajukan peminjaman barang, sementara Admin memiliki panel khusus untuk melakukan *Approval* (Persetujuan) serta pencatatan pengembalian (*Return*).
- **Antarmuka Modern (UI/UX):** Dibangun menggunakan **Tailwind CSS** dengan tata letak responsif, kartu statistik *real-time*, dan transisi visual yang elegan.
- **Pencatatan Audit & Kepatuhan Git:** Struktur database terstandarisasi ERD dan penerapan prosedur *commit* berkala di GitHub.

---

## 🛠️ Prasyarat Sistem (Prerequisites)

Sebelum menjalankan aplikasi ini di komputer lokal, pastikan perangkat Anda telah terinstal perangkat lunak berikut:
- **PHP** (Minimal versi 8.2 atau sesuai standar Laravel 11)
- **Composer** (Manajer dependensi PHP)
- **Node.js & NPM** (Untuk kompilasi aset frontend Vite & Tailwind CSS)
- **MySQL / MariaDB** (Sebagai sistem basis data)
- **Git** (Untuk manajemen repositori)

---

## ⚙️ Panduan Instalasi (Installation Guide)

Ikuti langkah-langkah di bawah ini secara berurutan untuk memasang dan menjalankan proyek pada lingkungan lokal Anda:

1. **Clone repository ini ke direktori lokal Anda:**
   ```bash
   git clone https://github.com/zhraarayaa/sistem-inventaris-sekolah.git
   cd sistem-inventaris-sekolah
   ```

2. **Instal dependensi backend menggunakan Composer:**
   ```bash
   composer install
   ```

3. **Instal dependensi frontend menggunakan NPM:**
   ```bash
   npm install
   ```

4. **Salin file konfigurasi lingkungan (.env):**
   ```bash
   cp .env.example .env
   ```

5. **Generate kunci enkripsi aplikasi (Application Key):**
   ```bash
   php artisan key:generate
   ```

6. **Konfigurasi Database:**
   Buka file `.env` menggunakan teks editor (seperti VS Code), lalu sesuaikan kredensial koneksi database MySQL Anda:
   ```env
      DB_CONNECTION=mysql
      DB_HOST=127.0.0.1
      DB_PORT=3306
      DB_DATABASE=db_sistem_inventaris
      DB_USERNAME=root
      DB_PASSWORD=
   ```

7. **Jalankan Migrasi Database dan Seeder:**
   Perintah ini akan membuat tabel-tabel basis data yang diperlukan sekaligus mengisi data awal beserta kredensial akun bawaan:
   ```bash
   php artisan migrate --seed
   ```

8. **Jalankan Server Lokal (Development Servers):**
   Buka **dua jendela terminal terpisah** di dalam direktori proyek Anda:
   - **Terminal 1 (Menjalankan Backend Laravel):**
     ```bash
     php artisan serve
     ```
   - **Terminal 2 (Menjalankan Kompilasi Aset Vite & Tailwind CSS):**
     ```bash
     npm run dev
     ```

9. **Akses Aplikasi:**
   Buka peramban web (*browser*) Anda dan kunjungi tautan berikut:  
   `http://127.0.0.1:8000`

---

## 🔑 Kredensial Akun (Seeder Credentials)

Berikut adalah informasi akun default hasil *seeder* yang dapat langsung digunakan untuk masuk ke dalam sistem berdasarkan peran masing-masing:

### 1. Akun Administrator (Admin)
Digunakan untuk mengelola data master (barang, kategori, pengguna), menyetujui peminjaman, serta memantau laporan keseluruhan.
- **Nama:** Zahra Araya (Admin)
- **Email:** `admin@sekolah.com`
- **Password:** `password`

### 2. Akun Pengguna / Siswa (User)
Digunakan untuk melihat katalog barang inventaris yang tersedia dan mengajukan permohonan peminjaman.
- **Nama:** Aya (User)
- **Email:** `aya@siswa.com` 
- **Password:** `password`

---

## 📁 Struktur Basis Data Utama (ERD Compliance)
- `users`: Menyimpan informasi akun pengguna dan kolom `role` (admin/user).
- `categories`: Mengelompokkan jenis barang inventaris sekolah.
- `items`: Menyimpan rincian aset barang, stok, kode, dan kondisi fisik.
- `loans`: Mencatat riwayat dan status alur peminjaman barang (`pending`, `approved`, `returned`, dll).

---
*© 2026 Sistem Inventaris Sekolah — Gamelab Indonesia.*
