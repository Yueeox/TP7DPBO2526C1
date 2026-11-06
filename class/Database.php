<?php
// ============================================================================
// Class Database
// Bertanggung jawab untuk mengelola koneksi ke database MySQL/MariaDB menggunakan PDO.
// Menyimpan konfigurasi koneksi serta mengembalikan instance PDO yang siap digunakan.
// ============================================================================

// Memuat file konfigurasi koneksi database
require_once __DIR__ . '/../config/db.php';

// Definisi Class Database
class Database {
    // Properti private untuk menyimpan alamat host server database
    private string $host;

    // Properti private untuk menyimpan username database
    private string $user;

    // Properti private untuk menyimpan password database
    private string $pass;

    // Properti private untuk menyimpan nama database
    private string $dbname;

    // Properti public untuk menyimpan instance koneksi PDO (default: null sebelum terkoneksi)
    public ?PDO $conn = null;

    // Constructor Database
    // Menginisialisasi parameter konfigurasi database (host, user, pass, dbname)
    // Jika parameter tidak diberikan, nilai default diambil dari konstanta konfigurasi
    // Constructor ini juga langsung memanggil method getConnection() untuk membuka koneksi
    public function __construct(
        string $host = DB_HOST,
        string $user = DB_USER,
        string $pass = DB_PASS,
        string $dbname = DB_NAME
    ) {
        // Mengisi nilai atribut/properti koneksi database
        $this->host   = $host;
        $this->user   = $user;
        $this->pass   = $pass;
        $this->dbname = $dbname;

        // Membuka dan menginisialisasi koneksi saat objek Database pertama kali dibuat
        $this->getConnection();
    }

    // Method getConnection
    // Berfungsi membuat dan mengembalikan objek koneksi PDO ke database
    // Menggunakan teknik lazy-initialization agar koneksi PDO hanya dibuat satu kali
    public function getConnection(): PDO {
        // Memeriksa apakah koneksi database belum pernah diinisialisasi
        if ($this->conn === null) {
            // Membentuk string DSN (Data Source Name) dengan driver MySQL dan charset utf8mb4
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
            
            // Konfigurasi opsi untuk driver PDO
            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Menghasilkan exception jika terjadi error pada query SQL
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Format hasil fetch query berupa associative array
                PDO::ATTR_EMULATE_PREPARES   => false,                  // Menggunakan native prepared statements bawaan database
            ];

            try {
                // Mencoba menginisialisasi objek PDO dengan DSN, user, password, dan opsi
                $this->conn = new PDO($dsn, $this->user, $this->pass, $options);
            } catch (PDOException $e) {
                // Mencatat pesan error ke sistem error log jika koneksi gagal
                error_log("Database Connection Error: " . $e->getMessage());
                // Melemparkan kembali PDOException dengan pesan yang informatif
                throw new PDOException("Koneksi database gagal: " . $e->getMessage(), (int)$e->getCode());
            }
        }

        // Mengembalikan instance koneksi PDO yang aktif
        return $this->conn;
    }
}
