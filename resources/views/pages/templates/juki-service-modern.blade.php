@extends('layouts.frontend')

@php
    use App\Models\SystemSetting;

    $siteName = SystemSetting::get('site_name', config('app.name', 'Juki'));
    $waNumber = SystemSetting::get('contact_inquiry_whatsapp', '6282213028718');
    $slug = trim($page->slug, '/');
    $isSeo = str_starts_with($slug, 'jasa/seo');
    $isAutomation = str_starts_with($slug, 'jasa/otomasi');
    $isAi = str_starts_with($slug, 'jasa/ai');
    $service = match (true) {
        $isSeo => [
            'eyebrow' => 'Search authority · local visibility · technical SEO',
            'label' => 'SEO & Search Authority',
            'headline' => 'Jasa SEO di Solo untuk bisnis yang ingin ditemukan dan dipilih.',
            'lead' => 'SEO teknis, konten berbasis entitas, dan Google Business Profile yang bekerja sebagai satu sistem untuk pasar Solo dan Solo Raya.',
            'promise' => 'Dari “tidak terlihat” menjadi pilihan yang masuk akal ketika pelanggan mencari.',
            'accent' => 'gold',
            'features' => [
                ['title' => 'Technical clarity', 'text' => 'Perbaiki crawlability, indexability, canonical, performa, dan struktur halaman agar Google memahami aset penting Anda.'],
                ['title' => 'Intent-led content', 'text' => 'Petakan keyword, entity, dan search intent untuk menarik pengunjung yang memiliki kebutuhan nyata.'],
                ['title' => 'Local advantage', 'text' => 'Perkuat sinyal lokasi, Google Business Profile, sitasi, dan halaman layanan untuk pasar Solo dan sekitarnya.'],
            ],
            'signals' => ['Technical SEO audit', 'Entity & topical silo', 'Google Business Profile', 'Schema JSON-LD', 'Internal linking', 'Search Console insight'],
            'steps' => ['Audit kondisi saat ini', 'Susun prioritas dan intent', 'Perbaiki halaman serta konten', 'Ukur, pelajari, dan iterasikan'],
            'related' => [
                ['/jasa/website', 'Bangun website yang SEO-ready'],
                ['/jasa/seo-lokal', 'Fokus SEO lokal'],
                ['/jasa/ai', 'Percepat produksi konten dengan AI'],
            ],
            'faqs' => [
                ['Apakah SEO menjamin ranking nomor satu?', 'Tidak ada jaminan ranking yang bertanggung jawab. Fokus kami adalah membangun relevansi, kualitas, dan fondasi teknis yang meningkatkan peluang visibilitas secara berkelanjutan.'],
                ['Apakah SEO bisa untuk bisnis lokal di Solo?', 'Bisa. Strategi dapat mencakup Google Business Profile, halaman area layanan, entity lokasi, ulasan, dan konten yang relevan dengan pencarian lokal.'],
            ],
        ],
        $isAutomation => [
            'eyebrow' => 'Workflow design · n8n · API integration',
            'label' => 'Business Automation',
            'headline' => 'Jasa otomasi bisnis di Solo untuk mengurangi kerja manual dan mempercepat respons.',
            'lead' => 'Hubungkan website, CRM, spreadsheet, WhatsApp, email, dan API menjadi alur kerja yang rapi untuk bisnis di Solo dan sekitarnya.',
            'promise' => 'Lebih sedikit copy-paste. Lebih banyak waktu untuk melayani pelanggan dan menutup penjualan.',
            'accent' => 'amber',
            'features' => [
                ['title' => 'Lead routing', 'text' => 'Formulir baru masuk ke database, CRM, dan notifikasi tim tanpa input ulang atau jeda komunikasi.'],
                ['title' => 'System bridge', 'text' => 'Satukan data website, spreadsheet, payment, CRM, dan aplikasi internal melalui API yang terdokumentasi.'],
                ['title' => 'Reliable workflows', 'text' => 'Alur memiliki validasi, log, retry, dan notifikasi error agar otomasi tidak menjadi kotak hitam.'],
            ],
            'signals' => ['n8n self-hosted', 'REST API & webhook', 'WhatsApp notification', 'CRM sync', 'Spreadsheet automation', 'Error monitoring'],
            'steps' => ['Petakan proses manual', 'Pilih titik otomasi', 'Bangun dan uji workflow', 'Serahkan dokumentasi dan kontrol'],
            'related' => [
                ['/jasa/website', 'Hubungkan dari website'],
                ['/jasa/otomasi/n8n', 'Implementasi n8n'],
                ['/jasa/ai', 'Tambahkan AI di alur kerja'],
            ],
            'faqs' => [
                ['Apakah otomasi menggantikan tim?', 'Tidak. Otomasi menghapus pekerjaan repetitif agar tim dapat fokus pada keputusan, pelanggan, dan pekerjaan bernilai tinggi.'],
                ['Platform apa yang dapat dihubungkan?', 'Tergantung kebutuhan. Website, database, spreadsheet, CRM, email, WhatsApp, payment, REST API, dan webhook dapat dipetakan dalam satu workflow.'],
            ],
        ],
        $isAi => [
            'eyebrow' => 'Applied AI · knowledge systems · human review',
            'label' => 'Applied AI for Business',
            'headline' => 'Jasa integrasi AI di Solo untuk bekerja lebih cepat tanpa kehilangan kendali.',
            'lead' => 'Rancang sistem AI yang memahami konteks bisnis, mempercepat riset dan pekerjaan tim, serta tetap memiliki validasi manusia untuk bisnis Solo.',
            'promise' => 'AI yang dipasang di tempat yang tepat—bukan sekadar chatbot yang terlihat canggih.',
            'accent' => 'violet',
            'features' => [
                ['title' => 'Knowledge assistant', 'text' => 'Bantu tim menemukan SOP, dokumen, katalog, dan pengetahuan internal tanpa mencari file satu per satu.'],
                ['title' => 'Content intelligence', 'text' => 'Gunakan entity, keyword, dan search intent untuk menghasilkan brief serta draft konten yang lebih terarah.'],
                ['title' => 'Human-in-the-loop', 'text' => 'Output AI melewati validasi, batasan sumber, dan kontrol editorial agar akurasi serta reputasi bisnis tetap terjaga.'],
            ],
            'signals' => ['Knowledge base', 'AI content engine', 'Entity research', 'Semantic gap audit', 'Chatbot internal', 'Human review'],
            'steps' => ['Pilih masalah yang tepat', 'Siapkan data dan batasan', 'Bangun prototype terukur', 'Validasi lalu integrasikan'],
            'related' => [
                ['/jasa/website', 'Siapkan fondasi website'],
                ['/jasa/ai/content-engine', 'AI Content Engine'],
                ['/jasa/otomasi', 'Otomatiskan tindak lanjut'],
            ],
            'faqs' => [
                ['Apakah data bisnis aman digunakan?', 'Rancangan sistem mengikuti kebutuhan akses, sumber data, dan batasan penggunaan. Data tidak seharusnya dibuka ke model atau pengguna yang tidak berwenang.'],
                ['Apakah AI menggantikan penilaian manusia?', 'Tidak. Sistem dirancang untuk mempercepat pekerjaan dan membantu keputusan, sementara validasi penting tetap berada di tangan manusia.'],
            ],
        ],
        default => [
            'eyebrow' => 'Digital service · strategy · execution',
            'label' => $page->title,
            'headline' => $page->title,
            'lead' => $page->meta_description ?: 'Solusi digital yang dirancang sesuai kebutuhan bisnis Anda.',
            'promise' => 'Solusi yang jelas, implementasi yang terukur, dan ruang untuk bertumbuh.',
            'accent' => 'gold',
            'features' => [
                ['title' => 'Strategi yang relevan', 'text' => 'Kami mulai dari kebutuhan layanan ini, target pelanggan, dan hasil yang ingin dicapai—bukan dari template yang dipaksakan.'],
                ['title' => 'Experience yang meyakinkan', 'text' => 'Struktur, copy, visual, dan CTA disusun agar pengunjung memahami nilai layanan lalu tahu langkah berikutnya.'],
                ['title' => 'Fondasi yang dapat tumbuh', 'text' => 'Layanan ini dapat dihubungkan dengan website, SEO, otomasi, dan AI ketika kebutuhan bisnis Anda berkembang.'],
            ],
            'signals' => ['Business positioning', 'Responsive experience', 'SEO-ready structure', 'Clear conversion path', 'Direct support'],
            'steps' => ['Pahami kebutuhan', 'Susun solusi', 'Implementasikan sistem', 'Evaluasi dan kembangkan'],
            'related' => [
                ['/jasa/website', 'Website profesional'],
                ['/jasa/seo', 'SEO dan search authority'],
                ['/jasa/otomasi', 'Otomasi alur kerja'],
            ],
            'faqs' => [
                ['Bagaimana memulai layanan ini?', 'Kirimkan kebutuhan, kondisi saat ini, target pelanggan, dan hasil yang ingin dicapai. Kami bantu menentukan scope yang paling relevan.'],
                ['Apakah layanan dapat digabungkan?', 'Bisa. Website, SEO, otomasi, dan AI dapat dirancang sebagai ekosistem bertahap sesuai prioritas dan kemampuan bisnis.'],
            ],
        ],
    };
    $specific = match ($slug) {
        'jasa/website/company-profile' => [
            'label' => 'Company Profile & Corporate Website',
            'eyebrow' => 'B2B credibility · corporate profile · trust architecture',
            'headline' => 'Jadikan website perusahaan sebagai aset kepercayaan sebelum meeting pertama.',
            'lead' => 'Profil perusahaan yang rapi untuk menjelaskan kapabilitas, solusi, pengalaman, dan alasan calon klien memilih Anda.',
            'promise' => 'Terlihat siap dipercaya oleh klien, partner, dan tim procurement.',
            'features' => [
                ['title' => 'Corporate narrative', 'text' => 'Susun cerita perusahaan, layanan, nilai, dan bukti kerja dalam urutan yang mudah dipahami stakeholder.'],
                ['title' => 'Proof architecture', 'text' => 'Tampilkan portofolio, sertifikasi, klien, tim, dan proses tender tanpa membuat halaman terasa penuh.'],
                ['title' => 'Lead-ready contact', 'text' => 'Arahkan pengunjung B2B ke brief proyek, WhatsApp, atau request quotation dengan konteks yang jelas.'],
            ],
            'signals' => ['Corporate profile', 'Service architecture', 'Portfolio showcase', 'Trust signals', 'Request quotation', 'SEO-ready pages'],
            'related' => [['/jasa/website', 'Website profesional'], ['/jasa/seo', 'SEO untuk visibilitas'], ['/jasa/website/landing-page', 'Landing page kampanye']],
        ],
        'jasa/website/landing-page' => [
            'label' => 'Conversion Landing Page',
            'eyebrow' => 'Campaign page · focused CTA · measurable conversion',
            'headline' => 'Ubah klik iklan menjadi percakapan yang lebih berkualitas.',
            'lead' => 'Landing page cepat dengan satu tujuan utama, pesan yang tajam, bukti yang relevan, dan integrasi tracking untuk kampanye Meta maupun Google Ads.',
            'promise' => 'Satu halaman. Satu pesan. Satu langkah yang mudah dilakukan.',
            'features' => [
                ['title' => 'Single-minded message', 'text' => 'Headline dan offer disusun mengikuti intent audiens dari iklan sampai CTA terakhir.'],
                ['title' => 'Frictionless action', 'text' => 'WhatsApp direct, formulir, kalender, atau checkout ditempatkan pada momen keputusan yang tepat.'],
                ['title' => 'Campaign measurement', 'text' => 'Siap dihubungkan dengan Meta Pixel, Google Tag, event tracking, dan evaluasi funnel.'],
            ],
            'signals' => ['AIDA structure', 'Meta Pixel', 'Google Tag', 'WhatsApp CTA', 'Fast loading', 'Conversion tracking'],
            'related' => [['/jasa/website', 'Website utama'], ['/jasa/seo', 'SEO organik'], ['/jasa/otomasi', 'Otomasi follow-up lead']],
        ],
        'jasa/website/toko-online' => [
            'label' => 'E-commerce & Online Store',
            'eyebrow' => 'Product discovery · checkout flow · owned commerce',
            'headline' => 'Bangun toko online yang membuat produk lebih mudah ditemukan dan dibeli.',
            'lead' => 'Katalog produk, pencarian, keranjang, order WhatsApp, pembayaran, dan pengelolaan konten dalam pengalaman belanja milik Anda sendiri.',
            'promise' => 'Jualan lebih mandiri tanpa sepenuhnya bergantung pada marketplace.',
            'features' => [
                ['title' => 'Product discovery', 'text' => 'Kategori, filter, pencarian, dan halaman produk membantu pembeli menemukan pilihan yang tepat.'],
                ['title' => 'Checkout that fits', 'text' => 'Pilih alur checkout, order WhatsApp, pembayaran, atau kalkulasi ongkir sesuai operasional bisnis.'],
                ['title' => 'Commerce foundation', 'text' => 'Struktur dapat dikembangkan menuju promo, CRM, analitik, katalog B2B, dan integrasi stok.'],
            ],
            'signals' => ['Product catalog', 'Cart & checkout', 'WhatsApp order', 'Payment gateway', 'Shipping calculation', 'Product SEO'],
            'related' => [['/jasa/website', 'Website profesional'], ['/jasa/otomasi', 'Sinkronisasi order'], ['/jasa/seo', 'SEO produk dan kategori']],
        ],
        'jasa/website/wordpress' => [
            'label' => 'WordPress Development',
            'eyebrow' => 'Lightweight CMS · custom theme · editorial control',
            'headline' => 'WordPress yang ringan, aman, dan tetap terasa dibuat khusus untuk brand Anda.',
            'lead' => 'Tema kustom, struktur konten yang mudah dikelola, dan fondasi performa tanpa tumpukan plugin yang membuat website lambat.',
            'promise' => 'Tim Anda dapat mengelola konten tanpa mengorbankan pengalaman pengguna.',
            'features' => [
                ['title' => 'Custom, not crowded', 'text' => 'Tampilan dan blok konten disesuaikan dengan identitas brand, bukan sekadar mengganti warna template.'],
                ['title' => 'Performance first', 'text' => 'Struktur aset, gambar, caching, dan plugin dipilih untuk menjaga Core Web Vitals tetap sehat.'],
                ['title' => 'Editorial freedom', 'text' => 'Admin dapat memperbarui halaman, artikel, dan media dengan alur yang jelas serta aman.'],
            ],
            'signals' => ['Custom theme', 'Core Web Vitals', 'Plugin audit', 'Security hardening', 'Content blocks', 'Backup strategy'],
            'related' => [['/jasa/website', 'Website custom'], ['/jasa/seo', 'SEO WordPress'], ['/jasa/maintenance-website', 'Maintenance rutin']],
        ],
        'jasa/aplikasi-web' => [
            'label' => 'Custom Web Application',
            'eyebrow' => 'Laravel · CodeIgniter · dashboard · business systems',
            'headline' => 'Bangun aplikasi web yang mengikuti proses bisnis Anda, bukan sebaliknya.',
            'lead' => 'Dashboard, sistem informasi, portal internal, dan integrasi REST API untuk mengurangi proses manual serta membuat data lebih mudah digunakan.',
            'promise' => 'Proses kerja yang lebih konsisten, terlihat, dan siap dikembangkan.',
            'features' => [
                ['title' => 'Workflow modeling', 'text' => 'Petakan role, status, approval, data, dan laporan sebelum fitur dikembangkan.'],
                ['title' => 'Scalable foundation', 'text' => 'Laravel, CodeIgniter, database, dan REST API dipilih berdasarkan kebutuhan dan umur sistem.'],
                ['title' => 'Operational clarity', 'text' => 'Dashboard dan permission membantu tim melihat apa yang terjadi serta mengambil tindakan lebih cepat.'],
            ],
            'signals' => ['Laravel & CodeIgniter', 'Role permissions', 'REST API', 'Database design', 'Operational dashboard', 'Audit trail'],
            'related' => [['/jasa/website', 'Website sebagai pintu masuk'], ['/jasa/otomasi', 'Integrasi workflow'], ['/jasa/ai', 'AI untuk knowledge work']],
        ],
        'jasa/maintenance-website' => [
            'label' => 'Website Maintenance & Care',
            'eyebrow' => 'Monitoring · backup · security · continuity',
            'headline' => 'Jaga website tetap sehat setelah hari peluncuran.',
            'lead' => 'Maintenance rutin untuk update keamanan, backup, uptime, performa, dan perbaikan kecil sebelum masalah berubah menjadi kehilangan bisnis.',
            'promise' => 'Website bukan hanya live—website tetap terawat dan siap melayani.',
            'features' => [
                ['title' => 'Preventive care', 'text' => 'Update inti, plugin, dependency, dan konfigurasi dilakukan dengan checklist serta perhatian pada kompatibilitas.'],
                ['title' => 'Recovery confidence', 'text' => 'Backup terjadwal dan prosedur pemulihan membantu mengurangi risiko saat terjadi kesalahan atau serangan.'],
                ['title' => 'Performance watch', 'text' => 'Pantau uptime, error, kecepatan, dan gejala penurunan performa dari waktu ke waktu.'],
            ],
            'signals' => ['Scheduled backup', 'Security updates', 'Uptime monitoring', 'Performance check', 'Bug fixing', 'Monthly report'],
            'related' => [['/jasa/perbaikan-website', 'Perbaikan darurat'], ['/jasa/website', 'Redesign website'], ['/jasa/seo', 'Health check SEO']],
        ],
        'jasa/perbaikan-website' => [
            'label' => 'Website Rescue & Repair',
            'eyebrow' => 'Error recovery · malware cleanup · performance rescue',
            'headline' => 'Pulihkan website error sebelum masalahnya meluas.',
            'lead' => 'Bantuan untuk error 500, white screen, malware, database bermasalah, redirect aneh, dan penurunan performa yang menghambat bisnis.',
            'promise' => 'Diagnosis yang tenang, perbaikan terarah, dan langkah pencegahan setelah pulih.',
            'features' => [
                ['title' => 'Find the cause', 'text' => 'Log, server, database, dependency, dan perubahan terakhir ditelusuri sebelum perbaikan dilakukan.'],
                ['title' => 'Restore safely', 'text' => 'Prioritaskan pemulihan akses dan data, lalu bersihkan akar masalah dengan langkah yang dapat dijelaskan.'],
                ['title' => 'Prevent repeat incidents', 'text' => 'Setelah pulih, susun backup, update, monitoring, dan hardening yang sesuai kondisi website.'],
            ],
            'signals' => ['Error 500', 'White screen', 'Malware cleanup', 'Database recovery', 'Speed troubleshooting', 'Security hardening'],
            'related' => [['/jasa/maintenance-website', 'Maintenance berkala'], ['/jasa/website', 'Bangun ulang website'], ['/contact', 'Hubungi untuk diagnosis']],
        ],
        'jasa/website/solo' => [
            'label' => 'Web Developer Solo & Surakarta',
            'eyebrow' => 'Local partner · Solo Raya · direct communication',
            'headline' => 'Partner web developer di Solo yang memahami bisnis lokal dan kebutuhan digital nyata.',
            'lead' => 'Pembuatan website untuk UMKM, perusahaan, institusi, dan profesional di Solo Raya dengan komunikasi langsung dan solusi yang tidak berlebihan.',
            'promise' => 'Lebih mudah berdiskusi, lebih jelas menentukan prioritas, lebih dekat saat implementasi.',
            'features' => [
                ['title' => 'Local context', 'text' => 'Bahasa, area layanan, karakter pelanggan, dan kebutuhan bisnis Solo menjadi bagian dari strategi halaman.'],
                ['title' => 'Direct collaboration', 'text' => 'Diskusikan ide, scope, dan revisi dengan alur komunikasi yang ringkas dan mudah diikuti.'],
                ['title' => 'Digital foundation', 'text' => 'Mulai dari company profile, landing page, toko online, sampai aplikasi yang bisa berkembang.'],
            ],
            'signals' => ['Solo Raya', 'UMKM & corporate', 'Face-to-face option', 'WhatsApp support', 'Local SEO', 'Flexible scope'],
            'related' => [['/jasa/website', 'Pembuatan website'], ['/jasa/seo-lokal', 'SEO lokal Solo'], ['/contact', 'Jadwalkan konsultasi']],
        ],
        'jasa/seo-lokal' => [
            'label' => 'Local SEO & Google Business Profile',
            'eyebrow' => 'Google Maps · local intent · reputation signals',
            'headline' => 'Muncul ketika pelanggan di sekitar Anda sedang mencari solusi.',
            'lead' => 'Optimasi Google Business Profile, halaman lokasi, sitasi NAP, ulasan, dan sinyal relevansi untuk memperkuat visibilitas lokal.',
            'promise' => 'Pencarian lokal yang lebih relevan, bukan sekadar traffic yang jauh dari lokasi bisnis.',
            'features' => [
                ['title' => 'Profile precision', 'text' => 'Kategori, layanan, deskripsi, media, dan atribut GBP disusun agar konsisten dengan bisnis nyata.'],
                ['title' => 'Location relevance', 'text' => 'Bangun hubungan antara area layanan, halaman website, entity lokasi, dan kebutuhan pencari.'],
                ['title' => 'Review momentum', 'text' => 'Rancang alur meminta, merespons, dan memanfaatkan ulasan secara natural serta berkelanjutan.'],
            ],
            'signals' => ['Google Business Profile', 'Google Maps', 'NAP citations', 'Review strategy', 'Local schema', 'Area pages'],
            'related' => [['/jasa/seo', 'SEO menyeluruh'], ['/jasa/google-maps-embed', 'Google Maps embed'], ['/jasa/website/solo', 'Web developer Solo']],
        ],
        'jasa/google-maps-embed' => [
            'label' => 'Google Maps Embed & Geo Signals',
            'eyebrow' => 'Geographic relevance · map integration · local entity',
            'headline' => 'Bantu Google dan pelanggan memahami di mana bisnis Anda berada.',
            'lead' => 'Integrasi peta, data lokasi, geo-schema, dan konteks halaman yang membantu pengalaman pengguna sekaligus memperjelas entitas lokal.',
            'promise' => 'Lokasi bukan hanya pin—lokasi menjadi bagian dari pengalaman dan kepercayaan.',
            'features' => [
                ['title' => 'Useful map experience', 'text' => 'Peta ditempatkan pada momen yang membantu pelanggan menemukan, mengunjungi, atau menghubungi bisnis.'],
                ['title' => 'Consistent local data', 'text' => 'Nama, alamat, telepon, jam layanan, dan konteks area dijaga konsisten di aset digital utama.'],
                ['title' => 'Geo-aware structure', 'text' => 'Schema dan halaman layanan mendukung pemahaman mesin pencari tanpa klaim lokasi yang berlebihan.'],
            ],
            'signals' => ['Google Maps embed', 'Geo-schema', 'NAP consistency', 'Location UX', 'Local entity', 'Directions CTA'],
            'related' => [['/jasa/seo-lokal', 'SEO lokal'], ['/jasa/seo', 'Technical SEO'], ['/jasa/website', 'Website bisnis']],
        ],
        'jasa/otomasi/n8n' => [
            'label' => 'n8n Workflow Automation',
            'eyebrow' => 'Self-hosted n8n · webhooks · API orchestration',
            'headline' => 'Bangun workflow n8n yang fleksibel, transparan, dan berada di bawah kendali Anda.',
            'lead' => 'Implementasi n8n self-hosted untuk menghubungkan aplikasi, webhook, database, spreadsheet, CRM, dan notifikasi tanpa alur kotak hitam.',
            'promise' => 'Otomasi yang dapat Anda lihat, audit, kembangkan, dan miliki.',
            'features' => [
                ['title' => 'Self-hosted control', 'text' => 'Deploy di lingkungan yang sesuai dengan kebutuhan akses, biaya, dan kebijakan data Anda.'],
                ['title' => 'Composable workflows', 'text' => 'Rancang node, trigger, kondisi, dan transformasi data yang mudah dipahami tim teknis.'],
                ['title' => 'Resilient execution', 'text' => 'Tambahkan retry, logging, credential handling, dan alert agar workflow lebih siap dipakai harian.'],
            ],
            'signals' => ['n8n VPS setup', 'Webhook trigger', 'API nodes', 'Retry & logs', 'Credential safety', 'Workflow docs'],
            'related' => [['/jasa/otomasi', 'Strategi otomasi'], ['/jasa/aplikasi-web', 'Sistem internal'], ['/jasa/ai', 'AI workflow']],
        ],
        'jasa/ai/content-engine' => [
            'label' => 'AI Content Engine & Programmatic SEO',
            'eyebrow' => 'Content systems · entity coverage · editorial control',
            'headline' => 'Produksi konten lebih konsisten tanpa mengorbankan konteks dan kualitas.',
            'lead' => 'Sistem content engine berbasis AI untuk riset entity, brief, draft, internal link, dan produksi halaman yang tetap melewati human review.',
            'promise' => 'Skala produksi yang lebih besar dengan standar editorial yang tetap terlihat manusia.',
            'features' => [
                ['title' => 'Entity-led planning', 'text' => 'Rancang topik dari hubungan entity, intent, dan kebutuhan pembaca—bukan sekadar mengejar jumlah keyword.'],
                ['title' => 'Programmatic guardrails', 'text' => 'Template, data, validasi, dan quality gate membantu mencegah halaman tipis atau repetitif.'],
                ['title' => 'Editorial ownership', 'text' => 'Tim tetap dapat meninjau, memperbaiki, menyetujui, dan mengembangkan konten sebelum dipublikasikan.'],
            ],
            'signals' => ['Entity research', 'Content brief', 'Internal links', 'Human-in-the-loop', 'WebP images', 'Quality gates'],
            'related' => [['/jasa/ai', 'Strategi AI bisnis'], ['/jasa/seo', 'SEO teknis dan organik'], ['/jasa/otomasi', 'Publikasi otomatis']],
        ],
        default => [],
    };
    if ($specific) {
        $service = array_replace($service, $specific);
    }
    $contentBlocks = match ($slug) {
        'jasa/seo' => [
            ['kicker' => '01 · Arah strategi', 'title' => 'SEO Solo dimulai dari pelanggan, bukan dari daftar keyword.', 'text' => 'Bisnis di Solo memiliki pola pencarian yang berbeda antara jasa profesional, kuliner, manufaktur, pendidikan, properti, kesehatan, dan toko lokal. Karena itu kami memulai dari siapa yang ingin dijangkau, masalah yang mereka ketik di Google, area layanan yang benar-benar dilayani, serta tindakan yang diharapkan setelah mereka menemukan website. Hasilnya adalah prioritas SEO yang menghubungkan pencarian dengan inquiry, telepon, kunjungan toko, atau percakapan WhatsApp.'],
            ['kicker' => '02 · Audit teknis', 'title' => 'Buat Google dapat merayapi dan memahami aset penting Anda.', 'text' => 'Audit teknis memeriksa status index, robots.txt, sitemap, canonical, redirect, struktur heading, internal link, data terstruktur, broken link, dan halaman yang saling bersaing. Kami juga melihat Core Web Vitals, ukuran aset, respons mobile, serta pengalaman pengguna di perangkat yang umum digunakan pelanggan Solo. Perbaikan dilakukan berdasarkan dampak bisnis, sehingga halaman layanan dan halaman lokasi yang paling penting tidak tertutup oleh masalah teknis yang sebenarnya dapat dicegah.'],
            ['kicker' => '03 · Search intent', 'title' => 'Pisahkan pencarian informasi dari pencarian yang sudah siap membeli.', 'text' => 'Tidak semua traffic memiliki nilai yang sama. Kami mengelompokkan keyword berdasarkan intent: mencari informasi, membandingkan solusi, mencari vendor, atau siap menghubungi. Kelompok tersebut kemudian dipetakan ke halaman layanan, halaman perbandingan, FAQ, atau konten pendukung. Untuk target seperti jasa SEO Solo, web developer Solo, atau SEO Google Maps, halaman dibuat menjawab kebutuhan komersial secara langsung tanpa memaksa keyword berulang di setiap paragraf.'],
            ['kicker' => '04 · Entity & topical map', 'title' => 'Bangun hubungan topik yang membuat otoritas lebih mudah dipahami.', 'text' => 'Mesin pencari tidak hanya membaca satu frasa; ia memahami hubungan antara layanan, lokasi, industri, masalah, teknologi, dan bukti. Kami menyusun peta entity yang menghubungkan SEO teknis, SEO lokal, Google Business Profile, schema markup, content strategy, website, dan konversi. Internal link dibuat untuk membantu pengunjung bergerak dari kebutuhan awal ke solusi yang relevan, sekaligus membantu crawler menemukan halaman penting dengan konteks yang konsisten.'],
            ['kicker' => '05 · SEO lokal', 'title' => 'Perkuat kehadiran bisnis di Solo Raya dengan sinyal lokal yang nyata.', 'text' => 'SEO lokal bukan sekadar menambahkan kata Solo pada judul. Kami mengevaluasi Google Business Profile, kategori, deskripsi layanan, foto, ulasan, konsistensi nama-alamat-telepon, halaman area, dan hubungan antara website dengan profil peta. Area seperti Surakarta, Sukoharjo, Karanganyar, Boyolali, dan Klaten dapat diprioritaskan sesuai jangkauan bisnis, tanpa membuat halaman lokasi tipis atau mengklaim wilayah yang tidak Anda layani.'],
            ['kicker' => '06 · Content that converts', 'title' => 'Konten SEO harus membantu orang mengambil keputusan.', 'text' => 'Konten yang baik tidak berhenti pada ranking. Ia menjelaskan masalah, menunjukkan pendekatan, memberi contoh, menjawab keberatan, dan menawarkan langkah berikutnya. Untuk bisnis di Solo, konteks lokal seperti karakter pelanggan, area operasional, proses konsultasi, dan cara mendapatkan penawaran membuat halaman terasa lebih nyata. Setiap konten memiliki peran dalam funnel: menarik perhatian, membangun kepercayaan, atau membantu calon pelanggan menghubungi tim Anda.'],
            ['kicker' => '07 · Schema & measurement', 'title' => 'Data terstruktur dan pengukuran membuat optimasi lebih dapat dipertanggungjawabkan.', 'text' => 'Schema Service, Organization, BreadcrumbList, FAQPage, dan tipe lain digunakan hanya ketika sesuai dengan konten yang terlihat. Kami menyiapkan metadata, canonical, Open Graph, sitemap, serta jalur internal link agar halaman mudah dibaca pengguna maupun mesin pencari. Perkembangan kemudian dipantau melalui Search Console, analytics, query, landing page, dan kualitas inquiry—bukan hanya posisi ranking yang dapat berubah setiap hari.'],
            ['kicker' => '08 · Kolaborasi lokal', 'title' => 'Bekerja dengan partner SEO di Solo memberi ruang untuk memahami konteks bisnis.', 'text' => 'Konsultasi langsung membantu kami memahami produk, bahasa pelanggan, musim penjualan, kompetitor lokal, dan target yang tidak selalu terlihat di data keyword. Anda dapat memulai dari audit, memperbaiki halaman utama, mengoptimalkan Google Business Profile, atau menyusun roadmap enam bulan. Scope dibuat bertahap agar investasi SEO tetap masuk akal untuk UMKM, profesional, maupun perusahaan yang sedang memperluas pasar di Solo Raya.'],
            ['kicker' => '09 · Ekosistem digital', 'title' => 'SEO bekerja lebih kuat ketika website dan operasionalnya siap.', 'text' => 'Jika website lambat, CTA tidak jelas, atau lead tidak ditindaklanjuti, traffic organik belum tentu menjadi pertumbuhan. Karena itu strategi SEO dapat dihubungkan dengan jasa pembuatan website, landing page, otomasi notifikasi WhatsApp, dan integrasi CRM. Pengunjung yang datang dari Google memperoleh pengalaman yang konsisten dari halaman pertama sampai percakapan penjualan, sementara tim dapat mengukur bagian mana yang perlu diperbaiki berikutnya.'],
            ['kicker' => '10 · Langkah mulai', 'title' => 'Mulai dengan gambaran kondisi yang nyata, bukan janji ranking.', 'text' => 'Dalam konsultasi awal, kami memetakan tujuan bisnis, layanan yang paling menguntungkan, area target, kondisi website, aset Google Business Profile, serta kapasitas pembuatan konten. Anda akan mendapatkan rekomendasi prioritas yang dapat dikerjakan sekarang dan pekerjaan lanjutan yang dapat ditunda. Hubungi Juki melalui WhatsApp untuk membahas kebutuhan SEO profesional di Solo secara terbuka dan terukur.'],
        ],
        'jasa/otomasi' => [
            ['kicker' => '01 · Diagnosis proses', 'title' => 'Otomasi yang baik dimulai dari pekerjaan yang paling menguras waktu.', 'text' => 'Setiap bisnis di Solo memiliki alur berbeda. Ada yang menerima lead dari website, marketplace, WhatsApp, dan Instagram; ada yang masih memindahkan data order ke spreadsheet; ada pula yang harus mengecek pembayaran dan membuat laporan secara manual. Kami memetakan trigger, data, keputusan, pemilik tugas, dan hasil akhir sebelum memilih platform. Dengan begitu otomasi menyelesaikan bottleneck nyata, bukan sekadar menambah aplikasi baru.'],
            ['kicker' => '02 · Lead masuk', 'title' => 'Jangan biarkan prospek menunggu karena data berpindah tangan terlalu lama.', 'text' => 'Ketika formulir website diisi, sistem dapat memvalidasi data, memberi label kebutuhan, menyimpan kontak ke CRM, memberi notifikasi kepada sales, dan mengirim balasan awal melalui email atau WhatsApp. Tim tetap memegang percakapan dan keputusan, tetapi tidak perlu menyalin nama, nomor, dan detail kebutuhan berkali-kali. Untuk bisnis lokal Solo, kecepatan respons sering menjadi pembeda ketika calon pelanggan menghubungi beberapa vendor sekaligus.'],
            ['kicker' => '03 · Integrasi data', 'title' => 'Hubungkan sistem tanpa membuat tim bekerja di lima tempat berbeda.', 'text' => 'Website, spreadsheet, CRM, payment gateway, email, database, dan REST API dapat menjadi bagian dari satu alur yang terkoordinasi. Kami menentukan sumber data utama, format field, aturan duplikasi, serta kapan data boleh diperbarui. Integrasi yang dirancang rapi mengurangi kesalahan input dan membuat status pelanggan lebih mudah dilihat, baik oleh pemilik bisnis, admin, sales, maupun tim operasional.'],
            ['kicker' => '04 · WhatsApp workflow', 'title' => 'Gunakan WhatsApp sebagai kanal komunikasi, bukan tempat menyimpan semua data.', 'text' => 'WhatsApp sangat penting bagi bisnis Solo, tetapi percakapan mudah tercecer ketika tidak dihubungkan dengan proses internal. Otomasi dapat membuat notifikasi lead, pengingat jadwal, konfirmasi order, atau update status dengan format yang jelas. Data inti tetap disimpan pada sistem yang tepat sehingga tim dapat mencari riwayat dan menindaklanjuti pelanggan tanpa bergantung pada ingatan satu orang.'],
            ['kicker' => '05 · n8n dan API', 'title' => 'Bangun workflow fleksibel dengan n8n, webhook, dan API yang dapat diaudit.', 'text' => 'Untuk kebutuhan multi-langkah, n8n dapat menjadi orkestrator yang menghubungkan trigger, transformasi data, kondisi, dan tujuan akhir. Kami dapat menyiapkan n8n self-hosted di VPS, mengatur credential, membuat webhook, menangani response API, dan memberi log eksekusi. Pilihan ini cocok ketika bisnis ingin kontrol lebih besar atas workflow, biaya, dan data dibanding solusi otomasi yang sepenuhnya tertutup.'],
            ['kicker' => '06 · Reliability', 'title' => 'Workflow produksi harus memiliki jalan keluar ketika sesuatu gagal.', 'text' => 'Otomasi tanpa monitoring hanya memindahkan risiko dari pekerjaan manual ke error yang tidak terlihat. Karena itu kami menyiapkan validasi input, retry terbatas, error branch, log, alert, dan prosedur pemeriksaan. Jika payment gateway tidak merespons atau API sedang bermasalah, tim mendapat informasi yang dapat ditindaklanjuti. Setiap workflow juga memiliki dokumentasi agar tidak menjadi pengetahuan eksklusif satu developer.'],
            ['kicker' => '07 · Operasional Solo', 'title' => 'Buat sistem yang sesuai kapasitas tim, bukan terlalu rumit untuk digunakan.', 'text' => 'Bisnis kecil dan menengah di Solo sering membutuhkan hasil cepat dengan jumlah pengguna yang terbatas. Kami menghindari otomasi berlebihan dan memulai dari satu proses dengan dampak jelas, misalnya distribusi lead, pengingat follow-up, atau rekap order. Setelah alur stabil, sistem dapat diperluas ke CRM, inventori, laporan, dan AI. Pendekatan bertahap membuat tim lebih mudah menerima perubahan.'],
            ['kicker' => '08 · Security', 'title' => 'Akses, credential, dan data pelanggan harus diperlakukan sebagai aset sensitif.', 'text' => 'Setiap integrasi membutuhkan batas akses yang jelas. Kami mempertimbangkan secret management, role pengguna, webhook validation, data minimization, backup, dan log aktivitas. Tidak semua data perlu dikirim ke semua platform. Desain yang aman membantu bisnis menjaga kepercayaan pelanggan sekaligus memudahkan audit ketika alur bertambah kompleks.'],
            ['kicker' => '09 · Website sebagai trigger', 'title' => 'Website yang bagus dapat menjadi pintu masuk workflow bisnis.', 'text' => 'Landing page, toko online, company profile, dan aplikasi web dapat mengirim data yang lebih terstruktur ke proses internal. Inilah alasan otomasi sering dirancang bersama website, SEO, dan formulir inquiry. Pengunjung mendapatkan pengalaman yang cepat dan jelas, sedangkan tim menerima data yang siap diproses tanpa pekerjaan copy-paste. Internal link ke layanan website dan aplikasi membantu Anda memilih fondasi yang tepat.'],
            ['kicker' => '10 · Langkah mulai', 'title' => 'Mulai dari satu alur yang paling sering berulang.', 'text' => 'Kirim contoh proses manual, tools yang sudah dipakai, volume transaksi, dan masalah yang paling sering terjadi. Kami bantu menentukan apakah solusi terbaik berupa n8n, API integration, perubahan formulir, dashboard, atau kombinasi beberapa pendekatan. Konsultasikan jasa otomasi bisnis di Solo melalui WhatsApp untuk mendapatkan roadmap yang realistis dan dapat diuji.'],
        ],
        'jasa/ai' => [
            ['kicker' => '01 · AI yang tepat guna', 'title' => 'Mulai dari masalah bisnis, bukan dari tren model terbaru.', 'text' => 'AI tidak selalu perlu menjadi chatbot publik. Untuk bisnis di Solo, manfaat yang lebih cepat dapat muncul dari pencarian dokumen internal, pembuatan brief, klasifikasi lead, analisis feedback, atau bantuan customer service. Kami membantu memilih masalah dengan frekuensi tinggi, data yang cukup, dan hasil yang dapat diukur. Dengan fokus tersebut, AI menjadi alat kerja yang menyelesaikan hambatan nyata, bukan demo yang berhenti setelah presentasi.'],
            ['kicker' => '02 · Knowledge assistant', 'title' => 'Buat pengetahuan perusahaan dapat ditemukan tanpa membuka puluhan file.', 'text' => 'SOP, katalog, kebijakan, proposal, manual produk, dan dokumen operasional sering tersebar di folder atau chat. Knowledge assistant dapat membantu tim menemukan jawaban berdasarkan sumber yang disetujui, menampilkan konteks, dan mengarahkan pengguna ke dokumen asli. Sistem tidak menggantikan pemilik proses; ia mengurangi waktu mencari informasi sehingga staf dapat melayani pelanggan dan mengambil keputusan lebih cepat.'],
            ['kicker' => '03 · Content intelligence', 'title' => 'Percepat riset konten SEO tanpa menyerahkan suara brand kepada mesin.', 'text' => 'AI dapat membantu mengelompokkan entity, merangkum pertanyaan pelanggan, menemukan gap topik, dan menyusun brief. Namun setiap output tetap memerlukan arahan brand, pemeriksaan fakta, pengalaman lokal, serta review manusia. Untuk target Solo, konten harus terasa memahami area dan kebutuhan pembaca, bukan sekadar mengganti nama kota pada template yang sama. Hasil akhirnya adalah proses produksi yang lebih konsisten dan dapat dievaluasi.'],
            ['kicker' => '04 · Human-in-the-loop', 'title' => 'Kecepatan AI harus berjalan bersama akurasi dan tanggung jawab.', 'text' => 'Kami menetapkan titik pemeriksaan manusia pada output yang memengaruhi pelanggan, reputasi, legalitas, dan keputusan bisnis. Sistem dapat memberi confidence signal, sumber rujukan, aturan penolakan, serta alur persetujuan. Pendekatan ini mencegah jawaban yang terdengar meyakinkan tetapi salah, menjaga kerahasiaan informasi, dan membuat tim memahami kapan AI boleh membantu serta kapan keputusan harus dikembalikan kepada manusia.'],
            ['kicker' => '05 · Integrasi workflow', 'title' => 'Hubungkan AI ke proses yang sudah digunakan tim.', 'text' => 'AI dapat menerima data dari formulir website, CRM, spreadsheet, database, email, atau webhook; kemudian menghasilkan ringkasan, klasifikasi, draft balasan, atau rekomendasi untuk langkah berikutnya. Integrasi bukan berarti semua pekerjaan dijalankan otomatis. Kami menentukan approval, penyimpanan output, akses role, dan notifikasi agar AI memperkuat workflow yang ada tanpa membuat operasional menjadi sulit dipahami.'],
            ['kicker' => '06 · Data dan keamanan', 'title' => 'Data bisnis perlu memiliki batas sebelum digunakan oleh sistem AI.', 'text' => 'Kami memetakan jenis data, pemilik akses, masa simpan, sumber pengetahuan, dan risiko kebocoran. Dokumen yang bersifat rahasia tidak boleh diperlakukan sama dengan konten publik. Desain dapat menggunakan knowledge base terbatas, redaction, role permission, logging, dan human approval sesuai kebutuhan. Tujuannya adalah memperoleh manfaat AI tanpa mengorbankan kepercayaan pelanggan atau kontrol perusahaan.'],
            ['kicker' => '07 · AI untuk bisnis Solo', 'title' => 'Implementasi lokal membutuhkan pemahaman konteks, bahasa, dan pelanggan.', 'text' => 'AI yang membantu bisnis di Solo harus memahami istilah layanan, karakter pelanggan, area operasional, produk, dan kebiasaan tim. Kami dapat memulai dari prototype kecil yang menggunakan contoh dokumen nyata, lalu menguji kualitas jawaban bersama pengguna. Validasi ini penting agar sistem tidak hanya terlihat pintar dalam demo, tetapi benar-benar menghemat waktu pada pekerjaan yang dilakukan setiap hari.'],
            ['kicker' => '08 · Evaluasi', 'title' => 'Ukur AI dari waktu yang dihemat dan kualitas hasilnya.', 'text' => 'Indikator implementasi dapat berupa waktu pencarian dokumen, kecepatan membuat brief, jumlah lead yang terklasifikasi, waktu balas, tingkat koreksi, atau kepuasan pengguna internal. Kami membantu menetapkan baseline dan cara review sehingga bisnis dapat mengetahui apakah sistem layak dikembangkan. Jika hasil belum cukup, prompt, sumber, workflow, atau scope dapat diperbaiki sebelum investasi diperbesar.'],
            ['kicker' => '09 · Ekosistem digital', 'title' => 'AI lebih kuat ketika website, SEO, dan otomasi memiliki fondasi yang rapi.', 'text' => 'Website menyimpan konteks publik, SEO membantu orang menemukan solusi, otomasi mengalirkan data, dan AI membantu tim memahami atau mengolahnya. Keempatnya dapat dirancang bertahap. Anda dapat memulai dari website profesional di Solo, melanjutkan dengan SEO, menambahkan workflow n8n, lalu memasang knowledge assistant atau content engine ketika data dan proses sudah siap.'],
            ['kicker' => '10 · Langkah mulai', 'title' => 'Pilih satu pekerjaan yang ingin dibuat lebih cepat minggu ini.', 'text' => 'Ceritakan tugas yang berulang, dokumen yang sering dicari, pertanyaan pelanggan, atau proses konten yang paling lambat. Kami bantu menilai apakah AI, otomasi biasa, atau perbaikan proses lebih tepat digunakan. Hubungi Juki untuk konsultasi jasa integrasi AI di Solo dengan scope yang jelas, pengujian yang terukur, dan kontrol manusia sejak awal.'],
        ],
        default => [],
    };
    if (in_array($slug, ['jasa/seo', 'jasa/otomasi', 'jasa/ai'], true)) {
        $contentBlocks[] = [
            'kicker' => '11 · Pendekatan Juki',
            'title' => 'Solusi digital yang bisa dipahami, digunakan, dan dikembangkan.',
            'text' => 'Kami percaya partner digital tidak cukup hanya menyerahkan hasil akhir. Bisnis membutuhkan alasan di balik rekomendasi, prioritas yang jelas, serta dokumentasi yang dapat dipakai setelah proyek selesai. Karena itu setiap konsultasi dimulai dari konteks bisnis dan berakhir pada langkah yang dapat dilakukan. Anda dapat memilih scope kecil untuk menguji dampak, menyusun roadmap lanjutan, atau menghubungkan layanan ini dengan website, SEO, otomasi, dan AI. Untuk perusahaan di Solo Raya, komunikasi yang dekat membuat proses review lebih cepat dan keputusan lebih mudah diambil. Tujuan akhirnya bukan teknologi yang terlihat rumit, melainkan sistem yang membantu tim bekerja dan pelanggan mendapatkan pengalaman yang lebih baik.',
        ];
        $contentBlocks[] = [
            'kicker' => '12 · Keputusan yang aman',
            'title' => 'Mulai dari prioritas yang paling memberi dampak.',
            'text' => 'Tidak semua bisnis perlu mengerjakan semuanya sekaligus. Kami membantu membedakan kebutuhan wajib, peluang yang dapat diuji, dan fitur yang sebaiknya menunggu. Dengan pendekatan ini, anggaran dapat diarahkan pada halaman, workflow, data, atau sistem yang benar-benar mendukung target. Setelah hasil awal terlihat, keputusan untuk memperluas pekerjaan dibuat berdasarkan bukti, bukan tekanan untuk membeli layanan tambahan. Inilah cara kami menjaga proyek tetap fokus untuk pemilik bisnis, tim internal, dan pelanggan di Solo.',
        ];
    }
    $empathy = match ($slug) {
        'jasa/website/company-profile' => ['kicker' => 'Untuk perusahaan yang ingin dipercaya', 'before' => 'Calon klien melihat nama perusahaan Anda, tetapi belum menemukan alasan kuat untuk menghubungi.', 'turn' => 'Kami menyusun kapabilitas, pengalaman, dan bukti kerja menjadi cerita yang mudah dipahami.', 'after' => 'Setiap kunjungan terasa seperti langkah awal menuju meeting yang lebih serius.'],
        'jasa/website/landing-page' => ['kicker' => 'Untuk campaign yang tidak boleh menyia-nyiakan klik', 'before' => 'Iklan sudah mendatangkan pengunjung, tetapi halaman belum mampu menjawab mengapa mereka harus memilih Anda.', 'turn' => 'Kami memusatkan pesan, offer, bukti, dan CTA pada satu keputusan yang ingin dibuat pengunjung.', 'after' => 'Traffic tidak berhenti sebagai angka; ia berubah menjadi percakapan dengan konteks.'],
        'jasa/website/toko-online' => ['kicker' => 'Untuk pemilik produk yang ingin punya rumah sendiri', 'before' => 'Penjualan bergantung pada marketplace, sementara pelanggan dan data order tersebar di banyak tempat.', 'turn' => 'Kami merancang pengalaman katalog, produk, checkout, dan follow-up sesuai cara bisnis Anda bekerja.', 'after' => 'Brand memiliki ruang untuk tumbuh dan pelanggan memiliki alasan untuk kembali.'],
        'jasa/website/wordpress' => ['kicker' => 'Untuk tim yang ingin mengelola website tanpa takut merusaknya', 'before' => 'Website terlihat ramai karena plugin, lambat dibuka, dan setiap perubahan kecil terasa berisiko.', 'turn' => 'Kami membangun WordPress yang ringan, terstruktur, dan tetap mudah diedit oleh tim internal.', 'after' => 'Anda memiliki kendali atas konten tanpa harus mengorbankan kecepatan dan rasa percaya.'],
        'jasa/aplikasi-web' => ['kicker' => 'Untuk bisnis yang sudah terlalu besar untuk spreadsheet', 'before' => 'Data penting tersebar, approval tersendat, dan tim menghabiskan waktu mencari status pekerjaan.', 'turn' => 'Kami memetakan proses nyata lalu menerjemahkannya menjadi dashboard dan aplikasi yang dipahami tim.', 'after' => 'Keputusan lebih cepat karena informasi berada di tempat yang tepat.'],
        'jasa/maintenance-website' => ['kicker' => 'Untuk pemilik website yang ingin tidur lebih tenang', 'before' => 'Website memang online, tetapi tidak ada yang benar-benar tahu kapan backup terakhir atau kapan masalah mulai muncul.', 'turn' => 'Kami merawat, memantau, dan mendokumentasikan hal-hal kecil sebelum menjadi gangguan besar.', 'after' => 'Website tetap siap melayani pelanggan, bahkan ketika Anda sedang fokus mengurus bisnis.'],
        'jasa/perbaikan-website' => ['kicker' => 'Untuk saat website tiba-tiba mengecewakan pelanggan', 'before' => 'Halaman error, email berhenti, atau website diretas ketika bisnis sedang membutuhkan kepercayaan.', 'turn' => 'Kami menenangkan situasi, mencari akar masalah, memulihkan layanan, lalu memperkuat titik lemahnya.', 'after' => 'Website kembali menjadi aset yang bisa diandalkan, bukan sumber kepanikan.'],
        'jasa/website/solo' => ['kicker' => 'Untuk bisnis Solo yang ingin punya partner, bukan sekadar vendor', 'before' => 'Anda ingin website yang serius, tetapi tidak ingin tersesat dalam istilah teknis dan komunikasi yang berjarak.', 'turn' => 'Kami berdiskusi dengan bahasa bisnis, memahami konteks Solo Raya, dan menyusun prioritas bersama.', 'after' => 'Anda tahu apa yang dibangun, mengapa dibutuhkan, dan ke mana langkah berikutnya.'],
        'jasa/seo-lokal' => ['kicker' => 'Untuk bisnis yang ingin ditemukan tetangga terdekatnya', 'before' => 'Pelanggan mencari layanan di sekitar Solo, tetapi nama bisnis Anda belum muncul ketika keputusan sedang dibuat.', 'turn' => 'Kami menyatukan Google Business Profile, website, ulasan, dan sinyal lokasi yang benar-benar mewakili bisnis.', 'after' => 'Orang yang mencari di area Anda menemukan bisnis yang siap mereka percaya.'],
        'jasa/google-maps-embed' => ['kicker' => 'Untuk bisnis yang lokasinya harus mudah ditemukan', 'before' => 'Pelanggan tahu mereka membutuhkan layanan Anda, tetapi ragu apakah lokasi, alamat, dan informasi bisnisnya benar.', 'turn' => 'Kami menjadikan lokasi sebagai bagian dari pengalaman digital, bukan hanya pin yang ditempel di footer.', 'after' => 'Pelanggan lebih yakin untuk datang, menelepon, dan mengambil langkah berikutnya.'],
        'jasa/otomasi/n8n' => ['kicker' => 'Untuk tim yang lelah membetulkan workflow yang tidak transparan', 'before' => 'Otomasi lama berjalan seperti kotak hitam: ketika gagal, tidak ada yang tahu data berhenti di mana.', 'turn' => 'Kami membangun n8n workflow dengan log, retry, credential, dokumentasi, dan kontrol yang dapat dipahami.', 'after' => 'Otomasi bekerja sebagai sistem yang bisa diawasi dan dikembangkan, bukan ketergantungan baru.'],
        'jasa/ai/content-engine' => ['kicker' => 'Untuk tim konten yang ingin bertumbuh tanpa kehilangan suara brand', 'before' => 'Produksi konten terasa lambat, tetapi konten AI yang tidak dikurasi juga dapat membuat brand terdengar sama seperti semua orang.', 'turn' => 'Kami menyusun riset entity, brief, drafting, internal link, dan human review dalam satu alur yang terjaga.', 'after' => 'Tim dapat memproduksi lebih konsisten tanpa menyerahkan kepercayaan pelanggan kepada mesin.'],
        default => ['kicker' => 'Untuk bisnis yang ingin bergerak dengan lebih yakin', 'before' => 'Kebutuhan digital sudah terasa, tetapi langkah pertamanya masih terlihat rumit dan penuh risiko.', 'turn' => 'Kami memecah masalah menjadi prioritas yang jelas, solusi yang dapat diuji, dan proses yang dapat dipahami.', 'after' => 'Anda dapat bergerak maju dengan sistem yang sesuai kebutuhan, bukan sekadar mengikuti tren.'],
    };
    $seoOverrides = [
        'jasa/seo' => ['title' => 'Jasa SEO Solo & Optimasi Google Maps untuk Bisnis | Juki', 'description' => 'Jasa SEO di Solo untuk menaikkan visibilitas Google, Google Maps, dan inquiry melalui audit teknis, SEO lokal, konten, schema, dan internal link.'],
        'jasa/otomasi' => ['title' => 'Jasa Otomasi Bisnis di Solo & Integrasi API | Juki', 'description' => 'Jasa otomasi bisnis di Solo untuk menghubungkan website, CRM, WhatsApp, spreadsheet, n8n, dan API agar pekerjaan manual berkurang dan respons lebih cepat.'],
        'jasa/ai' => ['title' => 'Jasa Integrasi AI untuk Bisnis di Solo | Juki', 'description' => 'Jasa integrasi AI di Solo untuk knowledge assistant, content engine, riset SEO, dan workflow bisnis dengan human review, keamanan data, dan hasil terukur.'],
    ];
    $accent = $service['accent'];
    $canonicalUrl = url('/' . $slug);
    $seoTitle = $seoOverrides[$slug]['title'] ?? ($page->meta_title ?: ($service['label'] . ' — ' . $siteName));
    $seoDescription = $seoOverrides[$slug]['description'] ?? ($page->meta_description ?: $service['lead']);
@endphp

@section('title', $seoTitle)
@section('meta_description', $seoDescription)
@section('canonical_url', $canonicalUrl)

@section('head_extra')
<script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@type' => 'Service', 'name' => $service['label'], 'description' => $seoDescription, 'provider' => ['@type' => 'Person', 'name' => 'Tri Marzuki', 'url' => url('/')], 'areaServed' => ['Solo', 'Surakarta', 'Sukoharjo', 'Indonesia'], 'url' => $canonicalUrl], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@type' => 'BreadcrumbList', 'itemListElement' => [['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => url('/')], ['@type' => 'ListItem', 'position' => 2, 'name' => 'Layanan', 'item' => url('/jasa')], ['@type' => 'ListItem', 'position' => 3, 'name' => $service['label'], 'item' => $canonicalUrl]]], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@if($service['faqs'])
<script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => collect($service['faqs'])->map(fn ($faq) => ['@type' => 'Question', 'name' => $faq[0], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $faq[1]]])->values()->all()], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endif
@endsection

@section('styles')
<style>
    .juki-modern { --jm-ink:#15253f; --jm-muted:#5c6878; --jm-gold:#ce9a45; --jm-soft:#fbf6eb; --jm-line:#eadfca; min-height:100vh; color:var(--jm-ink); background:#f8f7f3; font-family:'Plus Jakarta Sans','Inter',ui-sans-serif,system-ui,sans-serif; }
    .juki-modern h1,.juki-modern h2,.juki-modern h3 { font-family:'Outfit','Plus Jakarta Sans',sans-serif; font-weight:700; letter-spacing:-.035em; }
    .juki-modern .jm-wrap { width:min(1160px,calc(100% - 32px)); margin:auto; }
    .juki-modern .jm-hero { position:relative; overflow:hidden; padding:84px 0 74px; color:#fff; background:linear-gradient(135deg,#101d32,#15253f 58%,#263d5e); }
    .juki-modern .jm-hero:after { content:""; position:absolute; width:560px; height:560px; right:-240px; top:-280px; border:1px solid rgba(232,201,130,.3); border-radius:50%; box-shadow:0 0 0 55px rgba(232,201,130,.06),0 0 0 110px rgba(232,201,130,.03); }
    .juki-modern .jm-hero-grid { position:relative; z-index:1; display:grid; grid-template-columns:1.05fr .95fr; gap:64px; align-items:center; }
    .juki-modern .jm-eyebrow,.juki-modern .jm-kicker { color:#dcb86f; font-size:.72rem; font-weight:800; letter-spacing:.14em; text-transform:uppercase; }
    .juki-modern .jm-eyebrow:before { content:""; display:inline-block; width:27px; height:2px; margin:0 9px 3px 0; background:var(--jm-gold); }
    .juki-modern .jm-hero h1 { max-width:690px; margin:18px 0; color:#fff; font-size:clamp(2.6rem,5vw,5rem); line-height:1.01; }
    .juki-modern .jm-lead { max-width:650px; color:#dce4ee; font-size:1.08rem; line-height:1.75; }
    .juki-modern .jm-actions { display:flex; flex-wrap:wrap; gap:12px; margin-top:30px; }
    .juki-modern .jm-btn { display:inline-flex; min-height:48px; align-items:center; justify-content:center; gap:8px; padding:0 20px; border-radius:10px; font-weight:800; text-decoration:none; transition:transform .22s ease,box-shadow .22s ease; }
    .juki-modern .jm-btn:hover { transform:translateY(-3px); box-shadow:0 14px 28px rgba(0,0,0,.2); }
    .juki-modern .jm-btn-primary { color:var(--jm-ink); background:var(--jm-gold); }
    .juki-modern .jm-btn-ghost { color:#fff; border:1px solid rgba(232,201,130,.48); }
    .juki-modern .jm-hero-card { padding:30px; border:1px solid rgba(232,201,130,.28); border-radius:22px; background:rgba(15,23,42,.48); box-shadow:0 28px 70px rgba(0,0,0,.28); }
    .juki-modern .jm-hero-card h2 { margin:10px 0 22px; color:#fff; font-size:1.65rem; line-height:1.15; }
    .juki-modern .jm-promise { margin-bottom:22px; color:#e8c982; font-size:1.25rem; font-weight:700; line-height:1.3; }
    .juki-modern .jm-check { display:flex; gap:10px; margin:14px 0; color:#dce4ee; line-height:1.5; }
    .juki-modern .jm-check:before { content:'✓'; flex:0 0 20px; color:#e8c982; font-weight:800; }
    .juki-modern .jm-strip { display:grid; grid-template-columns:repeat(4,1fr); gap:18px; padding:25px 0; background:#fff; border-bottom:1px solid var(--jm-line); }
    .juki-modern .jm-strip .jm-wrap { display:grid; grid-template-columns:repeat(4,1fr); gap:18px; }
    .juki-modern .jm-strip strong { display:block; font-family:'Outfit',sans-serif; font-size:1rem; }
    .juki-modern .jm-strip span { display:block; margin-top:4px; color:var(--jm-muted); font-size:.8rem; }
    .juki-modern .jm-section { padding:82px 0; }
    .juki-modern .jm-section-alt { background:#fff; }
    .juki-modern .jm-kicker { display:block; color:#9a6b16; }
    .juki-modern .jm-section h2 { max-width:720px; margin:10px 0 15px; font-size:clamp(2rem,3.5vw,3.2rem); line-height:1.05; }
    .juki-modern .jm-intro { max-width:720px; color:var(--jm-muted); font-size:1.05rem; line-height:1.75; }
    .juki-modern .jm-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-top:34px; }
    .juki-modern .jm-card { padding:25px; border:1px solid #e2e8f0; border-radius:17px; background:#fff; box-shadow:0 9px 28px rgba(21,37,63,.06); transition:transform .28s ease,box-shadow .28s ease,border-color .28s ease; }
    .juki-modern .jm-card:hover { transform:translateY(-7px); border-color:#d4ad64; box-shadow:0 20px 40px rgba(154,107,22,.13); }
    .juki-modern .jm-card h3 { margin:0 0 9px; font-size:1.2rem; }
    .juki-modern .jm-card p { margin:0; color:var(--jm-muted); line-height:1.7; }
    .juki-modern .jm-card-wide { grid-column:span 2; background:var(--jm-ink); color:#fff; }
    .juki-modern .jm-card-wide p { color:#d8e0ea; }
    .juki-modern .jm-dark { color:#fff; background:linear-gradient(135deg,#15253f,#213756); }
    .juki-modern .jm-dark h2,.juki-modern .jm-dark .jm-kicker { color:#fff; }
    .juki-modern .jm-dark .jm-kicker { color:#e8c982; }
    .juki-modern .jm-dark .jm-intro,.juki-modern .jm-dark .jm-card p { color:#d5dce6; }
    .juki-modern .jm-dark .jm-card { border-color:rgba(232,201,130,.22); background:rgba(255,255,255,.07); box-shadow:none; }
    .juki-modern .jm-content-grid { display:grid; grid-template-columns:repeat(2,1fr); gap:18px; margin-top:34px; }
    .juki-modern .jm-content-card { padding:28px; border:1px solid var(--jm-line); border-radius:18px; background:linear-gradient(145deg,#fff,#fbf6eb); box-shadow:0 10px 28px rgba(21,37,63,.06); }
    .juki-modern .jm-content-card:nth-child(3n) { background:var(--jm-ink); color:#fff; }
    .juki-modern .jm-content-card:nth-child(3n) p { color:#d5dce6; }
    .juki-modern .jm-content-card h3 { margin:10px 0 12px; font-size:1.35rem; line-height:1.15; }
    .juki-modern .jm-content-card p { margin:0; color:var(--jm-muted); line-height:1.78; }
    .juki-modern .jm-content-card .jm-kicker { font-size:.65rem; }
    .juki-modern .jm-empathy { color:#fff; background:linear-gradient(125deg,#101d32,#1c3150); }
    .juki-modern .jm-empathy h2,.juki-modern .jm-empathy .jm-kicker { color:#fff; }
    .juki-modern .jm-empathy .jm-kicker { color:#e8c982; }
    .juki-modern .jm-empathy-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:0; margin-top:34px; }
    .juki-modern .jm-empathy-card { min-height:240px; padding:28px; border:1px solid rgba(232,201,130,.22); background:rgba(255,255,255,.06); }
    .juki-modern .jm-empathy-card:first-child { border-radius:18px 0 0 18px; }
    .juki-modern .jm-empathy-card:last-child { border-radius:0 18px 18px 0; background:rgba(206,154,69,.16); }
    .juki-modern .jm-empathy-card b { display:block; margin-bottom:18px; color:#e8c982; font-family:'Outfit',sans-serif; font-size:.78rem; letter-spacing:.12em; text-transform:uppercase; }
    .juki-modern .jm-empathy-card p { margin:0; color:#e1e8f0; font-size:1.08rem; line-height:1.7; }
    .juki-modern .jm-timeline { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-top:34px; }
    .juki-modern .jm-step { position:relative; padding:22px; border-top:3px solid var(--jm-gold); background:var(--jm-soft); }
    .juki-modern .jm-step b { color:#9a6b16; font-size:.75rem; letter-spacing:.12em; }
    .juki-modern .jm-step h3 { margin:12px 0 7px; font-size:1.1rem; }
    .juki-modern .jm-step p { margin:0; color:var(--jm-muted); line-height:1.6; }
    .juki-modern .jm-related { display:grid; grid-template-columns:repeat(3,1fr); gap:14px; margin-top:30px; }
    .juki-modern .jm-related a { display:flex; justify-content:space-between; gap:14px; padding:20px; border:1px solid var(--jm-line); border-radius:14px; color:var(--jm-ink); background:var(--jm-soft); font-weight:800; text-decoration:none; }
    .juki-modern .jm-faq { display:grid; gap:12px; max-width:850px; margin-top:28px; }
    .juki-modern details { border:1px solid #e2e8f0; border-radius:12px; background:#fff; }
    .juki-modern summary { cursor:pointer; padding:18px 20px; font-weight:800; }
    .juki-modern details p { margin:0; padding:0 20px 20px; color:var(--jm-muted); line-height:1.7; }
    .juki-modern .jm-final { padding:76px 0; color:#fff; text-align:center; background:var(--jm-ink); }
    .juki-modern .jm-final h2 { max-width:700px; margin:10px auto 15px; color:#fff; }
    .juki-modern .jm-final p { max-width:640px; margin:auto; color:#d5dce6; line-height:1.7; }
    .juki-modern [data-reveal] { opacity:0; transform:translateY(18px); transition:opacity .6s ease,transform .6s ease; }
    .juki-modern [data-reveal].is-visible { opacity:1; transform:none; }
    .juki-modern a:focus-visible { outline:3px solid #fbbf24; outline-offset:3px; }
    @media (max-width:900px) { .juki-modern .jm-hero-grid { grid-template-columns:1fr; gap:35px; } .juki-modern .jm-grid,.juki-modern .jm-timeline,.juki-modern .jm-content-grid,.juki-modern .jm-empathy-grid { grid-template-columns:repeat(2,1fr); } .juki-modern .jm-card-wide { grid-column:auto; } }
    @media (max-width:620px) { .juki-modern .jm-wrap { width:min(100% - 24px,1160px); } .juki-modern .jm-hero { padding:58px 0; } .juki-modern .jm-strip,.juki-modern .jm-strip .jm-wrap,.juki-modern .jm-grid,.juki-modern .jm-timeline,.juki-modern .jm-content-grid,.juki-modern .jm-empathy-grid,.juki-modern .jm-related { grid-template-columns:1fr; } .juki-modern .jm-section { padding:58px 0; } .juki-modern .jm-actions .jm-btn { width:100%; } .juki-modern .jm-empathy-card:first-child,.juki-modern .jm-empathy-card:last-child { border-radius:0; } .juki-modern .jm-empathy-card:first-child { border-radius:18px 18px 0 0; } .juki-modern .jm-empathy-card:last-child { border-radius:0 0 18px 18px; } }
    @media (prefers-reduced-motion:reduce) { .juki-modern * { animation:none!important; transition:none!important; scroll-behavior:auto!important; } .juki-modern [data-reveal] { opacity:1; transform:none; } }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var page = document.querySelector('.juki-modern');
    if (!page) return;
    var items = page.querySelectorAll('[data-reveal]');
    if (!('IntersectionObserver' in window)) { items.forEach(function (item) { item.classList.add('is-visible'); }); return; }
    var observer = new IntersectionObserver(function (entries, current) {
        entries.forEach(function (entry) { if (!entry.isIntersecting) return; entry.target.classList.add('is-visible'); current.unobserve(entry.target); });
    }, { rootMargin: '0px 0px -8% 0px', threshold: .08 });
    items.forEach(function (item) { observer.observe(item); });
    page.querySelectorAll('img').forEach(function (image) { if (!image.hasAttribute('loading')) image.setAttribute('loading', 'lazy'); image.setAttribute('decoding', 'async'); });
});
</script>
@endsection

@section('content')
<main class="juki-modern">
    <section class="jm-hero">
        <div class="jm-wrap jm-hero-grid">
            <div><span class="jm-eyebrow">{{ $service['eyebrow'] }}</span><h1>{{ $service['headline'] }}</h1><p class="jm-lead">{{ $service['lead'] }}</p><div class="jm-actions"><a class="jm-btn jm-btn-primary" href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Halo Tri Marzuki, saya ingin konsultasi layanan '.$service['label'].'.') }}">Konsultasi via WhatsApp →</a><a class="jm-btn jm-btn-ghost" href="{{ url('/contact') }}">Kirim brief proyek</a></div></div>
            <aside class="jm-hero-card"><span class="jm-kicker">{{ $service['label'] }}</span><h2>{{ $service['promise'] }}</h2>@foreach($service['signals'] as $signal)<div class="jm-check">{{ $signal }}</div>@endforeach</aside>
        </div>
    </section>
    <section class="jm-strip"><div class="jm-wrap">@foreach([['Strategic','Mulai dari masalah bisnis'],['Structured','Dibangun dengan sistem'],['Measurable','Bisa dievaluasi'],['Owned','Aset tetap milik Anda']] as $item)<div><strong>{{ $item[0] }}</strong><span>{{ $item[1] }}</span></div>@endforeach</div></section>
    <section class="jm-section" data-reveal><div class="jm-wrap"><span class="jm-kicker">Mengapa layanan ini penting</span><h2>{{ $service['promise'] }}</h2><p class="jm-intro">{{ $service['lead'] }} Kami membantu menerjemahkan kebutuhan yang kompleks menjadi langkah yang terlihat, terukur, dan dapat digunakan oleh tim Anda.</p><div class="jm-grid">@foreach($service['features'] as $index => $feature)<article class="jm-card {{ $index === 0 ? 'jm-card-wide' : '' }}"><span class="jm-kicker">0{{ $index + 1 }} · {{ $service['label'] }}</span><h3>{{ $feature['title'] }}</h3><p>{{ $feature['text'] }}</p></article>@endforeach</div></div></section>
    <section class="jm-section jm-empathy" data-reveal><div class="jm-wrap"><span class="jm-kicker">{{ $empathy['kicker'] }}</span><h2>Karena di balik kebutuhan digital, ada rasa khawatir yang ingin diselesaikan.</h2><div class="jm-empathy-grid"><article class="jm-empathy-card"><b>Sebelum</b><p>{{ $empathy['before'] }}</p></article><article class="jm-empathy-card"><b>Titik balik</b><p>{{ $empathy['turn'] }}</p></article><article class="jm-empathy-card"><b>Sesudah</b><p>{{ $empathy['after'] }}</p></article></div></div></section>
    <section class="jm-section jm-dark" data-reveal><div class="jm-wrap"><span class="jm-kicker">Yang masuk ke dalam strategi</span><h2>Setiap komponen memiliki pekerjaan yang jelas.</h2><p class="jm-intro">Bukan daftar fitur untuk terlihat sibuk. Setiap elemen dipilih karena membantu visibilitas, kecepatan kerja, akurasi, atau keputusan bisnis.</p><div class="jm-grid">@foreach($service['signals'] as $signal)<article class="jm-card"><span class="jm-kicker">Signal {{ str_pad((string) ($loop->index + 1), 2, '0', STR_PAD_LEFT) }}</span><h3>{{ $signal }}</h3><p>Dirancang sebagai bagian dari sistem layanan {{ strtolower($service['label']) }}, bukan solusi terpisah yang sulit dipelihara.</p></article>@endforeach</div></div></section>
    @if($contentBlocks)<section class="jm-section jm-section-alt" data-reveal><div class="jm-wrap"><span class="jm-kicker">Panduan strategi {{ $service['label'] }} di Solo</span><h2>Modul yang menghubungkan kebutuhan bisnis dengan langkah digital yang nyata.</h2><p class="jm-intro">Landing page ini dirancang untuk membantu Anda memahami ruang lingkup layanan, bukan sekadar membaca daftar fitur. Setiap modul menjawab pertanyaan yang biasanya muncul sebelum bisnis memilih partner.</p><div class="jm-content-grid">@foreach($contentBlocks as $block)<article class="jm-content-card"><span class="jm-kicker">{{ $block['kicker'] }}</span><h3>{{ $block['title'] }}</h3><p>{{ $block['text'] }}</p></article>@endforeach</div></div></section>@endif
    <section class="jm-section jm-section-alt" data-reveal><div class="jm-wrap"><span class="jm-kicker">Cara kerja</span><h2>Dari kebutuhan yang kabur menjadi sistem yang bisa dijalankan.</h2><div class="jm-timeline">@foreach($service['steps'] as $step)<article class="jm-step"><b>0{{ $loop->iteration }}</b><h3>{{ $step }}</h3><p>Tujuan, batasan, dan hasil tahap ini disepakati agar proyek bergerak dengan arah yang sama.</p></article>@endforeach</div></div></section>
    <section class="jm-section" data-reveal><div class="jm-wrap"><span class="jm-kicker">Terhubung dengan ekosistem Juki</span><h2>Satu layanan dapat membuka peluang berikutnya.</h2><p class="jm-intro">Bangun sistem secara bertahap tanpa kehilangan arah. Pilih layanan yang paling relevan dengan posisi bisnis Anda saat ini.</p><div class="jm-related">@foreach($service['related'] as $link)<a href="{{ url($link[0]) }}"><span>{{ $link[1] }}</span><span aria-hidden="true">↗</span></a>@endforeach</div></div></section>
    @if($service['faqs'])<section class="jm-section jm-section-alt" data-reveal><div class="jm-wrap"><span class="jm-kicker">Pertanyaan umum</span><h2>Hal yang perlu jelas sebelum mulai.</h2><div class="jm-faq">@foreach($service['faqs'] as $faq)<details><summary>{{ $faq[0] }}</summary><p>{{ $faq[1] }}</p></details>@endforeach</div></div></section>@endif
    <section class="jm-final"><div class="jm-wrap"><span class="jm-kicker">Punya proyek dalam pikiran?</span><h2>Siap membangun sistem yang lebih masuk akal untuk bisnis Anda?</h2><p>Ceritakan kondisi Anda sekarang. Kami bantu menemukan titik mulai yang paling relevan tanpa memaksakan paket yang tidak dibutuhkan.</p><div class="jm-actions" style="justify-content:center"><a class="jm-btn jm-btn-primary" href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Halo Tri Marzuki, saya ingin konsultasi layanan '.$service['label'].'.') }}">WhatsApp Saya →</a><a class="jm-btn jm-btn-ghost" href="{{ url('/contact') }}">Kirim brief</a></div></div></section>
</main>
@endsection
