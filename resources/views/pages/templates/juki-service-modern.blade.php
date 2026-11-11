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
            'headline' => 'Buat bisnis Anda lebih mudah ditemukan oleh orang yang siap membeli.',
            'lead' => 'SEO teknis, konten berbasis entitas, dan Google Business Profile yang bekerja sebagai satu sistem—bukan sekumpulan trik terpisah.',
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
            'headline' => 'Berhenti membayar waktu tim untuk pekerjaan yang seharusnya berjalan otomatis.',
            'lead' => 'Hubungkan website, CRM, spreadsheet, WhatsApp, email, dan API menjadi alur kerja yang rapi, terukur, dan mudah diawasi.',
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
            'headline' => 'Gunakan AI untuk bergerak lebih cepat tanpa kehilangan kendali.',
            'lead' => 'Rancang sistem AI yang memahami konteks bisnis, mempercepat riset dan pekerjaan tim, serta tetap memiliki validasi manusia di titik yang penting.',
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
    $accent = $service['accent'];
    $canonicalUrl = url('/' . $slug);
    $seoTitle = $page->meta_title ?: ($service['label'] . ' — ' . $siteName);
    $seoDescription = $page->meta_description ?: $service['lead'];
@endphp

@section('title', $seoTitle)
@section('meta_description', $seoDescription)
@section('canonical_url', $canonicalUrl)

@section('head_extra')
<script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@type' => 'Service', 'name' => $service['label'], 'description' => $seoDescription, 'provider' => ['@type' => 'Person', 'name' => 'Tri Marzuki', 'url' => url('/')], 'areaServed' => ['Solo', 'Surakarta', 'Sukoharjo', 'Indonesia'], 'url' => $canonicalUrl], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
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
    @media (max-width:900px) { .juki-modern .jm-hero-grid { grid-template-columns:1fr; gap:35px; } .juki-modern .jm-grid,.juki-modern .jm-timeline { grid-template-columns:repeat(2,1fr); } .juki-modern .jm-card-wide { grid-column:auto; } }
    @media (max-width:620px) { .juki-modern .jm-wrap { width:min(100% - 24px,1160px); } .juki-modern .jm-hero { padding:58px 0; } .juki-modern .jm-strip,.juki-modern .jm-strip .jm-wrap,.juki-modern .jm-grid,.juki-modern .jm-timeline,.juki-modern .jm-related { grid-template-columns:1fr; } .juki-modern .jm-section { padding:58px 0; } .juki-modern .jm-actions .jm-btn { width:100%; } }
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
    <section class="jm-section jm-dark" data-reveal><div class="jm-wrap"><span class="jm-kicker">Yang masuk ke dalam strategi</span><h2>Setiap komponen memiliki pekerjaan yang jelas.</h2><p class="jm-intro">Bukan daftar fitur untuk terlihat sibuk. Setiap elemen dipilih karena membantu visibilitas, kecepatan kerja, akurasi, atau keputusan bisnis.</p><div class="jm-grid">@foreach($service['signals'] as $signal)<article class="jm-card"><span class="jm-kicker">Signal {{ str_pad((string) ($loop->index + 1), 2, '0', STR_PAD_LEFT) }}</span><h3>{{ $signal }}</h3><p>Dirancang sebagai bagian dari sistem layanan {{ strtolower($service['label']) }}, bukan solusi terpisah yang sulit dipelihara.</p></article>@endforeach</div></div></section>
    <section class="jm-section jm-section-alt" data-reveal><div class="jm-wrap"><span class="jm-kicker">Cara kerja</span><h2>Dari kebutuhan yang kabur menjadi sistem yang bisa dijalankan.</h2><div class="jm-timeline">@foreach($service['steps'] as $step)<article class="jm-step"><b>0{{ $loop->iteration }}</b><h3>{{ $step }}</h3><p>Tujuan, batasan, dan hasil tahap ini disepakati agar proyek bergerak dengan arah yang sama.</p></article>@endforeach</div></div></section>
    <section class="jm-section" data-reveal><div class="jm-wrap"><span class="jm-kicker">Terhubung dengan ekosistem Juki</span><h2>Satu layanan dapat membuka peluang berikutnya.</h2><p class="jm-intro">Bangun sistem secara bertahap tanpa kehilangan arah. Pilih layanan yang paling relevan dengan posisi bisnis Anda saat ini.</p><div class="jm-related">@foreach($service['related'] as $link)<a href="{{ url($link[0]) }}"><span>{{ $link[1] }}</span><span aria-hidden="true">↗</span></a>@endforeach</div></div></section>
    @if($service['faqs'])<section class="jm-section jm-section-alt" data-reveal><div class="jm-wrap"><span class="jm-kicker">Pertanyaan umum</span><h2>Hal yang perlu jelas sebelum mulai.</h2><div class="jm-faq">@foreach($service['faqs'] as $faq)<details><summary>{{ $faq[0] }}</summary><p>{{ $faq[1] }}</p></details>@endforeach</div></div></section>@endif
    <section class="jm-final"><div class="jm-wrap"><span class="jm-kicker">Punya proyek dalam pikiran?</span><h2>Siap membangun sistem yang lebih masuk akal untuk bisnis Anda?</h2><p>Ceritakan kondisi Anda sekarang. Kami bantu menemukan titik mulai yang paling relevan tanpa memaksakan paket yang tidak dibutuhkan.</p><div class="jm-actions" style="justify-content:center"><a class="jm-btn jm-btn-primary" href="https://wa.me/{{ $waNumber }}?text={{ urlencode('Halo Tri Marzuki, saya ingin konsultasi layanan '.$service['label'].'.') }}">WhatsApp Saya →</a><a class="jm-btn jm-btn-ghost" href="{{ url('/contact') }}">Kirim brief</a></div></div></section>
</main>
@endsection
