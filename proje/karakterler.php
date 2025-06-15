<?php
//proje başlatmayı dahil et
require_once 'init.php';

//eğer kullanıcı giriş yapmamışsa, logine yönlendir
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

//oturumdan kullanıcı idsini al
$user_id = $_SESSION['user_id'];

//form işlemleri
//yeni karakter ekleme
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['yeni_karakter'])) {
    $stmt = $pdo->prepare("INSERT INTO karakterler (kullanici_id, karakter_adi, irki, sinifi, seviye, guc, ceviklik, dayaniklilik, zeka, bilgelik, karizma) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $_POST['karakter_adi'], $_POST['irki'], $_POST['sinifi'], $_POST['seviye'], $_POST['guc'], $_POST['ceviklik'], $_POST['dayaniklilik'], $_POST['zeka'], $_POST['bilgelik'], $_POST['karizma']]);
    header('Location: karakterler.php');
    exit();
}

//karakter silme
if (isset($_GET['action']) && $_GET['action'] === 'sil' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM karakterler WHERE id = ? AND kullanici_id = ?");
    $stmt->execute([$_GET['id'], $user_id]);
    header('Location: karakterler.php');
    exit();
}

//veri çekme
$stmt = $pdo->prepare("SELECT * FROM karakterler WHERE kullanici_id = ? ORDER BY olusturma_tarihi DESC");
$stmt->execute([$user_id]);
$karakterler = $stmt->fetchAll();

//sayfa görünümü
include 'header.php';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Karakterlerin</h1>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#yeniKarakterModal">Yeni Karakter Oluştur</button>
</div>

<div class="row">
    <?php //eğer hiç karakter yoksa mesaj göster?>
    <?php if (empty($karakterler)): ?>
        <div class="col">
             <p class="text-center">henüz hiç karakter oluşturmadınız.</p>
        </div>
    <?php else: ?>
        <?php //karakterleri döngü ile listele?>
        <?php foreach ($karakterler as $karakter): ?>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><?php echo htmlspecialchars($karakter['karakter_adi']); ?></h5>
                    <a href="karakterler.php?action=sil&id=<?php echo $karakter['id']; ?>" class="btn-close" onclick="return confirm('bu karakteri silmek istediğinizden emin misiniz? tüm envanteri de silinecektir.')"></a>
                </div>
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($karakter['irki']); ?> - <?php echo htmlspecialchars($karakter['sinifi']); ?> (seviye <?php echo $karakter['seviye']; ?>)</h6>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">güç: <?php echo $karakter['guc']; ?> | çeviklik: <?php echo $karakter['ceviklik']; ?> | dayanıklılık: <?php echo $karakter['dayaniklilik']; ?></li>
                        <li class="list-group-item">zeka: <?php echo $karakter['zeka']; ?> | bilgelik: <?php echo $karakter['bilgelik']; ?> | karizma: <?php echo $karakter['karizma']; ?></li>
                    </ul>
                </div>
                <div class="card-footer">
                    <a href="envanter.php?karakter_id=<?php echo $karakter['id']; ?>" class="btn btn-outline-secondary w-100">Envanteri Yönet</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!--yeni karakter ekleme modalı-->
<div class="modal fade" id="yeniKarakterModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Yeni Karakter Oluştur</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="karakterler.php" method="post">
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label">Karakter Adı</label><input type="text" class="form-control" name="karakter_adi" required></div>
                <div class="col-md-6 mb-3"><label class="form-label">Irkı</label><input type="text" class="form-control" name="irki" placeholder="örn: İnsan, Elf" required></div>
                <div class="col-md-6 mb-3"><label class="form-label">Sınıfı</label><input type="text" class="form-control" name="sinifi" placeholder="örn: Savaşçı, Büyücü" required></div>
                <div class="col-md-6 mb-3"><label class="form-label">Seviye</label><input type="number" class="form-control" name="seviye" value="1" required></div>
            </div>
            <hr>
            <p class="text-muted">istatistikler (1-20 arası)</p>
            <div class="row">
                <div class="col-md-4 mb-3"><label for="guc" class="form-label">Güç</label><input type="number" class="form-control" id="guc" name="guc" value="10" required></div>
                <div class="col-md-4 mb-3"><label for="ceviklik" class="form-label">Çeviklik</label><input type="number" class="form-control" id="ceviklik" name="ceviklik" value="10" required></div>
                <div class="col-md-4 mb-3"><label for="dayaniklilik" class="form-label">Dayanıklılık</label><input type="number" class="form-control" id="dayaniklilik" name="dayaniklilik" value="10" required></div>
                <div class="col-md-4 mb-3"><label for="zeka" class="form-label">Zeka</label><input type="number" class="form-control" id="zeka" name="zeka" value="10" required></div>
                <div class="col-md-4 mb-3"><label for="bilgelik" class="form-label">Bilgelik</label><input type="number" class="form-control" id="bilgelik" name="bilgelik" value="10" required></div>
                <div class="col-md-4 mb-3"><label for="karizma" class="form-label">Karizma</label><input type="number" class="form-control" id="karizma" name="karizma" value="10" required></div>
            </div>
        </div>
        <div class="modal-footer">
            <input type="hidden" name="yeni_karakter" value="1">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
            <button type="submit" class="btn btn-primary">Karakteri Kaydet</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>