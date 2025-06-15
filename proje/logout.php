<?php
//proje başlatmayı dahil et
require_once 'init.php';

//oturum değişkenlerini temizle
session_unset();
//oturumu sonlandır
session_destroy();

//giriş sayfasına yönlendir
header('Location: login.php');
exit();
?>