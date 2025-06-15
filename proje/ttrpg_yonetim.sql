CREATE DATABASE IF NOT EXISTS `ttrpg_yonetim` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `ttrpg_yonetim`;

CREATE TABLE `kullanicilar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kullanici_adi` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `sifre` varchar(255) NOT NULL,
  `kayit_tarihi` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `kullanici_adi` (`kullanici_adi`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `karakterler` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kullanici_id` int(11) NOT NULL,
  `karakter_adi` varchar(100) NOT NULL,
  `irki` varchar(50) NOT NULL,
  `sinifi` varchar(50) NOT NULL,
  `seviye` int(11) NOT NULL DEFAULT 1,
  `guc` int(11) NOT NULL DEFAULT 10,
  `ceviklik` int(11) NOT NULL DEFAULT 10,
  `dayaniklilik` int(11) NOT NULL DEFAULT 10,
  `zeka` int(11) NOT NULL DEFAULT 10,
  `bilgelik` int(11) NOT NULL DEFAULT 10,
  `karizma` int(11) NOT NULL DEFAULT 10,
  `olusturma_tarihi` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `kullanici_id` (`kullanici_id`),
  CONSTRAINT `karakterler_ibfk_1` FOREIGN KEY (`kullanici_id`) REFERENCES `kullanicilar` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `envanter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `karakter_id` int(11) NOT NULL,
  `esya_adi` varchar(100) NOT NULL,
  `esya_tipi` enum('Silah','Zırh','İksir','Tılsım','Diğer') NOT NULL,
  `aciklama` text DEFAULT NULL,
  `miktar` int(11) NOT NULL DEFAULT 1,
  `eklenme_tarihi` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `karakter_id` (`karakter_id`),
  CONSTRAINT `envanter_ibfk_1` FOREIGN KEY (`karakter_id`) REFERENCES `karakterler` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;