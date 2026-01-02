# 🛠️ ORBI TEKNİK DEFTER (V2.0 - DETAYLI)

Bu belge, ORBI altyapısının teknik mimarisini, tasarım standartlarını ve kritik kod bloklarını en ince ayrıntısına kadar açıklar.

## 1. TASARIM SİSTEMİ VE GÖRSEL STANDARTLAR

### 📏 Görsel Boyut Standartları (Kritik)
Sitenin görsel bütünlüğünün bozulmaması için aşağıdaki boyutlara sadık kalınmalıdır:

| İçerik Türü | Önerilen Boyut | En-Boy Oranı | Notlar |
| :--- | :--- | :--- | :--- |
| **Ana Sayfa Slider** | 1920x1080 px | 16:9 | Odak noktası merkezde olmalı. |
| **Haber/İnceleme Kartı** | 800x450 px | 16:9 | `object-fit: cover` kullanılır. |
| **Yazı İçi Görseller** | Max Genişlik 1200 px | Değişken | Alt metin (alt tag) eklenmelidir. |
| **Platform Logoları** | 128x128 px | 1:1 | Şeffaf arka plan (SVG önerilir). |
| **Kullanıcı Avatarı** | 256x256 px | 1:1 | Kare yüklenmelidir, sistem yuvarlar. |

---

## 2. DİNAMİK RENK VE TEMA MİMARİSİ

Sitenin en kritik özelliği, bulunulan platforma (PC, PlayStation vb.) göre tüm arayüzün renk değiştirmesidir.

### 🎨 Renk Yönetimi (`functions.php`)
`oyunhaber_dynamic_platform_colors()` fonksiyonu her sayfa yüklendiğinde çalışır:
1.  Sayfanın taksonomisini (Platform) kontrol eder.
2.  İlgili HEX kodunu alır (Örn: PlayStation için `#003791`).
3.  Bu rengi `:root` seviyesinde `--accent-color` değişkenine atar.
4.  Sayfanın arka planına bu rengin `%25` şeffaflığında bir **Radial Gradient** ekler.

### 📌 Kritik CSS Değişkenleri (`style.css`)
```css
:root {
    --bg-primary: #121212;      /* Ana arka plan */
    --accent-color: #ff4757;    /* Değişken vurgu rengi */
    --font-heading: 'Segoe UI'; /* Başlık fontu */
}
```

---

## 3. NAVİGASYON VE ARAYÜZ YAPISI

### 🖥️ Masaüstü Navigasyon
- **Dropdown (Açılır Menü):** Hover (üzerine gelme) durumunda açılır.
- **Hover Bridge:** Menü ile dropdown arasında kopma olmaması için görünmez bir link katmanı (`::after`) eklenmiştir.
- **Dinamik Dropdown:** Seçili platform aktifse dropdown o platformun renginde, değilse koyu gri (`#2d2d2d`) görünür.

### 📱 Mobil Arayüz (App-Like)
- **Top Bar:** Arama ve profil butonlarını içerir.
- **Secondary Nav:** Platformların yatayda kaydırılabilir listesi.
- **Arama Overlay:** Tam ekran açılır, `backdrop-filter: blur(10px)` ile arka planı bulanıklaştırır.

---

## 4. GÜVENLİK VE ERİŞİM KONTROLÜ

Siber güvenlik ve yetkisiz erişim için aşağıdaki önlemler kod seviyesinde alınmıştır:

### 🚫 Admin Paneli Kısıtlaması
`functions.php` içindeki `oyunhaber_security_restrictions()` fonksiyonu:
- **Kimler Girebilir:** Sadece `Administrator` ve `Editor` rollerine sahip kullanıcılar.
- **Kimler Engellenir:** Aboneler (`Subscriber`) ve Misafirler.
- **Sonuç:** Yetkisiz biri `/wp-admin` yazarsa anında Ana Sayfaya yönlendirilir.
- **Admin Bar:** Sadece yetkililere gösterilir, normal üyeler siteyi tertemiz görür.

---

## 5. DATABASE VE ÖZEL TAXONOMY MİMARİSİ

- **Platform (Taxonomy):** `platform` slug'ı ile tanımlıdır. `PC`, `Xbox`, `Playstation`, `Nintendo`, `Mobil`, `Genel` değerlerini alır.
- **Video Meta Box:** Videolu haberler için `_video_url` meta alanı kullanılır.
- **Activity Log:** `inc/activity-log.php` üzerinden tüm kritik admin hareketleri veritabanına kaydedilir.

---
**Teknik Not:** Tema dosyalarında yapılan değişikliklerden sonra `style.css` versiyon numarasını güncelleyerek tarayıcı önbelleğini temizletebilirsiniz.
