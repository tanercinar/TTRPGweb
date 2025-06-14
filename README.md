# TTRPG Karakter ve Envanter Yönetim Sistemi

Bu proje, PHP, MySQL, HTML ve Bootstrap kullanılarak geliştirilmiş web tabanlı bir CRUD (Create, Read, Update, Delete) uygulamasıdır. Proje, Masaüstü Rol Yapma Oyunu (TTRPG) oyuncularının karakterlerini ve bu karakterlere ait envanterleri dijital olarak yönetmelerini sağlar.

Kullanıcılar sisteme kaydolup giriş yapabilir, yeni karakterler oluşturabilir, karakterlerinin istatistiklerini belirleyebilir ve her bir karakter için ayrı ayrı envanter (eşya) takibi yapabilirler.

## Projenin Amacı

Bu uygulama, TTRPG oyunları sırasında kağıt-kalem ile tutulan karakter kağıtlarına dijital bir alternatif sunmayı hedefler. Oyuncular, oluşturdukları karakterlerin tüm detaylarına ve sahip oldukları eşyalara her an, her yerden erişebilirler.

## Kullanılan Teknolojiler

*   **Arka Uç (Backend):** Yalın PHP (Herhangi bir framework kullanılmamıştır)
*   **Veritabanı (Database):** MySQL
*   **Ön Uç (Frontend):** HTML, Bootstrap 5
*   **Oturum Yönetimi:** PHP Sessions
*   **Güvenlik:**
    *   Şifreler `password_hash()` ile hash'lenerek saklanmaktadır.
    *   SQL Injection saldırılarına karşı PDO ve Prepared Statements kullanılmıştır.
    *   Kullanıcı girdileri `htmlspecialchars()` ile ekrana güvenli bir şekilde yazdırılmaktadır.

## Temel Özellikler

-   **Kullanıcı Yönetimi:** Güvenli kayıt olma ve şifreli giriş/çıkış işlemleri.
-   **Karakter Oluşturma (Create):** Kullanıcılar kendilerine ait yeni oyun karakterleri oluşturabilir.
-   **Karakter Listeleme (Read):** Kullanıcılar kendi oluşturdukları tüm karakterleri bir kart yapısında listeleyebilir.
-   **Karakter Silme (Delete):** Kullanıcılar artık oynamadıkları karakterleri silebilir. (Bir karakter silindiğinde, ona ait tüm envanter de otomatik olarak silinir).
-   **Envanter Yönetimi (CRUD):**
    -   Her karaktere özel envantere yeni eşya (silah, zırh, iksir vb.) ekleme.
    -   Mevcut eşyaların bilgilerini (ad, miktar, açıklama) düzenleme.
    -   Envanterden eşya silme.

---

## Ekran Görüntüleri

### 1. Karakter Listeleme Sayfas

<img width="1437" alt="Screenshot 2025-06-14 at 20 19 37" src="https://github.com/user-attachments/assets/d6ac2e13-183e-4416-8a60-29f9cef75112" />



### 2. Envanter Yönetim Sayfası

<img width="1437" alt="Screenshot 2025-06-14 at 20 20 11" src="https://github.com/user-attachments/assets/da105c5d-8216-4df3-b2c1-8355b7d1f810" />



---

## Canlı Demo

Uygulamanın canlı sunucuda çalışan versiyonunu test etmek için aşağıdaki bağlantıyı ziyaret edebilirsiniz. Siteye girerek yeni bir kullanıcı oluşturabilir ve tüm özellikleri deneyebilirsiniz.

  **[Proje Bağlantısı](http://95.130.171.20/~st22360859042/)**

---

## Proje Tanıtım Videosu

Uygulamanın nasıl çalıştığını, kullanıcı kaydından başlayarak karakter ve envanter yönetimine kadar tüm temel işlevlerin gösterildiği kısa tanıtım videosuna aşağıdaki bağlantıdan ulaşabilirsiniz:

▶️ **[Youtube Video Bağlantısı](https://www.youtube.com/watch?v=5G4OJLTmIOg)**
