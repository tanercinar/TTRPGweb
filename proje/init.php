<?php
//proje başlatma dosyası

//oturum yoksa başlat
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//veritabanı bağlantısını dahil et
require_once 'db.php';
?>