<?php
//proje başlatmayı dahil et
require_once 'init.php';

//eğer kullanıcı zaten giriş yapmışsa mesaj göster
if (isset($_SESSION['user_id'])) {
    include 'header.php';
    echo '<div class="container mt-4"><div class="alert alert-info">zaten giriş yapmış durumdasınız. <a href="karakterler.php" class="alert-link">karakterler sayfanıza gidebilirsiniz</a>.</div></div>';
    include 'footer.php';
    exit();
}

//değişkenleri tanımla
$mesaj = '';
$is_error = false;

//eğer form gönderilmişse
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //eğer giriş yap butonuna basılmışsa
    if (isset($_POST['login'])) {
        $stmt = $pdo->prepare("SELECT * FROM kullanicilar WHERE kullanici_adi = ?");
        $stmt->execute([$_POST['kullanici_adi']]);
        $user = $stmt->fetch();
        //kullanıcı varsa ve şifre doğruysa
        if ($user && password_verify($_POST['sifre'], $user['sifre'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['kullanici_adi'] = $user['kullanici_adi'];
            header('Location: karakterler.php');
            exit();
        } else {
            $mesaj = 'geçersiz kullanıcı adı veya şifre.';
            $is_error = true;
        }
    //eğer kayıt ol butonuna basılmışsa
    } elseif (isset($_POST['register'])) {
        $kullanici_adi = $_POST['kullanici_adi'];
        $email = $_POST['email'];
        $sifre = $_POST['sifre'];
        //alan kontrolü
        if (empty($kullanici_adi) || empty($email) || empty($sifre)) {
            $mesaj = 'tüm alanlar zorunludur.';
            $is_error = true;
        } elseif ($_POST['sifre'] !== $_POST['sifre_tekrar']) {
            $mesaj = 'şifreler eşleşmiyor.';
            $is_error = true;
        } else {
            //kullanıcı var mı kontrol et
            $stmt = $pdo->prepare("SELECT id FROM kullanicilar WHERE kullanici_adi = ? OR email = ?");
            $stmt->execute([$kullanici_adi, $email]);
            if ($stmt->fetch()) {
                $mesaj = 'kullanıcı adı veya email zaten mevcut.';
                $is_error = true;
            } else {
                //yeni kullanıcıyı kaydet
                $hashed_sifre = password_hash($sifre, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO kullanicilar (kullanici_adi, email, sifre) VALUES (?, ?, ?)");
                $stmt->execute([$kullanici_adi, $email, $hashed_sifre]);
                $mesaj = 'kayıt başarılı! lütfen giriş yapın.';
            }
        }
    }
}
//hangi sayfanın gösterileceğini belirle
$sayfa = $_GET['sayfa'] ?? 'giris';
include 'header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm mt-5">
            <div class="card-body p-4">
                <?php //mesaj varsa göster?>
                <?php if ($mesaj): ?>
                    <div class="alert <?php echo $is_error ? 'alert-danger' : 'alert-success'; ?>"><?php echo $mesaj; ?></div>
                <?php endif; ?>

                <?php //kayıt formu?>
                <?php if ($sayfa === 'kayit'): ?>
                    <h3 class="text-center mb-4">Kayıt Ol</h3>
                    <form action="login.php?sayfa=kayit" method="post">
                        <div class="mb-3"><label class="form-label">Kullanıcı Adı</label><input type="text" class="form-control" name="kullanici_adi" required></div>
                        <div class="mb-3"><label class="form-label">Email Adresi</label><input type="email" class="form-control" name="email" required></div>
                        <div class="mb-3"><label class="form-label">Şifre</label><input type="password" class="form-control" name="sifre" required></div>
                        <div class="mb-3"><label class="form-label">Şifre Tekrar</label><input type="password" class="form-control" name="sifre_tekrar" required></div>
                        <button type="submit" name="register" class="btn btn-success w-100">Kayıt Ol</button>
                    </form>
                    <p class="text-center mt-3">zaten hesabın var mı? <a href="login.php" class="text-success">giriş yap</a></p>
                <?php else: ?>
                <?php //giriş formu?>
                    <h3 class="text-center mb-4">Giriş Yap</h3>
                    <form action="login.php" method="post">
                        <div class="mb-3"><label class="form-label">Kullanıcı Adı</label><input type="text" class="form-control" name="kullanici_adi" required></div>
                        <div class="mb-3"><label class="form-label">Şifre</label><input type="password" class="form-control" name="sifre" required></div>
                        <button type="submit" name="login" class="btn btn-primary w-100">Giriş Yap</button>
                    </form>
                    <p class="text-center mt-3">hesabın yok mu? <a href="login.php?sayfa=kayit" class="text-success">kayıt ol</a></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>