<?php
// ============================================================================
// Class Menu
// Model untuk mengelola data entitas Menu (tabel: 'menu') pada database.
// Berelasi Many-to-One dengan tabel 'umkm' (satu UMKM dapat memiliki banyak menu).
// Menyediakan operasi CRUD (Create, Read, Update, Delete) untuk menu makanan dan minuman.
// ============================================================================

// Memuat class Database untuk keperluan koneksi
require_once __DIR__ . '/Database.php';

// Definisi Class Menu
class Menu {
    // Properti private untuk menyimpan instance koneksi PDO
    private PDO $conn;

    // Properti private untuk menyimpan nama tabel yang dikelola
    private string $table = 'menu';

    // Constructor Menu
    // Menginisialisasi koneksi database dengan menerapkan Dependency Injection
    // Menerima parameter berupa instance PDO, instance Database, atau bernilai null (default membuat Database baru)
    public function __construct(PDO|Database|null $db = null) {
        // Jika parameter yang dikirim adalah instance PDO langsung
        if ($db instanceof PDO) {
            $this->conn = $db;
        // Jika parameter yang dikirim adalah instance class Database, ambil koneksi PDO-nya
        } elseif ($db instanceof Database) {
            $this->conn = $db->getConnection();
        // Jika tidak ada argumen yang dikirim (null), buat objek Database baru
        } else {
            $database = new Database();
            $this->conn = $database->getConnection();
        }
    }

    // Method getAll
    // Berfungsi mengambil seluruh data menu dari tabel menu dengan JOIN ke tabel umkm
    // sehingga informasi nama stand UMKM pemilik menu dapat ikut ditampilkan
    // Mengembalikan array berisi seluruh data menu atau array kosong jika terjadi error
    public function getAll(): array {
        try {
            // Menyiapkan query SELECT dengan LEFT JOIN ke tabel umkm berdasarkan id_umkm
            $query = "SELECT 
                        m.id_menu,
                        m.nama_menu,
                        m.harga_menu,
                        m.id_umkm,
                        m.deskripsi,
                        u.nama_stand
                      FROM {$this->table} m
                      LEFT JOIN umkm u ON m.id_umkm = u.id_umkm
                      ORDER BY m.id_menu ASC";

            // Menyiapkan statement query PDO
            $stmt = $this->conn->prepare($query);
            
            // Menjalankan query SQL
            $stmt->execute();

            // Mengambil semua baris hasil query dalam bentuk array asosiatif
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            // Mencatat pesan kesalahan ke error log sistem
            error_log("Menu::getAll Error: " . $e->getMessage());
            // Mengembalikan array kosong jika query mengalami kegagalan
            return [];
        }
    }

    // Method getById
    // Berfungsi mengambil satu baris data detail menu berdasarkan ID beserta nama stand UMKM-nya
    // Parameter $id: ID menu yang dicari
    // Mengembalikan array data menu jika ditemukan, atau boolean false jika tidak ditemukan / error
    public function getById($id): array|false {
        try {
            // Menyiapkan query SELECT dengan LEFT JOIN berdasarkan id_menu spesifik
            $query = "SELECT 
                        m.id_menu,
                        m.nama_menu,
                        m.harga_menu,
                        m.id_umkm,
                        m.deskripsi,
                        u.nama_stand
                      FROM {$this->table} m
                      LEFT JOIN umkm u ON m.id_umkm = u.id_umkm
                      WHERE m.id_menu = :id
                      LIMIT 1";

            // Menyiapkan statement query PDO
            $stmt = $this->conn->prepare($query);
            
            // Mengeksekusi query dengan mengikat nilai parameter :id
            $stmt->execute([':id' => $id]);

            // Mengambil satu baris data menu hasil query
            return $stmt->fetch();
        } catch (PDOException $e) {
            // Mencatat pesan kesalahan ke error log sistem
            error_log("Menu::getById Error: " . $e->getMessage());
            // Mengembalikan false jika terjadi error
            return false;
        }
    }

    // Method create
    // Berfungsi menambahkan data menu baru ke dalam tabel menu
    // Parameter:
    // - $nama_menu  : Nama makanan atau minuman
    // - $harga_menu : Harga satuan menu
    // - $id_umkm    : ID UMKM pemilik menu (foreign key)
    // - $deskripsi  : Keterangan atau deskripsi tambahan menu
    // Mengembalikan true jika penambahan data berhasil, atau false jika gagal
    public function create($nama_menu, $harga_menu, $id_umkm, $deskripsi): bool {
        try {
            // Menyiapkan query INSERT INTO untuk menyimpan data menu baru
            $query = "INSERT INTO {$this->table} (nama_menu, harga_menu, id_umkm, deskripsi)
                      VALUES (:nama_menu, :harga_menu, :id_umkm, :deskripsi)";

            // Menyiapkan statement query PDO
            $stmt = $this->conn->prepare($query);

            // Mengeksekusi query dengan mengikat nilai parameter
            return $stmt->execute([
                ':nama_menu'  => $nama_menu,
                ':harga_menu' => $harga_menu,
                ':id_umkm'    => $id_umkm,
                ':deskripsi'  => $deskripsi
            ]);
        } catch (PDOException $e) {
            // Mencatat pesan kesalahan ke error log sistem
            error_log("Menu::create Error: " . $e->getMessage());
            // Mengembalikan false jika gagal menyimpan data
            return false;
        }
    }

    // Method update
    // Berfungsi memperbarui data menu yang sudah ada berdasarkan ID menu tertentu
    // Parameter:
    // - $id         : ID menu yang akan diperbarui
    // - $nama_menu  : Nama menu baru
    // - $harga_menu : Harga satuan baru
    // - $id_umkm    : ID UMKM pemilik menu baru
    // - $deskripsi  : Keterangan deskripsi baru
    // Mengembalikan true jika pembaruan berhasil, atau false jika gagal
    public function update($id, $nama_menu, $harga_menu, $id_umkm, $deskripsi): bool {
        try {
            // Menyiapkan query UPDATE untuk mengubah data menu sesuai id_menu
            $query = "UPDATE {$this->table}
                      SET nama_menu = :nama_menu,
                          harga_menu = :harga_menu,
                          id_umkm = :id_umkm,
                          deskripsi = :deskripsi
                      WHERE id_menu = :id";

            // Menyiapkan statement query PDO
            $stmt = $this->conn->prepare($query);

            // Mengeksekusi query dengan mengikat nilai-nilai parameter pembaruan
            return $stmt->execute([
                ':nama_menu'  => $nama_menu,
                ':harga_menu' => $harga_menu,
                ':id_umkm'    => $id_umkm,
                ':deskripsi'  => $deskripsi,
                ':id'         => $id
            ]);
        } catch (PDOException $e) {
            // Mencatat pesan kesalahan ke error log sistem
            error_log("Menu::update Error: " . $e->getMessage());
            // Mengembalikan false jika pembaruan gagal
            return false;
        }
    }

    // Method delete
    // Berfungsi menghapus satu data menu dari tabel menu berdasarkan ID
    // Parameter $id: ID menu yang akan dihapus
    // Mengembalikan true jika berhasil dihapus, atau false jika gagal
    public function delete($id): bool {
        try {
            // Menyiapkan query DELETE untuk menghapus menu berdasarkan id_menu
            $query = "DELETE FROM {$this->table} WHERE id_menu = :id";
            
            // Menyiapkan statement query PDO
            $stmt = $this->conn->prepare($query);

            // Mengeksekusi query dengan mengikat nilai parameter id
            return $stmt->execute([
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            // Mencatat pesan kesalahan ke error log sistem
            error_log("Menu::delete Error: " . $e->getMessage());
            // Mengembalikan false jika penghapusan gagal
            return false;
        }
    }
}
