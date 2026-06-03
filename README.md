# Skippy

Aplikasi galeri foto berbasis web yang memungkinkan pengguna untuk mengunggah, mengedit, menghapus, dan menyukai gambar dengan gaya interaksi ala Pinterest. Proyek ini dikembangkan untuk memenuhi Tugas Akhir mata kuliah Rekayasa Perangkat Lunak.

## 👥 Tim Pengembang
* Fandi Nurrezky - Project Manager
* Ryan Hidayat - Web Developer
* Muhammad Rizky Raditya - Web Developer
* M. Rizehan Noor - Frontend Designer

## 💻 Prasyarat & Dependensi
Aplikasi ini dibangun dengan PHP Native. Sistem wajib memiliki lingkungan *server* lokal berikut sebelum dijalankan:
* XAMPP / Laragon
* PHP v7.4 atau v8.x
* MySQL / MariaDB
* Web Browser modern (Chrome/Firefox/Edge)

## 🚀 Panduan Instalasi
1. **Siapkan Folder**: Ekstrak atau *clone* repositori ini. Pindahkan folder proyek ke dalam direktori `htdocs` (untuk XAMPP) atau `www` (untuk Laragon).
2. **Jalankan Server**: Buka kontrol panel XAMPP/Laragon, lalu mulai/jalankan modul **Apache** dan **MySQL**.
3. **Konfigurasi Database**:
   * Akses `http://localhost/phpmyadmin` di browser.
   * Buat *database* baru dengan nama: `galeryfoto`.
   * Lakukan *Import* file SQL yang berada di dalam folder `database/` proyek ini ke dalam *database* `galeryfoto`.
4. **Koneksi Aplikasi**: Buka file `db.php` menggunakan *text editor*. Pastikan kredensialnya terkonfigurasi seperti ini:
```php
   $hostname = 'localhost';
   $username = 'root';
   $password = ''; // Kosongkan jika password root default XAMPP
   $dbname   = 'galeryfoto';
```
5. **Jalankan Aplikasi**: Buka browser dan akses tautan lokal sesuai nama folder proyek kalian
