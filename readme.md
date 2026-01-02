# ORBI — Moderatör ve Yönetici Rehberi

Bu doküman; Orbi web sitesinin yönetimi, içerik girişi, moderasyon süreçleri ve teknik notları hakkında **moderatörler** ve **yöneticiler** için hazırlanmıştır.

---

## İçindekiler
- [1. Yönetim Paneli Sayfaları ve İşlevleri](#1-yönetim-paneli-sayfaları-ve-i̇şlevleri)
- [2. İçerik Oluşturma Kuralları](#2-i̇çerik-oluşturma-kuralları)
- [3. Görsel Boyutları](#3-görsel-boyutları)
- [4. Moderasyon Kuralları](#4-moderasyon-kuralları)
- [5. Teknik Notlar](#5-teknik-notlar)
- [Son Güncelleme](#son-güncelleme)

---

## 1. Yönetim Paneli Sayfaları ve İşlevleri

Aşağıdaki sayfalar WordPress Admin Paneli **(Sol Menü)** üzerinde bulunur.

### 1.1 Aktivite Günlüğü (Yeni)
- **Konum:** `Admin Paneli > Aktivite Günlüğü`
- **Amaç:** Sitedeki son gelişmeleri anlık takip etmenizi sağlar.

**İçerik Yönetimi Tablosu**
- Son eklenen **Haber**, **İnceleme** ve **Videoları** listeler.
- **Yazar**, **Tür** ve **Platform** filtreleri ile raporlama yapılabilir.

**Yorum Yönetimi Tablosu**
- Son gelen yorumları listeler.
- Onay bekleyenleri gösterir.
- Kullanıcı bazlı filtreleme yapılabilir.
- **“Raporu İndir”** ile Excel çıktısı alınabilir.

### 1.2 İçerik Yönetimi
- **Konum:** `Admin Paneli > İçerik Yönetimi`
- **Amaç:** Özel içerik türleri dışındaki genel ayarları ve bazı tema özelleştirmelerini barındırabilir.

### 1.3 Haberler (News)
- **Konum:** `Admin Paneli > Haberler`
- **Amaç:** Kısa ve güncel haber içerikleri.
- **Dikkat:**  
  - **Öne Çıkan Görsel** eklenmelidir.  
  - **Platform** seçimi yapılmalıdır.

### 1.4 İncelemeler (Reviews)
- **Konum:** `Admin Paneli > İncelemeler`
- **Amaç:** Oyun incelemeleri ve detaylı analizler.
- **Özellik:** Puanlama sistemi, artı/eksi listesi ve teknik detaylar için özel alanlar (**meta box**) içerir.

### 1.5 Videolar
- **Konum:** `Admin Paneli > Videolar`
- **Amaç:** Video içerikleri.
- **Kaynak:**  
  - Sunucuya yüklenen **MP4** dosyaları  
  - veya **YouTube embed** linkleri

### 1.6 E-Spor
- **Konum:** `Admin Paneli > E-Spor`
- **Amaç:** Espor turnuvaları, takım haberleri ve maç sonuçları.

### 1.7 Slider Ayarları
- **Konum:** `Admin Paneli > Slider Ayarları`
- **Amaç:** Ana sayfadaki büyük manşet alanını yönetir.
- Buraya eklenen içerikler ana sayfada en üstte büyük olarak görünür.

### 1.8 Yorumlar
- **Konum:** `Admin Paneli > Yorumlar`
- **Amaç:** Tüm site yorumlarının yönetimi.
- İşlemler: onaylama, silme, SPAM işaretleme.

---

## 2. İçerik Oluşturma Kuralları

Bir içerik oluştururken aşağıdaki adımları takip edin.

### 2.1 Başlık ve Metin
- **Başlık:** İlgi çekici, kısa ve net olmalı (**maks. 60–70 karakter**).  
  - **Tümü büyük harf kullanmayın.**
- **Özet (Excerpt):** İçeriğin kısa özeti girilmelidir (kartlarda görünür).
- **İçerik:**  
  - Paragraflara bölünmüş ve okunabilir olmalı  
  - Yapıyı güçlendirmek için **H2 / H3** başlıkları kullanılmalı

### 2.2 Platform ve Kategori Seçimi
- **Platform (zorunlu):** İçeriğin ilgili olduğu platform (PC, PlayStation, Xbox vb.) sağ menüden **mutlaka** seçilmelidir.  
- Bu seçim, içeriğin doğru sayfalarda (ör. PlayStation sayfası) görünmesini sağlar.

### 2.3 Görseller (Önemli)
- Görseller **HD** olmalı ancak dosya boyutu **optimize** edilmelidir.  
  - Önerilen format: **WebP** (tercih), veya sıkıştırılmış **JPG**
- **Öne Çıkan Görsel (Featured Image)** her yazıda **zorunludur**.

---

## 3. Görsel Boyutları

Aşağıdaki boyutlara uymak tasarımın bozulmamasını ve sitenin hızlı açılmasını sağlar.

| Kullanım Alanı | Tavsiye Edilen Boyut (px) | Oran | Açıklama |
|---|---:|:---:|---|
| Slider / Manşet Görseli | 1920×1080 (veya 1600×900) | 16:9 | Ana sayfa üstteki büyük kayan görseller. |
| İçerik Kartı (Kapak) | 800×450 | 16:9 | Liste/arşiv/kategori kartları. |
| İçerik Detay (Hero) | 1200×675 | 16:9 | İçerik sayfası üst kapak görseli. |
| Profil Avatarı | 500×500 | 1:1 | Kare olmalı; sistem otomatik yuvarlar. |
| Platform Logosu (SVG) | Vektörel (SVG) | - | Menü ikonları. `/assets/images/platforms/` |
| Hakkımızda Yan Görsel | 600×800 | 3:4 | Hakkımızda sayfası sağ dikey görsel. |
| Video Kapak (Thumbnail) | 1280×720 | 16:9 | Video içerikleri kapak görseli. |

---

## 4. Moderasyon Kuralları

### 4.1 Yorumlar
- Küfür, hakaret, nefret söylemi içeren yorumlar **direkt silinmelidir (Çöp)**.
- Spoiler içeren yorumlar:  
  - düzenlenip spoiler uyarısı eklenmeli **veya**  
  - onaylanmamalıdır.
- Reklam/SPAM link içeren yorumlar **Spam** olarak işaretlenmelidir (IP engeli için).
- Yapıcı eleştiriler (siteye yönelik olsa bile) **onaylanmalıdır**.
- Toplu kontrol için **Aktivite Günlüğü** kullanılabilir.

### 4.2 Kullanıcılar
- Uygunsuz kullanıcı adı (küfürlü, reklam amaçlı) olan üyeler **engellenmeli veya silinmelidir**.
- Profil fotoğrafında uygunsuz görsel kullananlar **uyarılmalı** veya görselleri **kaldırılmalıdır**.

---

## 5. Teknik Notlar

> Bu bölüm yazılım ekibi içindir.

- **Tema Klasörü:** `/wp-content/themes/gamenews/`
- **Aktivite Log Dosyası:** `/inc/activity-log.php`
- **CSS Stilleri:** `style.css` (ana stil dosyası)
- **Platform İkonları:** `/assets/images/platforms/`  
  - İsimlendirme: `playstation.svg`, `xbox.svg` vb.

---

## Son Güncelleme
**01.01.2026**  
Orbi Yönetim Ekibi
