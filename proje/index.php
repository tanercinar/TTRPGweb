<?php
//proje başlatmayı dahil et
require_once 'init.php';

//kullanıcı giriş yapmışsa karakterlere yönlendir
if (isset($_SESSION['user_id'])) {
    header('Location: karakterler.php');
} else {
    //giriş yapmamışsa logine yönlendir
    header('Location: login.php');
}
//yönlendirme sonrası kodun çalışmasını durdur
exit();
?>