# Sistem Informasi Data UMKM Saparua (Info UMKM)

Dokumentasi resmi Tugas Praktikum Pemrograman Web berbasis **PHP Native dengan Paradigma Pure Object-Oriented Programming (OOP) dan Arsitektur Modular**.

---

## 1. Tema & Deskripsi Website

**Tema:** *Katalog & Manajemen Data UMKM Kuliner Kawasan Lapangan Saparua, Bandung.*

Website ini dirancang sebagai sistem informasi berbasis web untuk mengelola dan mempublikasikan data Usaha Mikro, Kecil, dan Menengah (UMKM) kuliner di kawasan Saparua. Sistem menyediakan manajemen data relasional yang mencakup kategori produk, profil stan UMKM (beserta foto fisik dan status sertifikasi halal), serta daftar menu kuliner dan minuman yang dijual.

### Fitur Utama Sistem:
* **Dashboard Terpadu:** Ringkasan statistik jumlah kategori, stand UMKM, menu terdaftar, serta tabel preview stand UMKM terbaru.
* **Manajemen Data Kategori (CRUD):** Tambah, lihat, ubah, dan hapus jenis kategori kuliner (misal: Minuman, Makanan Ringan, Makanan Berat, Buah).
* **Manajemen Data UMKM (CRUD & Relasi Kategori):** Pengelolaan profil stand UMKM dengan dropdown kategori dinamis, status sertifikasi halal (`ada` / `tidak`), serta integrasi foto stand dari folder `foto_umkm/`.
* **Manajemen Data Menu (CRUD & Relasi Stand):** Pengelolaan produk kuliner dengan pemilihan stand pemilik secara dinamis, harga berformat Rupiah, dan deskripsi cita rasa.
* **Keamanan Maksimal:** 100% menggunakan **PDO Prepared Statements** untuk mencegah ancaman *SQL Injection*.
* **UI/UX Modern & Responsif:** Desain clean menggunakan CSS kustom dengan indikator status, dialog konfirmasi hapus, dan alert notifikasi interaktif.

---

## 2. Struktur & Penjelasan Database

* **Nama Database:** `data_umkm_saparua_new`
* **DBMS:** MySQL / MariaDB (Driver PDO)

### Entity Relationship Diagram (ERD) Konseptual
```text
+-------------------+             +-----------------------+             +-------------------+
|     kategori      | 1         N |         umkm          | 1         N |       menu        |
+-------------------+-------------+-----------------------+-------------+-------------------+
| PK id_kategori    |             | PK id_umkm            |             | PK id_menu        |
|    jenis          |             |    nama_stand         |             |    nama_menu      |
+-------------------+             |    nama_penjaga       |             |    harga_menu     |
                                  |    foto               |             | FK id_umkm        |
                                  |    sertifikasi_halal  |             |    deskripsi      |
                                  | FK id_kategori        |             +-------------------+
                                  +-----------------------+
```

### Rincian Tabel:

#### 1. Tabel `kategori`
Menyimpan klasifikasi jenis produk UMKM.
| Nama Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id_kategori` | `INT(11)` | **Primary Key**, Auto Increment. |
| `jenis` | `VARCHAR(255)` | Nama jenis kategori (contoh: *Minuman*, *Makanan Berat*). |

#### 2. Tabel `umkm`
Menyimpan profil fisik dan identitas stan UMKM.
| Nama Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id_umkm` | `INT(11)` | **Primary Key**, Auto Increment. |
| `nama_stand` | `VARCHAR(255)` | Nama stan atau nama merek dagang UMKM. |
| `nama_penjaga` | `VARCHAR(255)` | Nama pemilik atau penjaga stand. |
| `foto` | `VARCHAR(255)` | Nama berkas gambar pada folder `foto_umkm/`. |
| `sertifikasi_halal` | `ENUM('ada','tidak')` | Status kelayakan sertifikasi halal (default: `tidak`). |
| `id_kategori` | `INT(11)` | **Foreign Key** mereferensikan `kategori(id_kategori)`. |

#### 3. Tabel `menu`
Menyimpan daftar produk makanan/minuman yang dijual oleh stan UMKM.
| Nama Kolom | Tipe Data | Keterangan |
| :--- | :--- | :--- |
| `id_menu` | `INT(11)` | **Primary Key**, Auto Increment. |
| `nama_menu` | `VARCHAR(255)` | Nama menu makanan atau minuman. |
| `harga_menu` | `INT(11)` | Nominal harga jual produk (dalam Rupiah). |
| `id_umkm` | `INT(11)` | **Foreign Key** mereferensikan `umkm(id_umkm)`. |
| `deskripsi` | `MEDIUMTEXT` | Keterangan rasa, varian, atau komposisi produk. |

---

## 3. Arsitektur & Flow Alur Kode

Aplikasi dibangun dengan arsitektur **OOP Modular** yang memisahkan tanggung jawab kode (*Separation of Concerns*) ke dalam tiga lapisan: Konfigurasi, Model Data, dan Antarmuka (View).

### Struktur Direktori Proyek
```text
TP7/
├── class/                      # Lapisan Model & Database (OOP)
│   ├── Database.php            # Pengelola koneksi PDO Database
│   ├── Kategori.php            # Model & query builder tabel 'kategori'
│   ├── Menu.php                # Model & query builder tabel 'menu'
│   └── Umkm.php                # Model & query builder tabel 'umkm'
├── config/                     # Lapisan Konfigurasi
│   └── db.php                  # Kredensial database (host, user, pass, dbname)
├── database/                   # Skrip Basis Data
│   └── db_tubes_final.sql      # Dump skema & data awal MySQL
├── foto_umkm/                  # Direktori Berkas Gambar
│   ├── default.jpg             # Fallback gambar bawaan
│   └── *.jpg / *.JPG           # Foto stan UMKM
├── view/                       # Lapisan Antarmuka Pengguna (GUI)
│   ├── footer.php              # Template footer bersama
│   ├── header.php              # Template navigasi, session & alert handler
│   ├── kategori.php            # Halaman CRUD Kategori
│   ├── menu.php                # Halaman CRUD Menu
│   └── umkm.php                # Halaman CRUD Stand UMKM
├── index.php                   # Halaman Dashboard Utama
├── README.md                   # Dokumentasi teknis sistem
├── style.css                   # Berkas stylesheet utama
└── style.php                   # Wrapper header CSS
```

---

### Alur Kerja (Execution Flow) Sistem:

```text
[Browser / Klien]
       │
       ▼ (1. HTTP GET / POST)
[View Script (e.g. view/umkm.php)]
       │
       ├─► (2. Inisialisasi Model)
       │         │
       │         ▼
       │   [Class Model (e.g. Umkm)]
       │         │
       │         ▼ (3. Dependency Injection)
       │   [Class Database] ──► [config/db.php]
       │         │ (PDO Connection)
       │         ▼
       │   [MySQL Server] (Prepared Statements)
       │
       ├─► (4. Logika POST -> PRG Redirect)
       │   Model dieksekusi -> Set $_SESSION['flash'] -> header("Location: ...")
       │
       └─► (5. Render HTML GUI)
           Header (Nav + Alert) + Data Table / Form + Footer
```

#### A. Alur Inisialisasi Koneksi & Model (Dependency Injection)
1. Setiap model (`Kategori`, `Umkm`, `Menu`) mengimplementasikan konstruktor fleksibel dengan dukungan *Dependency Injection*.
2. Jika objek model diinstansiasi tanpa argumen (`new Umkm()`), konstruktor otomatis memanggil `new Database()` yang membaca konfigurasi dari `config/db.php`.
3. Koneksi PDO diinisialisasi dengan konfigurasi ketat:
   - `PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION` (otomatis melempar pengecualian saat query bermasalah).
   - `PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC` (hasil query selalu berbentuk array asosiatif).
   - `PDO::ATTR_EMULATE_PREPARES => false` (memaksa database engine mengeksekusi *native prepared statement*).

#### B. Alur Eksekusi CRUD & Prepared Statements
1. Seluruh operasi manipulasi basis data menggunakan mekanisme 2 tahap:
   ```php
   $stmt = $this->conn->prepare($query);
   $stmt->execute($params);
   ```
2. Parameter dinamis dari pengguna dilewatkan melalui *named parameter* (contoh: `:nama_stand`, `:id`) sehingga nilai input terisolasi total dari struktur sintaks SQL.
3. Seluruh pemanggilan dibungkus dalam blok `try-catch (PDOException $e)` yang mencatat kegagalan ke `error_log` tanpa mengekspos error fatal ke layar pengguna.

#### C. Alur Form Handling (Post/Redirect/Get Pattern)
1. **Pengiriman Form (POST):** Saat pengguna mengisi form (Tambah/Edit), data dikirim via method `POST`.
2. **Pemrosesan di Backend:** Controller mendeteksi `$_SERVER['REQUEST_METHOD'] === 'POST'`, melakukan validasi input, lalu memanggil method model terkait.
3. **Penyimpanan Pesan Kilat (Flash Message):** Hasil eksekusi disimpan sementara di dalam `$_SESSION['flash'] = ['type' => 'success'|'danger', 'message' => '...']`.
4. **Redirect HTTP:** Sistem merespons dengan header pengalihan `header('Location: file.php')` diikuti `exit;`. Hal ini mencegah masalah form terkirim ganda (*duplicate submission*) saat pengguna menekan tombol *Refresh* (F5).
5. **Penampilan Alert:** Saat halaman baru dimuat, `view/header.php` memeriksa sesi flash, merender komponen alert notifikasi secara otomatis, dan langsung membersihkan sesi tersebut (`unset($_SESSION['flash'])`).

#### D. Alur Penghubung Gambar (`foto_umkm/`)
Catatan: Pada awalnya pada lama index dan juga penambahan umkm ditampilkan semua gambar, namun ternyata menyebabkan lag yang cukup berat (karena ukuran foto terlalu besar), jadinya masih tersisa kode untuk menampilkan foto dinamis di lama index dan penambahan umkm itu, tetapi tetap ada foto ketika mengedit umkm

1. Berkas gambar disimpan di folder root `foto_umkm/`.
2. Antarmuka web menghitung jalur relatif dinamis melalui variabel `$base_path`:
   - Dari `index.php`: `./foto_umkm/{nama_file}`.
   - Dari `view/*.php`: `../foto_umkm/{nama_file}`.
3. Dilengkapi sistem proteksi ganda:
   - Pengecekan `file_exists` di level server PHP (mengecek variasi ekstensi huruf kecil `.jpg` maupun huruf besar `.JPG`).
   - Atribut HTML fallback `onerror="this.src='.../foto_umkm/default.jpg'"` di level peramban pengguna jika file foto tidak ditemukan atau rusak.

---

## Dokumentasi

[Dokumentasi Ada Di GDrive](https://drive.google.com/file/d/15qioQuTeqT2TFOu5qHKuBsLr8eLR9XaS/view?usp=sharing)


