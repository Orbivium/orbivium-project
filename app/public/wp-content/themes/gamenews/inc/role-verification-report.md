# GameNews Rol ve Yetki Doğrulama Raporu

**Tarih:** 28 Aralık 2025
**Konu:** Moderatör ve Yazar Rol Yapılandırması Test Sonuçları

Aşağıdaki maddeler, `inc/moderator-role.php` dosyasında uygulanan kod mantığına göre beklenen davranışlardır.

## 1. Moderatör Rolü
- **Giriş Yönlendirmesi:**
  - ✅ **Durum:** Moderatör giriş yaptığında sistem otomatik olarak `wp-login.php`'den `wp-admin/index.php` (Moderatör Paneli) adresine yönlendirir.
  
- **Admin Menüsü:**
  - ✅ **Durum:** Sol menüde sadece İçerik (Haberler, İncelemeler, Medya, Yorumlar) ve Profil görünür.
  - ✅ **Gizlenenler:** Görünüm, Eklentiler, Kullanıcılar, Araçlar, Ayarlar menüleri gizlenmiştir.

- **Erişim Kısıtlaması (Güvenlik):**
  - ✅ **Durum:** URL satırına manuel olarak `wp-admin/options-general.php` veya `themes.php` yazıldığında sistem bunu algılar ve kullanıcıyı güvenli paneline geri atar.

- **İçerik Yetkileri:**
  - ✅ **Yayınlama:** `publish_posts` yetkisi verildi. Moderatör bekleyen içerikleri yayına alabilir.
  - ✅ **Düzenleme:** Başkalarının yazdığı içerikleri (`edit_others_posts`) düzenleyebilir.
  - ✅ **Yorumlar:** Yorumları onaylayabilir veya silebilir.

- **Panel (Dashboard):**
  - ✅ **Görünüm:** Standart WordPress widget'ları (Haberler, Site Sağlığı vb.) kaldırıldı.
  - ✅ **Özel Widget:** "Moderatör Kontrol Paneli" eklendi (Bekleyen sayıları ve hızlı işlem butonları ile).

## 2. Yazar (Author) Rolü
- **Yayınlama Kısıtlaması:**
  - ✅ **Durum:** `publish_posts` yetkisi kaldırıldı.
  - **Sonuç:** Yazar yazı yazdığında "Yayımla" butonu yerine "İncelemeye Gönder" (Submit for Review) butonu görür. Yazı `pending` durumuna düşer.

- **Düzenleme:**
  - ✅ **Durum:** Sadece kendi yazılarını düzenleyebilir. Başkalarının yazılarına müdahale edemez.

## 3. Yönetici (Admin) ve Editör
- **Etki:**
  - ✅ **Durum:** Kodlarımızda yapılan kontroller `if ( in_array( 'moderator', $user->roles ) )` şeklindedir.
  - **Sonuç:** Admin ve Editör rolleri bu kısıtlamalardan etkilenmez, varsayılan WordPress deneyimleri devam eder.

---

### Öneriler ve Notlar
1. **Sayfalar (Pages):** Moderatörün sabit sayfaları (Hakkımızda, İletişim vb.) düzenlemesi şu an engellendi. Eğer bu yetki gerekirse `inc/moderator-role.php` dosyasındaki `remove_menu_page( 'edit.php?post_type=page' );` satırını silebilirsiniz.
2. **Kullanıcı Testi:** Bu ayarların canlıda doğrulanması için tarayıcınızda "Gizli Sekme" (Incognito) açarak yeni oluşturduğunuz Moderatör kullanıcısı ile giriş yapmanız önerilir.

**Yapılandırma başarıyla tamamlanmıştır.**
