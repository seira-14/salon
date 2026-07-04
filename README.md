# Aplikasi Inventori Produk Rambut di Salon

## Deskripsi
Aplikasi Inventori Produk Rambut di Salon merupakan aplikasi berbasis web yang dirancang untuk membantu admin salon dalam mengelola inventori produk kecantikan secara efisien. Sistem ini menyediakan pengelolaan data barang, kategori, supplier, dan pengguna melalui fitur CRUD (Create, Read, Update, Delete). Selain itu, aplikasi dilengkapi dengan dashboard yang menampilkan ringkasan data inventori, daftar produk terbaru, serta peringatan stok rendah sehingga memudahkan pemilik salon dalam mengelola persediaan produk.

Aplikasi dikembangkan menggunakan PHP dan MySQL sebagai sistem basis data, serta memanfaatkan Bootstrap 5 untuk menghasilkan tampilan yang modern, responsif, dan mudah digunakan.

## Bahasa Pemrograman
- PHP
- CSS
- JavaScript

## Database
- MySQL
  
## Framework
- Bootstrap 5

## Fitur
- Login Admin.
- Dashboard inventori yang menampilkan jumlah barang, kategori, dan supplier.
- Notifikasi barang dengan stok rendah (stok < 10).
- Daftar produk terbaru yang ditambahkan.
- Pagination pada tabel data barang.
- CRUD Data Barang.
- CRUD Data Kategori.
- CRUD Data Supplier.
- CRUD Data User.
- Upload gambar produk.
- Validasi input pada setiap form.
- Konfirmasi sebelum menghapus data.
- Sidebar navigasi yang dapat ditampilkan atau disembunyikan.
- Logout sistem.
- Antarmuka responsif menggunakan Bootstrap 5.

## Struktur Folder

```text
website inventori salon/
├── assets/
│   └── css/
│       └── custom.css
├── js/
│   └── script.js
├── uploads/
│   ├── 1763001098_...
│   ├── 1763001165_...
│   ├── 1763001229_...
│   ├── ...
│   └── OIP.webp
├── barang.php
├── barang_tambah.php
├── barang_edit.php
├── kategori.php
├── kategori_tambah.php
├── kategori_edit.php
├── supplier.php
├── supplier_tambah.php
├── supplier_edit.php
├── user.php
├── user_tambah.php
├── login.php
├── logout.php
├── index.php
├── koneksi.php
├── functions.php
├── is_admin.php
└── inventori_salon.sql
```

## Cara Menjalankan
1. Simpan folder project ke dalam direktori `C:\xampp\htdocs`.
2. Ekstrak file ZIP apabila project masih dalam bentuk arsip.
3. Jalankan Apache dan MySQL melalui XAMPP Control Panel.
4. Buat database dengan nama `inventori_salon`.
5. Import file `inventori_salon.sql` ke dalam database tersebut.
6. Buka browser, kemudian akses:

```text
http://localhost/website%20inventori%20salon/login.php
```

## Akun Login

**Username:** `admin`

**Password:** `admin456`

## Tujuan Project
Project ini dibuat untuk membantu admin salon dalam mengelola inventori produk kecantikan secara lebih efektif, mulai dari pengelolaan data barang, kategori, supplier, hingga pengguna. Aplikasi juga membantu memantau stok produk melalui dashboard dan peringatan stok rendah sehingga proses pengelolaan inventori menjadi lebih terstruktur, efisien, dan mudah digunakan.

## Author

**Alifah Ghina Salsabila**
