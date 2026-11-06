<?php
// ============================================================================
// Class Umkm
// Model untuk mengelola data entitas UMKM (tabel: 'umkm') pada database.
// Berelasi Many-to-One dengan tabel 'kategori' (setiap UMKM terhubung ke satu kategori usaha).
// Menyediakan operasi CRUD (Create, Read, Update, Delete) untuk data UMKM/stand pedagang.
// ============================================================================

// Memuat class Database untuk keperluan koneksi
require_once __DIR__ . '/Database.php';

// Definisi Class Umkm
class Umkm {
    // Properti private untuk menyimpan instance koneksi PDO
    private PDO $conn;

    // Properti private untuk menyimpan nama tabel yang dikelola
    private string $table = 'umkm';

    // Constructor Umkm
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
    // Berfungsi mengambil seluruh data UMKM dari tabel umkm dengan LEFT JOIN ke tabel kategori
    // untuk menampilkan jenis kategori dari masing-masing UMKM
    // Mengembalikan array berisi seluruh data UMKM atau array kosong jika terjadi error
    public function getAll(): array {
        try {
            // Menyiapkan query SELECT dengan LEFT JOIN ke tabel kategori berdasarkan id_kategori
            $query = "SELECT 
                        u.id_umkm,
                        u.nama_stand,
                        u.nama_penjaga,
                        u.foto,
                        u.sertifikasi_halal,
                        u.id_kategori,
                        k.jenis AS jenis_kategori,
                        k.jenis
                      FROM {$this->table} u
                      LEFT JOIN kategori k ON u.id_kategori = k.id_kategori
                      ORDER BY u.id_umkm ASC";

            // Menyiapkan statement query PDO
            $stmt = $this->conn->prepare($query);
            
            // Menjalankan query SQL
            $stmt->execute();

            // Mengambil semua baris hasil query dalam bentuk array asosiatif
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            // Mencatat pesan kesalahan ke error log sistem
            error_log("Umkm::getAll Error: " . $e->getMessage());
            // Mengembalikan array kosong jika query mengalami kegagalan
            return [];
        }
    }

    // Method getById
    // Berfungsi mengambil satu baris data detail UMKM berdasarkan ID beserta jenis kategorinya
    // Parameter $id: ID UMKM yang dicari
    // Mengembalikan array data UMKM jika ditemukan, atau boolean false jika tidak ditemukan / error
    public function getById($id): array|false {
        try {
            // Menyiapkan query SELECT dengan LEFT JOIN berdasarkan id_umkm spesifik
            $query = "SELECT 
                        u.id_umkm,
                        u.nama_stand,
                        u.nama_penjaga,
                        u.foto,
                        u.sertifikasi_halal,
                        u.id_kategori,
                        k.jenis AS jenis_kategori,
                        k.jenis
                      FROM {$this->table} u
                      LEFT JOIN kategori k ON u.id_kategori = k.id_kategori
                      WHERE u.id_umkm = :id
                      LIMIT 1";

            // Menyiapkan statement query PDO
            $stmt = $this->conn->prepare($query);
            
            // Mengeksekusi query dengan mengikat nilai parameter :id
            $stmt->execute([':id' => $id]);

            // Mengambil satu baris data UMKM hasil query
            return $stmt->fetch();
        } catch (PDOException $e) {
            // Mencatat pesan kesalahan ke error log sistem
            error_log("Umkm::getById Error: " . $e->getMessage());
            // Mengembalikan false jika terjadi error
            return false;
        }
    }

    // Method create
    // Berfungsi menambahkan data UMKM baru ke dalam tabel umkm
    // Parameter:
    // - $nama_stand        : Nama stand atau gerai UMKM
    // - $nama_penjaga      : Nama penjaga atau pemilik stand
    // - $foto              : Nama file gambar/foto UMKM (nullable)
    // - $sertifikasi_halal : Status sertifikasi halal ('ada' atau 'tidak')
    // - $id_kategori       : ID kategori usaha UMKM (foreign key, nullable jika kosong)
    // Mengembalikan true jika penambahan data berhasil, atau false jika gagal
    public function create($nama_stand, $nama_penjaga, $foto, $sertifikasi_halal, $id_kategori): bool {
        try {
            // Menyiapkan query INSERT INTO untuk menyimpan data UMKM baru
            $query = "INSERT INTO {$this->table} (nama_stand, nama_penjaga, foto, sertifikasi_halal, id_kategori)
                      VALUES (:nama_stand, :nama_penjaga, :foto, :sertifikasi_halal, :id_kategori)";

            // Menyiapkan statement query PDO
            $stmt = $this->conn->prepare($query);

            // Mengeksekusi query dengan mengikat nilai parameter input
            return $stmt->execute([
                ':nama_stand'        => $nama_stand,
                ':nama_penjaga'      => $nama_penjaga,
                ':foto'              => $foto,
                ':sertifikasi_halal' => $sertifikasi_halal,
                ':id_kategori'       => !empty($id_kategori) ? $id_kategori : null
            ]);
        } catch (PDOException $e) {
            // Mencatat pesan kesalahan ke error log sistem
            error_log("Umkm::create Error: " . $e->getMessage());
            // Mengembalikan false jika gagal menyimpan data
            return false;
        }
    }

    // Method update
    // Berfungsi memperbarui data UMKM yang sudah ada berdasarkan ID UMKM tertentu
    // Parameter:
    // - $id                : ID UMKM yang akan diperbarui
    // - $nama_stand        : Nama stand baru
    // - $nama_penjaga      : Nama penjaga baru
    // - $foto              : Nama file foto baru (atau tetap foto lama)
    // - $sertifikasi_halal : Status sertifikasi halal baru ('ada' atau 'tidak')
    // - $id_kategori       : ID kategori baru (nullable jika kosong)
    // Mengembalikan true jika pembaruan berhasil, atau false jika gagal
    public function update($id, $nama_stand, $nama_penjaga, $foto, $sertifikasi_halal, $id_kategori): bool {
        try {
            // Menyiapkan query UPDATE untuk mengubah data UMKM berdasarkan id_umkm
            $query = "UPDATE {$this->table} 
                      SET nama_stand = :nama_stand,
                          nama_penjaga = :nama_penjaga,
                          foto = :foto,
                          sertifikasi_halal = :sertifikasi_halal,
                          id_kategori = :id_kategori
                      WHERE id_umkm = :id";

            // Menyiapkan statement query PDO
            $stmt = $this->conn->prepare($query);

            // Mengeksekusi query dengan mengikat nilai-nilai parameter pembaruan
            return $stmt->execute([
                ':nama_stand'        => $nama_stand,
                ':nama_penjaga'      => $nama_penjaga,
                ':foto'              => $foto,
                ':sertifikasi_halal' => $sertifikasi_halal,
                ':id_kategori'       => !empty($id_kategori) ? $id_kategori : null,
                ':id'                => $id
            ]);
        } catch (PDOException $e) {
            // Mencatat pesan kesalahan ke error log sistem
            error_log("Umkm::update Error: " . $e->getMessage());
            // Mengembalikan false jika pembaruan gagal
            return false;
        }
    }

    // Method delete
    // Berfungsi menghapus satu data UMKM dari tabel umkm berdasarkan ID
    // Parameter $id: ID UMKM yang akan dihapus
    // Mengembalikan true jika data berhasil dihapus, atau false jika gagal
    public function delete($id): bool {
        try {
            // Menyiapkan query DELETE untuk menghapus data berdasarkan id_umkm
            $query = "DELETE FROM {$this->table} WHERE id_umkm = :id";
            
            // Menyiapkan statement query PDO
            $stmt = $this->conn->prepare($query);

            // Mengeksekusi query dengan mengikat nilai parameter id
            return $stmt->execute([
                ':id' => $id
            ]);
        } catch (PDOException $e) {
            // Mencatat pesan kesalahan ke error log sistem
            error_log("Umkm::delete Error: " . $e->getMessage());
            // Mengembalikan false jika proses penghapusan gagal
            return false;
        }
    }
}
