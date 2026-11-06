-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: data_umkm_saparua_new
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id_admin` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id_admin`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'admin_saparua','0192023a7bbd73250516f069df18b500');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jadwal`
--

DROP TABLE IF EXISTS `jadwal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jadwal` (
  `id_jadwal` int(11) NOT NULL AUTO_INCREMENT,
  `jam_buka` time DEFAULT NULL,
  `jam_tutup` time DEFAULT NULL,
  PRIMARY KEY (`id_jadwal`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jadwal`
--

LOCK TABLES `jadwal` WRITE;
/*!40000 ALTER TABLE `jadwal` DISABLE KEYS */;
INSERT INTO `jadwal` VALUES (1,'07:00:00','00:00:00'),(2,'17:00:00','00:00:00'),(3,'15:00:00','00:00:00'),(4,'14:30:00','23:00:00'),(5,'14:00:00','23:00:00'),(6,'16:00:00','23:00:00'),(7,'10:00:00','23:00:00'),(8,'06:00:00','20:00:00'),(9,'06:00:00','19:00:00'),(10,'15:00:00','00:00:00'),(11,'08:00:00','22:00:00'),(12,'15:00:00','19:00:00');
/*!40000 ALTER TABLE `jadwal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `kategori`
--

DROP TABLE IF EXISTS `kategori`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL AUTO_INCREMENT,
  `jenis` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_kategori`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `kategori`
--

LOCK TABLES `kategori` WRITE;
/*!40000 ALTER TABLE `kategori` DISABLE KEYS */;
INSERT INTO `kategori` VALUES (1,'Minuman'),(2,'Makanan Ringan'),(3,'Makanan Berat'),(4,'Buah');
/*!40000 ALTER TABLE `kategori` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lokasi`
--

DROP TABLE IF EXISTS `lokasi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lokasi` (
  `id_lokasi` int(11) NOT NULL AUTO_INCREMENT,
  `koordinat` varchar(255) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  PRIMARY KEY (`id_lokasi`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lokasi`
--

LOCK TABLES `lokasi` WRITE;
/*!40000 ALTER TABLE `lokasi` DISABLE KEYS */;
INSERT INTO `lokasi` VALUES (1,NULL,'Bagian Utara'),(2,NULL,'Bagian Barat');
/*!40000 ALTER TABLE `lokasi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu`
--

DROP TABLE IF EXISTS `menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `menu` (
  `id_menu` int(11) NOT NULL AUTO_INCREMENT,
  `nama_menu` varchar(255) DEFAULT NULL,
  `harga_menu` int(11) DEFAULT NULL,
  `id_umkm` int(11) NOT NULL,
  `deskripsi` mediumtext DEFAULT NULL,
  `id_rasa` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_menu`),
  KEY `fk_menu_umkm` (`id_umkm`),
  KEY `id_rasa` (`id_rasa`),
  CONSTRAINT `fk_menu_umkm` FOREIGN KEY (`id_umkm`) REFERENCES `umkm` (`id_umkm`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`id_rasa`) REFERENCES `rasa_menu` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=453 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu`
--

LOCK TABLES `menu` WRITE;
/*!40000 ALTER TABLE `menu` DISABLE KEYS */;
INSERT INTO `menu` VALUES (1,'Es Jeruk',12000,1,'Segar, Masam',4),(2,'Sunkist',18000,1,'Segar, Masam',4),(3,'Lemon',15000,1,'Segar, Masam',4),(4,'Jeruk Murni',25000,1,'Segar, Masam',4),(5,'Sunkist Murni',50000,1,'Segar, Masam',4),(6,'Lemon Murni',35000,1,'Segar, Masam',4),(7,'Jeruk + Lemon',20000,1,'Segar, Masam',4),(8,'Jeruk + Sunkist',25000,1,'Segar, Masam',4),(9,'Sunkist + Lemon',25000,1,'Segar, Masam',4),(10,'Cilor',10000,2,'Berminyak, Gurih',9),(11,'Bilor',10000,2,'Berminyak, Gurih',9),(12,'Telur Gulung',10000,2,'Berminyak, Gurih',9),(13,'Chocolate',5000,3,'Berminyak, Manis',9),(14,'Chocolate Keju',5000,3,'Berminyak, Manis',9),(15,'Chocolate Kacang',5000,3,'Berminyak, Manis',9),(16,'Oreo Susu',5000,3,'Berminyak, Manis',9),(17,'Matcha Cheese',5000,3,'Berminyak, Manis',9),(18,'Tiramisu Crunchy',5000,3,'Berminyak, Manis',9),(19,'Ayam Original',5000,3,'Berminyak, Protein',10),(20,'Mac & Chick Pasta',5000,3,'Berminyak, Protein',10),(21,'Smoked Beef Mayo',5000,3,'Berminyak, Protein',10),(22,'Kornet Keju',5000,3,'Berminyak, Keju',11),(23,'Keju Lumer',5000,3,'Berminyak, Keju',11),(24,'Ayam Suwir Pedas',5000,3,'Berminyak, Pedas, Gurih',3),(25,'Ayam Bumbu Taichan',5000,3,'Berminyak, Pedas, Gurih',3),(26,'Sambel Goreng Ati',5000,3,'Berminyak, Pedas, Gurih',3),(27,'Chicken Curry',5000,3,'Berminyak, Pedas, Gurih',3),(28,'Jando',5000,3,'Berminyak, Pedas, Gurih',3),(29,'Sempol Original',1000,4,'Berminyak, Pedas, Gurih',3),(30,'Sempol Isi Ayam',2000,4,'Berminyak, Pedas, Gurih',3),(31,'Sempol Isi Telur Puyuh',2000,4,'Berminyak, Pedas, Gurih',3),(32,'Putera 1',10000,5,'Pedas, Gurih, Manis',3),(33,'Putera 2',15000,5,'Pedas, Gurih, Manis',3),(34,'Putera 3',20000,5,'Pedas, Gurih, Manis',3),(35,'Putera Spesial',25000,5,'Pedas, Gurih',3),(36,'Baso Bakar',1000,6,'Pedas, Gurih',3),(37,'Thai Tea',14000,7,'Manis',1),(38,'Green Tea',14000,7,'Manis',1),(39,'Basreng Bojot Small',8000,8,'Pedas, Gurih',3),(40,'Basreng Bojot Large',12000,8,'Pedas, Gurih',3),(41,'Otak Otak Bojot Small',8000,8,'Pedas, Gurih',3),(42,'Otak Otak Bojot Large',12000,8,'Pedas, Gurih',3),(43,'Mix Otak Otak & Basreng Small',8000,8,'Pedas, Gurih',3),(44,'Mix Otak Otak & Basreng Large',12000,8,'Pedas, Gurih',3),(45,'Medium Kebab Ayam',15000,9,'Pedas, Gurih',3),(46,'Large kebab Ayam',25000,9,'Pedas, Gurih',3),(47,'Medium Kebab Sapi',20000,9,'Pedas, Gurih',3),(48,'Large Kebab Sapi',35000,9,'Pedas, Gurih',3),(49,'Americano',8000,10,'Caffein',8),(50,'Kopi Susu',10000,10,'Caffein, Manis',8),(51,'Gula Aren',12000,10,'Caffein, Manis',8),(52,'Almond',12000,10,'Caffein, Manis',8),(53,'Butterscotch',12000,10,'Caffein, Manis',8),(54,'Chocolate',10000,10,'Manis',1),(55,'Matcha',10000,10,'Manis',1),(56,'Bala Bala',5000,11,'Berminyak, Gurih',9),(57,'Tempe',5000,11,'Berminyak, Gurih',9),(58,'Tahu',5000,11,'Berminyak, Gurih',9),(59,'Original Milo S',11000,12,'Manis',1),(60,'Original Milo M',13000,12,'Manis',1),(61,'Original Milo L',15000,12,'Manis',1),(62,'Milo Coklat S',13000,12,'Manis',1),(63,'Milo Coklat M',15000,12,'Manis',1),(64,'Milo Coklat L',17000,12,'Manis',1),(65,'Milo Dino S',13000,12,'Manis',1),(66,'Milo Dino M',15000,12,'Manis',1),(67,'Milo Dino L',17000,12,'Manis',1),(68,'Milo Strawberry S',16000,12,'Manis',1),(69,'Milo Strawberry M',18000,12,'Manis',1),(70,'Milo Strawberry L',20000,12,'Manis',1),(71,'Ice Matcha',13000,12,'Manis',1),(72,'Matcha Latte Original',15000,12,'Manis',1),(73,'Matcha Latte Cold Whisk/Creamy',17000,12,'Manis',1),(74,'Matcha Strawberry',20000,12,'Manis',1),(75,'Matcha Blueberry',20000,12,'Manis',1),(76,'Matcha Mango',20000,12,'Manis',1),(77,'Matcha Banana',20000,12,'Manis',1),(78,'Matcha Milo',20000,12,'Manis',1),(79,'Matcha Butterscotch',22000,12,'Manis, Creamy',6),(80,'Matcha Hazelnut',22000,12,'Manis',1),(81,'Mango Fizz',18000,12,'Manis',1),(82,'Sakura Breeze',18000,12,'Manis',1),(83,'Kyoto Citrus',18000,12,'Manis',1),(84,'Luk Chup',3500,13,'Manis',1),(85,'Vanilla Poci Iced Tea',5000,14,'Manis',1),(86,'Jasmine Poci Iced Tea',5000,14,'Manis',1),(87,'Milk Tea',12000,14,'Manis',1),(88,'Chocolate',13000,14,'Manis',1),(89,'Thai Tea',13000,14,'Manis',1),(90,'Cappucino',13000,14,'Manis',1),(91,'BlackCurrant',8000,14,'Manis',1),(92,'Apple',8000,14,'Manis',1),(93,'Lemon Honey',8000,14,'Manis',1),(94,'Lychee',8000,14,'Manis',1),(95,'Mango',8000,14,'Manis',1),(96,'Orange',8000,14,'Manis',1),(97,'Milk Tea Mango',12000,14,'Manis',1),(98,'Milk Tea Orange',12000,14,'Manis',1),(99,'Milk Tea Blackcurrant',12000,14,'Manis',1),(100,'Milk Tea Apple',12000,14,'Manis',1),(101,'Milk Tea Lychee',12000,14,'Manis',1),(102,'Milk Tea Lemon Honey',12000,14,'Manis',1),(103,'Koguler',10000,15,'Caffein',8),(104,'Kosuren',18000,15,'Caffein, Manis',8),(105,'Matchacih',18000,15,'Manis',1),(106,'Gelap',18000,15,'Caffein',8),(107,'Thai Tea',18000,15,'Manis',1),(108,'Mozza Ori M',23000,16,'Manis, Creamy, Tangy',6),(109,'Mozza Ori L',40000,16,'Manis, Creamy, Tangy',6),(110,'Mozza Creamcheese M',25000,16,'Manis, Creamy, Tangy',6),(111,'Mozza Creamcheese L',45000,16,'Manis, Creamy, Tangy',6),(112,'Mozza Chocolate M',25000,16,'Manis, Creamy',6),(113,'Mozza Chocolate L',45000,16,'Manis, Creamy',6),(114,'Mozza Chese Sause M',25000,16,'Manis, Cream, Tangy',6),(115,'Mozza Chese Sauce L',45000,16,'Manis, Cream, Tangy',6),(116,'Mozza Matcha M',25000,16,'Manis, Creamy',6),(117,'Mozza Matcha L',45000,16,'Manis, Creamy',6),(118,'Mozza Tiramisu M',25000,16,'Manis, Creamy',6),(119,'Mozza Tiramisu L',45000,16,'Manis, Creamy',6),(120,'Original M',15000,16,'Manis, Creamy',6),(121,'Original L',27000,16,'Manis, Creamy',6),(122,'Creamcheese M',18000,16,'Manis, Cream, Tangy',6),(123,'Creamcheese L',28000,16,'Manis, Cream, Tangy',6),(124,'Chocolate M',17000,16,'Manis',1),(125,'Chocolate L',28000,16,'Manis',1),(126,'Chese Sause M',17000,16,'Manis, Tangy',7),(127,'Chese Sauce L',28000,16,'Manis, Tangy',7),(128,'Matcha M',17000,16,'Manis',1),(129,'Matcha L',28000,16,'Manis',1),(130,'Tiramisu M',17000,16,'Manis',1),(131,'Tiramisu L',28000,16,'Manis',1),(132,'Takoyaki isi 6',15000,17,'Pedas, Gurih',3),(133,'Takoyaki isi 8',20000,17,'Pedas, Gurih',3),(134,'Takoyaki isi 10',25000,17,'Pedas, Gurih',3),(135,'Okonomiyaki',25000,17,'Pedas, Gurih',3),(136,'Bola-Bola Ayam',1000,18,'Pedas, Gurih',3),(137,'Es Tebu',10000,19,'Manis, Segar',4),(138,'Teh Original',5000,20,'Manis, Segar',4),(139,'Teh Jumbo',7000,20,'Manis, Segar',4),(140,'Teh Susu',8000,20,'Manis, Segar, Protein',4),(141,'Teh Lemon',10000,20,'Manis, Segar',4),(142,'Mangga',15000,21,'Manis, Serat, Segar',4),(143,'Buah Naga',15000,21,'Manis, Serat, Segar',4),(144,'Nanas',15000,21,'Manis, Serat, Segar',4),(145,'Pepaya',10000,21,'Manis, Serat, Segar',4);
/*!40000 ALTER TABLE `menu` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER cek_harga_menu
BEFORE INSERT ON menu
FOR EACH ROW
BEGIN
    IF NEW.harga_menu <= 0 THEN
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Gagal menyimpan: Harga menu tidak boleh 0 atau minus!';
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `metode_pembayaran`
--

DROP TABLE IF EXISTS `metode_pembayaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `metode_pembayaran` (
  `id_metode` int(11) NOT NULL AUTO_INCREMENT,
  `metode_pembayaran` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_metode`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `metode_pembayaran`
--

LOCK TABLES `metode_pembayaran` WRITE;
/*!40000 ALTER TABLE `metode_pembayaran` DISABLE KEYS */;
INSERT INTO `metode_pembayaran` VALUES (1,'Tunai'),(2,'QRIS'),(3,'Transfer Bank'),(4,'E-Wallet'),(5,'Debit Kredit'),(6,'Virtual Account');
/*!40000 ALTER TABLE `metode_pembayaran` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mitra`
--

DROP TABLE IF EXISTS `mitra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mitra` (
  `id_mitra` int(11) NOT NULL AUTO_INCREMENT,
  `nama_mitra` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_mitra`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mitra`
--

LOCK TABLES `mitra` WRITE;
/*!40000 ALTER TABLE `mitra` DISABLE KEYS */;
INSERT INTO `mitra` VALUES (1,'ShoopeFood'),(2,'GoFood'),(3,'GrabFood'),(4,'WhatsApp');
/*!40000 ALTER TABLE `mitra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rasa_menu`
--

DROP TABLE IF EXISTS `rasa_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rasa_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rasa` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rasa_menu`
--

LOCK TABLES `rasa_menu` WRITE;
/*!40000 ALTER TABLE `rasa_menu` DISABLE KEYS */;
INSERT INTO `rasa_menu` VALUES (1,'Manis'),(2,'Gurih'),(3,'Pedas'),(4,'Segar'),(5,'Masam'),(6,'Creamy'),(7,'Tangy'),(8,'Caffein'),(9,'Berminyak'),(10,'Protein'),(11,'Keju'),(12,'Serat'),(13,'Asam'),(14,'Pahit'),(15,'Asin'),(16,'Getir'),(17,'Spicy'),(18,'Savory'),(19,'Bitter'),(20,'Sour');
/*!40000 ALTER TABLE `rasa_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `umkm`
--

DROP TABLE IF EXISTS `umkm`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `umkm` (
  `id_umkm` int(11) NOT NULL AUTO_INCREMENT,
  `nama_stand` varchar(255) DEFAULT NULL,
  `nama_penjaga` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `sertifikasi_halal` enum('ada','tidak') DEFAULT 'tidak',
  `id_kategori` int(11) DEFAULT NULL,
  `id_jadwal` int(11) DEFAULT NULL,
  `id_lokasi` int(11) DEFAULT NULL,
  PRIMARY KEY (`id_umkm`),
  KEY `fk_umkm_kategori` (`id_kategori`),
  KEY `fk_umkm_jadwal` (`id_jadwal`),
  KEY `fk_umkm_lokasi` (`id_lokasi`),
  CONSTRAINT `fk_umkm_jadwal` FOREIGN KEY (`id_jadwal`) REFERENCES `jadwal` (`id_jadwal`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_umkm_kategori` FOREIGN KEY (`id_kategori`) REFERENCES `kategori` (`id_kategori`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_umkm_lokasi` FOREIGN KEY (`id_lokasi`) REFERENCES `lokasi` (`id_lokasi`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `umkm`
--

LOCK TABLES `umkm` WRITE;
/*!40000 ALTER TABLE `umkm` DISABLE KEYS */;
INSERT INTO `umkm` VALUES (1,'Mihcello Es Jeruk Lemon & Sunkist Full Vit C','Andi','sunkis.jpg','tidak',1,1,1),(2,'SDJ HDHT','Seni','sdjhdht.jpg','ada',2,2,1),(3,'Risol & Gehu Pedas Pandawa','Nouval','risol_gpp.jpg','tidak',2,3,1),(4,'Gerobak Sempol Ayam Si Jawa','Dion','sempol_asj.jpg','tidak',2,4,1),(5,'Cilok Urat Daging Tiga Putera','Heri','cilok_udtp.jpg','ada',2,3,1),(6,'Baso Bakar Sehati','Henri','bakso_bs.jpg','tidak',2,2,2),(7,'ThaiTea Premium Tea','Imat','thaitea_pt.jpg','ada',1,5,2),(8,'Basreng Bojot Si Teteh','Rijal','basreng_bst.jpg','tidak',2,3,2),(9,'Kebab Pakistan','Amza','kebab_p.jpg','tidak',3,6,1),(10,'Coffe Siliwangi','Herry','kopi_s.jpg','ada',1,7,2),(11,'Aneka Gorengan','Rizki','aneka_g.jpg','tidak',2,8,1),(12,'Milo Sawadikap Haphaphap','Aris','milo_shhh.jpg','ada',1,2,1),(13,'Luk Chup','Yuli','luk_c.jpg','tidak',2,6,1),(14,'Es Teh Jumbo Happy','Yuli','esteh_jh.jpg','tidak',1,6,1),(15,'Kopsup','Acel','kopsup.jpg','tidak',1,6,1),(16,'Jagung Cheese Tarik','Dede','jagung_ct.jpg','ada',2,6,1),(17,'Sakuraya Takoyaki','Mey','sakuraya_t.jpg','ada',2,9,1),(18,'Bola Bola ayam Abhijaya','Ajhar','bola_ayam_a.jpg','ada',2,10,1),(19,'Sari Tebu Murni','Angga','sari_tm.jpg','tidak',1,11,1),(20,'IyeuTeh','Juli','iyeuteh.jpg','tidak',1,10,1),(21,'Buah','Ahmad','buah.jpg','tidak',4,12,1);
/*!40000 ALTER TABLE `umkm` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'NO_ZERO_IN_DATE,NO_ZERO_DATE,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER set_default_halal
BEFORE INSERT ON umkm
FOR EACH ROW
BEGIN
    IF NEW.sertifikasi_halal IS NULL THEN
        SET NEW.sertifikasi_halal = 'tidak';
    END IF;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `umkm_mitra`
--

DROP TABLE IF EXISTS `umkm_mitra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `umkm_mitra` (
  `id_mitra` int(11) NOT NULL,
  `id_umkm` int(11) NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_mitra`,`id_umkm`),
  KEY `fk_um_umkm` (`id_umkm`),
  CONSTRAINT `fk_um_mitra` FOREIGN KEY (`id_mitra`) REFERENCES `mitra` (`id_mitra`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_um_umkm` FOREIGN KEY (`id_umkm`) REFERENCES `umkm` (`id_umkm`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `umkm_mitra`
--

LOCK TABLES `umkm_mitra` WRITE;
/*!40000 ALTER TABLE `umkm_mitra` DISABLE KEYS */;
INSERT INTO `umkm_mitra` VALUES (1,5,'https://shopee.co.id/universal-link/now-food/shop/22641032?deep_and_deferred=1&shareChannel=copy_link'),(3,5,'https://r.grab.com/g/6-20260517_153625_3bb7df1018db46788d11c9f19072ba65_MEXMPS-6-C7WBGFNHKA6TCT'),(4,3,'https://wa.me/6287722070460'),(4,8,'https://wa.me/6281321377860');
/*!40000 ALTER TABLE `umkm_mitra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `umkm_pembayaran`
--

DROP TABLE IF EXISTS `umkm_pembayaran`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `umkm_pembayaran` (
  `id_umkm` int(11) NOT NULL,
  `id_metode` int(11) NOT NULL,
  PRIMARY KEY (`id_umkm`,`id_metode`),
  KEY `fk_up_metode` (`id_metode`),
  CONSTRAINT `fk_up_metode` FOREIGN KEY (`id_metode`) REFERENCES `metode_pembayaran` (`id_metode`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_up_umkm` FOREIGN KEY (`id_umkm`) REFERENCES `umkm` (`id_umkm`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `umkm_pembayaran`
--

LOCK TABLES `umkm_pembayaran` WRITE;
/*!40000 ALTER TABLE `umkm_pembayaran` DISABLE KEYS */;
INSERT INTO `umkm_pembayaran` VALUES (1,1),(1,2),(2,1),(2,2),(2,3),(3,1),(3,2),(3,3),(4,1),(4,2),(5,1),(5,2),(6,1),(6,2),(6,3),(7,1),(7,2),(8,1),(8,2),(8,3),(9,1),(9,2),(10,1),(10,2),(11,1),(11,2),(12,1),(12,2),(13,1),(13,2),(14,1),(14,2),(15,1),(15,2),(15,3),(16,1),(16,2),(16,3),(17,1),(17,2),(18,1),(18,2),(19,1),(20,1),(20,2),(21,1),(21,2);
/*!40000 ALTER TABLE `umkm_pembayaran` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-06-11 20:03:40
