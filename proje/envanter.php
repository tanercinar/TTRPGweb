<?php
//proje başlatmayı dahil et
require_once 'init.php';

//kullanıcı giriş yapmamışsa logine yönlendir
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

//karakter idsi yoksa karakterler sayfasına yönlendir
if (!isset($_GET['karakter_id'])) {
    header('Location: karakterler.php');
    exit();
}

//kullanıcı ve karakter idlerini al
$user_id = $_SESSION['user_id'];
$karakter_id = $_GET['karakter_id'];

//karakterin bu kullanıcıya ait olduğunu doğrula
$stmt = $pdo->prepare("SELECT * FROM karakterler WHERE id = ? AND kullanici_id = ?");
$stmt->execute([$karakter_id, $user_id]);
$karakter = $stmt->fetch();
if (!$karakter) {
    header('Location: karakterler.php');
    exit();
}

//eğer form gönderilmişse (yeni ekleme veya güncelleme)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $esya_id = $_POST['esya_id'] ?? null;
    $esya_adi = $_POST['esya_adi'];
    $esya_tipi = $_POST['esya_tipi'];
    $miktar = $_POST['miktar'];
    $aciklama = $_POST['aciklama'];
    
    //eşya id varsa güncelle, yoksa ekle
    if ($esya_id) {
        $stmt = $pdo->prepare("UPDATE envanter SET esya_adi=?, esya_tipi=?, miktar=?, aciklama=? WHERE id=? AND karakter_id=?");
        $stmt->execute([$esya_adi, $esya_tipi, $miktar, $aciklama, $esya_id, $karakter_id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO envanter (karakter_id, esya_adi, esya_tipi, miktar, aciklama) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$karakter_id, $esya_adi, $esya_tipi, $miktar, $aciklama]);
    }
    //işlemden sonra sayfayı yenile
    header('Location: envanter.php?karakter_id=' . $karakter_id);
    exit();
}

//silme işlemi
if (isset($_GET['action']) && $_GET['action'] === 'sil' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM envanter WHERE id = ? AND karakter_id = ?");
    $stmt->execute([$_GET['id'], $karakter_id]);
    header('Location: envanter.php?karakter_id=' . $karakter_id);
    exit();
}

//düzenleme için eşya verisini çek
$duzenlenecek_esya = null;
if (isset($_GET['action']) && $_GET['action'] === 'duzenle' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM envanter WHERE id = ? AND karakter_id = ?");
    $stmt->execute([$_GET['id'], $karakter_id]);
    $duzenlenecek_esya = $stmt->fetch();
}

//tüm envanteri listelemek için veriyi çek
$stmt = $pdo->prepare("SELECT * FROM envanter WHERE karakter_id = ? ORDER BY eklenme_tarihi DESC");
$stmt->execute([$karakter_id]);
$envanter_esyaları = $stmt->fetchAll();

//sayfa görünümü
include 'header.php';
?>
<a href="karakterler.php" class="btn btn-sm btn-outline-secondary mb-3">← karakterlerime dön</a>
<h1><?php echo htmlspecialchars($karakter['karakter_adi']); ?> - envanter</h1>

<div class="row">
    <!--sol taraf: form-->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <?php echo $duzenlenecek_esya ? 'eşyayı düzenle' : 'yeni eşya ekle'; ?>
            </div>
            <div class="card-body">
                <form action="envanter.php?karakter_id=<?php echo $karakter_id; ?>" method="post">
                    <input type="hidden" name="esya_id" value="<?php echo $duzenlenecek_esya['id'] ?? ''; ?>">
                    <div class="mb-3">
                        <label class="form-label">Eşya Adı</label>
                        <input type="text" class="form-control" name="esya_adi" value="<?php echo htmlspecialchars($duzenlenecek_esya['esya_adi'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Eşya Tipi</label>
                        <select class="form-select" name="esya_tipi" required>
                            <?php $tipler = ['Silah', 'Zırh', 'İksir', 'Tılsım', 'Diğer']; ?>
                            <?php foreach ($tipler as $tip): ?>
                                <option value="<?php echo $tip; ?>" <?php echo (isset($duzenlenecek_esya['esya_tipi']) && $duzenlenecek_esya['esya_tipi'] == $tip) ? 'selected' : ''; ?>><?php echo $tip; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Miktar</label>
                        <input type="number" class="form-control" name="miktar" value="<?php echo $duzenlenecek_esya['miktar'] ?? '1'; ?>" min="1" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Açıklama</label>
                        <textarea class="form-control" name="aciklama" rows="3"><?php echo htmlspecialchars($duzenlenecek_esya['aciklama'] ?? ''); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100"><?php echo $duzenlenecek_esya ? 'güncelle' : 'envantere ekle'; ?></button>
                    <?php if ($duzenlenecek_esya): ?>
                        <a href="envanter.php?karakter_id=<?php echo $karakter_id; ?>" class="btn btn-secondary w-100 mt-2">iptal</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
    <!--sağ taraf: tablo-->
    <div class="col-md-8">
        <table class="table table-striped table-hover table-bordered">
            <thead class="table-dark">
                <tr><th>eşya adı</th><th>tipi</th><th>miktar</th><th>açıklama</th><th>işlemler</th></tr>
            </thead>
            <tbody>
                <?php foreach ($envanter_esyaları as $esya): ?>
                <tr>
                    <td><?php echo htmlspecialchars($esya['esya_adi']); ?></td>
                    <td><span class="badge bg-secondary"><?php echo htmlspecialchars($esya['esya_tipi']); ?></span></td>
                    <td><?php echo $esya['miktar']; ?></td>
                    <td><?php echo htmlspecialchars($esya['aciklama']); ?></td>
                    <td>
                        <a href="envanter.php?karakter_id=<?php echo $karakter_id; ?>&action=duzenle&id=<?php echo $esya['id']; ?>" class="btn btn-sm btn-warning">d</a>
                        <a href="envanter.php?karakter_id=<?php echo $karakter_id; ?>&action=sil&id=<?php echo $esya['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('bu eşyayı silmek istediğinizden emin misiniz?')">s</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($envanter_esyaları)): ?>
                    <tr><td colspan="5" class="text-center">bu karakterin envanteri boş.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include 'footer.php'; ?>