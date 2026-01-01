<?php
/**
 * Auto Create Pages
 * 
 * Automatically creates necessary pages if they don't exist.
 */

function oyunhaber_auto_create_pages() {
    // Array of pages to create
    $pages = [
        [
            'title' => 'Giriş Yap',
            'slug'  => 'giris-yap',
            'template' => 'page-login.php'
        ],
        [
            'title' => 'Kayıt Ol',
            'slug'  => 'kayit-ol',
            'template' => 'page-register.php'
        ],
        [
            'title' => 'Şifremi Unuttum',
            'slug'  => 'sifremi-unuttum',
            'template' => 'page-forgot-password.php'
        ],
        [
            'title' => 'Hakkımızda',
            'slug'  => 'hakkimizda',
            'template' => 'page-about.php'
        ],
        [
            'title' => 'İletişim',
            'slug'  => 'iletisim',
            'template' => 'page-contact.php'
        ],
        [
            'title' => 'Gizlilik Politikası',
            'slug'  => 'gizlilik-politikasi',
            'content' => '<h2>GİZLİLİK POLİTİKASI</h2>
<p>Bu Gizlilik Politikası, Orbi (“Site”, “Platform”, “Biz”) tarafından işletilen internet sitesi ve bağlı tüm dijital hizmetleri kullanan ziyaretçi ve üyelerin (“Kullanıcı”, “Siz”) kişisel verilerinin toplanması, kullanılması, saklanması ve korunmasına ilişkin esasları açıklamaktadır.</p>
<p>Platformumuzu ziyaret eden veya hizmetlerimizden yararlanan tüm kullanıcılar, bu Gizlilik Politikası’nda belirtilen şartları kabul etmiş sayılır.</p>

<h3>1. Gizliliğe Verdiğimiz Önem</h3>
<p>Orbi, kullanıcı gizliliğini en üst düzeyde önemser. Kişisel verilerinizin güvenliği bizim için yalnızca yasal bir zorunluluk değil, aynı zamanda temel bir etik ilkedir. Bu kapsamda;</p>
<ul>
<li>Kişisel verileriniz hukuka ve dürüstlük kurallarına uygun şekilde işlenir</li>
<li>Amaca uygun, sınırlı ve ölçülü veri işleme yapılır</li>
<li>Verileriniz güncel ve doğru tutulur</li>
<li>Yetkisiz erişimlere karşı korunur</li>
</ul>

<h3>2. Toplanan Kişisel Veriler</h3>
<p>Platformumuzun kullanımı sırasında aşağıdaki kişisel veriler toplanabilir:</p>

<h4>2.1. Kimlik ve İletişim Bilgileri</h4>
<ul>
<li>Ad, soyad</li>
<li>Kullanıcı adı</li>
<li>E-posta adresi</li>
<li>Profil fotoğrafı (isteğe bağlı)</li>
</ul>

<h4>2.2. Hesap ve Kullanım Bilgileri</h4>
<ul>
<li>Giriş/çıkış tarihleri</li>
<li>IP adresi</li>
<li>Tarayıcı ve cihaz bilgileri</li>
<li>Platform üzerindeki etkileşimler (yorumlar, incelemeler, beğeniler vb.)</li>
</ul>

<h4>2.3. İçerik ve Topluluk Verileri</h4>
<ul>
<li>Paylaşılan yorumlar</li>
<li>Oyun incelemeleri</li>
<li>Forum mesajları</li>
<li>Topluluk etkileşimleri</li>
</ul>

<h4>2.4. Çerez (Cookie) ve Benzeri Teknolojiler</h4>
<ul>
<li>Oturum çerezleri</li>
<li>Performans ve analiz çerezleri</li>
<li>Tercih ve kişiselleştirme çerezleri</li>
</ul>

<h3>3. Kişisel Verilerin Toplanma Yöntemleri</h3>
<p>Kişisel verileriniz;</p>
<ul>
<li>Siteye üye olmanız</li>
<li>Profil oluşturmanız</li>
<li>Yorum, inceleme veya içerik paylaşmanız</li>
<li>Siteyi ziyaret etmeniz</li>
<li>Bizimle iletişime geçmeniz</li>
</ul>
<p>gibi yollarla otomatik veya otomatik olmayan yöntemlerle toplanabilir.</p>

<h3>4. Kişisel Verilerin İşlenme Amaçları</h3>
<p>Toplanan kişisel veriler aşağıdaki amaçlarla işlenir:</p>
<ul>
<li>Platform hizmetlerinin sunulması ve geliştirilmesi</li>
<li>Kullanıcı hesaplarının oluşturulması ve yönetilmesi</li>
<li>Topluluk etkileşimlerinin sağlanması</li>
<li>İçeriklerin moderasyonu</li>
<li>Güvenliğin sağlanması ve kötüye kullanımın önlenmesi</li>
<li>Hukuki yükümlülüklerin yerine getirilmesi</li>
<li>Kullanıcı deneyiminin iyileştirilmesi</li>
<li>Teknik destek ve iletişim süreçlerinin yürütülmesi</li>
</ul>

<h3>5. Kişisel Verilerin Saklanması ve Korunması</h3>
<p>Kişisel verileriniz;</p>
<ul>
<li>Güvenli sunucularda</li>
<li>Yetkisiz erişime karşı korumalı sistemlerde</li>
<li>Güncel teknik ve idari güvenlik önlemleri alınarak</li>
</ul>
<p>saklanır.</p>
<p>Veriler, işlenme amacının ortadan kalkması veya yasal saklama süresinin sona ermesi halinde silinir, yok edilir veya anonim hale getirilir.</p>

<h3>6. Kişisel Verilerin Paylaşılması</h3>
<p>Kişisel verileriniz;</p>
<ul>
<li>Açık rızanız olmaksızın</li>
<li>Yasal zorunluluklar dışında</li>
</ul>
<p>üçüncü kişilerle paylaşılmaz.</p>
<p>Ancak aşağıdaki durumlarda paylaşım yapılabilir:</p>
<ul>
<li>Yasal mercilerin talebi</li>
<li>Hukuki yükümlülüklerin yerine getirilmesi</li>
<li>Teknik hizmet sağlayıcılarla (sunucu, güvenlik, analiz hizmetleri) sınırlı ve kontrollü paylaşım</li>
</ul>
<p>Bu paylaşımlar yalnızca hizmetin gerektirdiği ölçüde yapılır.</p>

<h3>7. Çerezler (Cookies)</h3>
<p>Platformumuz, kullanıcı deneyimini geliştirmek amacıyla çerezler kullanır.</p>
<p>Çerezler;</p>
<ul>
<li>Oturumun devamlılığını sağlamak</li>
<li>Tercihleri hatırlamak</li>
<li>Site performansını analiz etmek</li>
</ul>
<p>amacıyla kullanılır.</p>
<p>Tarayıcı ayarlarınızdan çerezleri reddedebilir veya silebilirsiniz. Ancak bu durumda sitenin bazı özellikleri düzgün çalışmayabilir.</p>

<h3>8. Kullanıcı Hakları</h3>
<p>Kullanıcılar, yürürlükteki mevzuat kapsamında aşağıdaki haklara sahiptir:</p>
<ul>
<li>Kişisel verilerinin işlenip işlenmediğini öğrenme</li>
<li>İşlenen verilere ilişkin bilgi talep etme</li>
<li>Yanlış veya eksik verilerin düzeltilmesini isteme</li>
<li>Kişisel verilerin silinmesini veya yok edilmesini talep etme</li>
<li>Veri işlemeye itiraz etme</li>
<li>Açık rızayı geri çekme</li>
</ul>
<p>Bu haklarınızı kullanmak için bizimle iletişime geçebilirsiniz.</p>

<h3>9. Üçüncü Taraf Bağlantılar</h3>
<p>Platformumuzda üçüncü taraf sitelere ait bağlantılar yer alabilir. Bu sitelerin gizlilik uygulamalarından Orbi sorumlu değildir. İlgili sitelerin kendi gizlilik politikalarını incelemenizi öneririz.</p>

<h3>10. Gizlilik Politikasında Değişiklikler</h3>
<p>Bu Gizlilik Politikası, gerekli görüldüğü hallerde güncellenebilir. Güncellenmiş politika site üzerinde yayımlandığı tarihte yürürlüğe girer.</p>
<p>Kullanıcıların, Gizlilik Politikası’nı düzenli olarak incelemesi tavsiye edilir.</p>

<h3>11. İletişim</h3>
<p>Gizlilik Politikası ile ilgili her türlü soru, talep veya başvuru için bizimle aşağıdaki kanallar üzerinden iletişime geçebilirsiniz:</p>
<p>E-posta: iletisim@orbi.local<br>
Site: orbi.local</p>

<h3>Son Not</h3>
<p>Bu platformu kullanarak, Gizlilik Politikası’nı okuduğunuzu, anladığınızı ve kabul ettiğinizi beyan etmiş olursunuz.</p>'
        ],
        [
            'title' => 'Kullanım Şartları',
            'slug'  => 'kullanim-sartlari',
            'content' => '<h2>KULLANIM ŞARTLARI</h2>
<p>Bu Kullanım Şartları, Orbi (“Site”, “Platform”, “biz”, “bizim”) tarafından sunulan tüm hizmetlerin kullanımına ilişkin kuralları belirler. Siteyi ziyaret eden, üye olan veya herhangi bir şekilde hizmetlerden yararlanan tüm kullanıcılar (“Kullanıcı”, “siz”), bu şartları okumuş, anlamış ve kabul etmiş sayılır.</p>
<p>Orbi, oyunlar ve oyun platformları hakkında içerik, inceleme, haber ve topluluk etkileşimi sunan bir dijital platformdur. Sunulan hizmetler zaman içerisinde geliştirilebilir, değiştirilebilir, geçici veya kalıcı olarak durdurulabilir.</p>

<h3>Üyelik ve Hesap Güvenliği</h3>
<p>Siteye üye olurken verilen bilgilerin doğru ve güncel olması kullanıcının sorumluluğundadır. Kullanıcı, hesap bilgilerini ve şifresini gizli tutmakla yükümlüdür. Hesap üzerinden gerçekleştirilen tüm işlemler kullanıcıya aittir. Yetkisiz kullanım şüphesi durumunda kullanıcı, Orbi’yi derhal bilgilendirmelidir.</p>

<h3>Kullanıcı Yükümlülükleri</h3>
<p>Kullanıcılar, platformu kullanırken yürürlükteki mevzuata, genel ahlak kurallarına ve bu Kullanım Şartları’na uygun davranmayı kabul eder. Başka kullanıcılara yönelik hakaret, tehdit, nefret söylemi, taciz veya rahatsız edici davranışlarda bulunmak yasaktır. Yanıltıcı bilgi, spam, reklam veya kötü niyetli içerik paylaşımı yapılamaz. Başkalarına ait kişisel verilerin izinsiz şekilde paylaşılması ve platformun teknik altyapısına zarar verecek girişimlerde bulunulması kesinlikle yasaktır.</p>

<h3>İçerik Paylaşımı ve Sorumluluk</h3>
<p>Kullanıcılar tarafından paylaşılan yorumlar, incelemeler ve diğer tüm içeriklerin hukuki ve cezai sorumluluğu içeriği paylaşan kullanıcıya aittir. Orbi, kullanıcı içeriklerini önceden denetlemekle yükümlü değildir. Ancak, yürürlükteki mevzuata veya bu şartlara aykırı olduğu tespit edilen içerikleri kaldırma, düzenleme veya erişimi kısıtlama hakkını saklı tutar. Gerekli görülmesi halinde kullanıcı hesapları geçici veya kalıcı olarak askıya alınabilir.</p>

<h3>Fikri Mülkiyet Hakları</h3>
<p>Platformda yer alan tasarım, yazılım, logo, metin, görsel ve diğer tüm içerikler (kullanıcılar tarafından oluşturulan içerikler hariç) Orbi’ye veya lisans sahiplerine aittir. Bu içerikler izinsiz olarak kopyalanamaz, çoğaltılamaz, dağıtılamaz veya ticari amaçla kullanılamaz. Kullanıcılar, paylaştıkları içeriklerin kendilerine ait olduğunu veya paylaşım için gerekli haklara sahip olduklarını kabul eder.</p>

<h3>Üçüncü Taraf Bağlantılar</h3>
<p>Platform, üçüncü taraf web sitelerine veya hizmetlere yönlendiren bağlantılar içerebilir. Bu bağlantılar aracılığıyla erişilen sitelerin içeriklerinden, hizmetlerinden veya gizlilik uygulamalarından Orbi sorumlu değildir.</p>

<h3>Hizmetin Kesintiye Uğraması</h3>
<p>Teknik bakım, güncelleme, altyapı çalışmaları veya mücbir sebepler nedeniyle hizmetlerde geçici kesintiler yaşanabilir. Orbi, bu kesintilerden doğabilecek doğrudan veya dolaylı zararlardan sorumlu tutulamaz.</p>

<h3>Sorumluluğun Sınırlandırılması</h3>
<p>Orbi, platformda yer alan içeriklerin doğruluğu, güncelliği veya eksiksizliği konusunda herhangi bir garanti vermez. Platformun kullanımından doğabilecek zararlar için, yürürlükteki mevzuatın izin verdiği ölçüde sorumluluk kabul edilmez.</p>

<h3>Şartlarda Değişiklik</h3>
<p>Orbi, bu Kullanım Şartları’nı dilediği zaman güncelleme hakkını saklı tutar. Güncellenmiş şartlar sitede yayımlandığı tarihte yürürlüğe girer. Platformun kullanılmaya devam edilmesi, güncellenmiş şartların kabul edildiği anlamına gelir.</p>

<h3>Üyeliğin Sona Erdirilmesi</h3>
<p>Kullanıcı, dilediği zaman üyeliğini sonlandırabilir. Orbi, Kullanım Şartları’na aykırı davranışların tespiti halinde kullanıcı hesabını askıya alma veya kalıcı olarak kapatma hakkına sahiptir.</p>

<h3>Uygulanacak Hukuk ve Yetki</h3>
<p>Bu Kullanım Şartları, Türkiye Cumhuriyeti hukukuna tabidir. Taraflar arasında doğabilecek uyuşmazlıklarda Türkiye Cumhuriyeti mahkemeleri ve icra daireleri yetkilidir.</p>

<h3>İletişim</h3>
<p>Kullanım Şartları ile ilgili her türlü soru, görüş veya talep için bizimle iletişime geçebilirsiniz. İletişim bilgileri site üzerinde yer almaktadır.</p>'
        ]
    ];

    foreach ($pages as $page) {
        $existing = get_page_by_path($page['slug']);
        
        // If page doesn't exist, create it
        if ( ! $existing ) {
            $page_args = array(
                'post_title'   => $page['title'],
                'post_name'    => $page['slug'],
                'post_content' => isset($page['content']) ? $page['content'] : '',
                'post_status'  => 'publish',
                'post_type'    => 'page',
                'post_author'  => 1
            );
            
            $page_id = wp_insert_post( $page_args );

            if ( $page_id && ! is_wp_error( $page_id ) ) {
                // Set page template if specified
                if ( isset( $page['template'] ) ) {
                    update_post_meta( $page_id, '_wp_page_template', $page['template'] );
                }
            }
        } else {
            // If page exists but doesn't have the template set (and needs one), update it
            if ( isset( $page['template'] ) ) {
                $current_template = get_post_meta( $existing->ID, '_wp_page_template', true );
                if ( $current_template != $page['template'] ) {
                    update_post_meta( $existing->ID, '_wp_page_template', $page['template'] );
                }
            }
            
            // SPECIAL: Enable content update for Privacy Policy OR Terms this time
            if ( ($page['slug'] === 'gizlilik-politikasi' || $page['slug'] === 'kullanim-sartlari') && isset($page['content']) ) {
                 $updated_post = array(
                    'ID'           => $existing->ID,
                    'post_content' => $page['content']
                );
                wp_update_post( $updated_post );
            }
        }
    }
}
add_action( 'init', 'oyunhaber_auto_create_pages' );
