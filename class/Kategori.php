<?php
// ============================================================================
// Class Kategori
// Model untuk mengelola data entitas Kategori (tabel: 'kategori') pada database.
// Menyediakan operasi CRUD (Create, Read, Update, Delete) untuk jenis kategori UMKM.
// ============================================================================

// Memuat class Database untuk keperluan koneksi
require_once __DIR__ . '/Database.php';

// Definisi Class Kategori
class Kategori {
    // Properti private untuk menyimpan instance koneksi PDO
    private PDO $conn;

    // Properti private untuk mendefinisikan nama tabel yang digunakan
    private string $table = 'kategori';

    // Constructor Kategori
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
    // Berfungsi mengambil seluruh data kategori dari tabel database secara urut ascending berdasarkan ID
    // Mengembalikan array berisi seluruh record kategori, atau array kosong jika terjadi kesalahan
    public function getAll(): array {
        try {
            // Menyiapkan query SQL untuk mengambil id_kategori dan jenis
            $query = "SELECT id_kategori, jenis FROM {$this->table} ORDER BY id_kategori ASC";
            
            // Menyiapkan statement query PDO
            $stmt = $this->conn->prepare($query);
            
            // Menjalankan query SQL
            $stmt->execute();

            // Mengambil semua baris hasil query dalam bentuk array asosiatif
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            // Mencatat pesan kesalahan ke error log sistem
            error_log("Kategori::getAll Error: " . $e->getMessage());
            // Mengembalikan array kosong jika query gagal dieksekusi
            return [];
        }
    }

    // Method getById
    // Berfungsi mengambil satu baris data kategori spesifik berdasarkan ID
    // Parameter $id: ID kategori yang dicari
    // Mengembalikan array data kategori jika ditemukan, atau boolean false jika tidak ditemukan / error
    public function getById($id): array|false {
        try {
            // Query SQL untuk mengambil data kategori berdasarkan id_kategori
            $query = "SELECT id_kategori, jenis FROM {$this->table} WHERE id_kategori = :id LIMIT 1";
            
            // Menyiapkan statement query PDO
            $stmt = $this->conn->prepare($query);
            
            // Mengeksekusi query dengan mengikat nilai parameter :id
            $stmt->execute([':id' => $id]);

            // Mengambil satu baris data hasil query
            return $stmt->fetch();
        } catch (PDOException $e) {
            // Mencatat pesan kesalahan ke error log sistem
            error_log("Kategori::getById Error: " . $e->getMessage());
            // Mengembalikan false jika proses query mengalami error
            return false;
        }
    }

    // Method create
    // Berfungsi menambahkan data kategori baru ke dalam tabel kategori
    // Parameter $jenis: nama jenis kategori yang akan disimpan
    // Mengembalikan true jika penambahan data berhasil, atau false jika gagal
    public function create($jenis): bool {
        try {
            // Menyiapkan query INSERT INTO untuk menambahkan kategori baru
            $query = "INSERT INTO {$this->table} (jenis) VALUES (:jenis)";
            
            // Menyiapkan statement query PDO
            $stmt = $this->conn->prepare($query);

            // Mengeksekusi query dengan mengikat nilai parameter jenis
            return $stmt->execute([
                ':jenis' => $jenis
            ]);
        } catch (PDOException $e) {
            // Mencatat pesan kesalahan ke error log sistem
            error_log("Kategori::create Error: " . $e->getMessage());
            // Mengembalikan false jika gagal menyimpan data
            return false;
        }
    }

    // Method update
    // Berfungsi memperbarui nama kategori berdasarkan ID kategori tertentu
    // Parameter $id: ID kategori yang akan diubah
    // Parameter $jenis: nilai nama kategori yang baru
    // Mengembalikan true jika pembaruan berhasil, atau false jika gagal
    public function update($id, $jenis): bool {
        try {
            // Menyiapkan query UPDATE untuk mengubah data kategori sesuai id_kategori
            $query = "UPDATE {$this->table} SET jenis = :jenis WHERE id_kategori = :id";
            
            // Menyiapkan statement query PDO
            $stmt = $this->conn->prepare($query);

            // Mengeksekusi query dengan mengikat nilai parameter jenis dan id
            return $stmt->execute([
                ':jenis' => $jenis,
                ':id'    => $id
            ]);
        } catch (PDOException $e) {
            // Mencatat pesan kesalahan ke error log sistem
            error_log("Kategori::update Error: " . $e->getMessage());
            // Mengembalikan false jika pembaruan gagal
            return false;
        }
    }

    // Method delete
    // Berfungsi menghapus satu data kategori dari tabel kategori berdasarkan ID
    // Parameter $id: ID kategori yang akan dihapus
    // Mengembalikan true jika data berhasil dihapus, atau false jika gagal
    public function delete($id): bool {
        try {
            // Menyiapkan query DELETE untuk menghapus data berdasarkan id_kategori
            $query = "DELETE FROM {$this->table} WHERE id_kategori = :id";
            
            // Menyiapkan statement query PDO
            $stmt = $this->conn->prepare($query);

            // Mengeksekusi query dengan mengikat nilai parameter id
            return $stmt->execute([
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            // Mencatat pesan kesalahan ke error log sistem
            error_log("Kategori::delete Error: " . $e->getMessage());
            // Mengembalikan false jika proses penghapusan gagal
            return false;
        }
    }
}
