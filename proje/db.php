<?php
//veritabanı bağlantı bilgileri
//github için sansürlenmiş halde
define('DB_HOST', 'localhost');
define('DB_USER', 'username');
define('DB_PASS', 'password');
define('DB_NAME', 'database');


try {
    //pdo ile bağlantıyı kur
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    //hata modunu ayarla
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //veri çekme modunu ayarla
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    //bağlantı hatası varsa programı durdur
    die("veritabanı bağlantısı kurulamadı: " . $e->getMessage());
}
?>