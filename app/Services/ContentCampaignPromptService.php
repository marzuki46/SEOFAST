<?php

namespace App\Services;

use App\Models\ContentCampaign;
use App\Models\SystemSetting;
use Carbon\CarbonImmutable;

class ContentCampaignPromptService
{
    public function defaults(): array
    {
        return [
            'keyword_prompt' => SystemSetting::get('apc_keyword_prompt') ?: <<<'PROMPT'
Riset keyword-first. Buat keyword utama 2-4 kata yang benar-benar dicari, berbeda dari semua keyword lama, tidak memakai nama brand sebagai keyword utama, dan sesuai target landing page. Buat judul natural 45-65 karakter; untuk Bahasa Indonesia gunakan “Cara”, bukan “Panduan”. Hindari judul klise AI dan keyword cannibalization.
PROMPT,
            'entity_prompt' => SystemSetting::get('apc_entity_prompt') ?: <<<'PROMPT'
Untuk setiap topik, hasilkan entity utama, sinonim, atribut, subtopik, pertanyaan terkait, istilah industri, dan variasi anchor yang benar-benar relevan. Entity harus membantu cakupan semantik artikel, bukan daftar keyword acak. Gunakan bahasa target campaign dan hindari pengulangan entity yang sudah sering dipakai.
PROMPT,
            'landing_prompt' => SystemSetting::get('apc_landing_prompt') ?: <<<'PROMPT'
Buat positioning landing page yang jelas dan natural: masalah audiens, manfaat utama, bukti atau batasan yang tersedia, dan CTA yang tidak memaksa. Jangan membuat klaim harga, fitur, angka, atau testimonial yang tidak ada di data bisnis.
PROMPT,
            'content_prompt' => SystemSetting::get('apc_content_prompt') ?: <<<'PROMPT'
Tulis seperti editor manusia yang memahami audiens. Gunakan struktur TOFU/MOFU/BOFU sesuai topic, E-E-A-T, contoh praktis, transisi natural, dan variasi kalimat. Jangan mengulang artikel lama, jangan mengarang fakta bisnis, jangan memakai pola pembuka AI yang klise, dan jangan memaksakan keyword.
PROMPT,
            'niche' => SystemSetting::get('apc_niche', 'Jasa & Solusi Bisnis Digital'),
            'brand_voice' => SystemSetting::get('apc_brand_voice', 'Otoritatif, Solutif, Informatif, Profesional'),
            'audience' => SystemSetting::get('apc_audience', 'Pemilik Bisnis, Calon Klien, Pengambil Keputusan'),
            'business_data' => SystemSetting::get('apc_business_data', ''),
            'cta_label' => SystemSetting::get('apc_cta_label', 'Konsultasi Sekarang via WhatsApp'),
            'cta_url' => SystemSetting::get('apc_cta_url', ''),
        ];
    }

    public function syncGlobalToCampaign(ContentCampaign $campaign): ContentCampaign
    {
        $defaults = $this->defaults();
        $campaign->update([
            'niche' => SystemSetting::get('apc_niche') ?: ($campaign->niche ?: $defaults['niche']),
            'brand_voice' => SystemSetting::get('apc_brand_voice') ?: ($campaign->brand_voice ?: $defaults['brand_voice']),
            'audience' => SystemSetting::get('apc_audience') ?: ($campaign->audience ?: $defaults['audience']),
            'business_data' => SystemSetting::get('apc_business_data') ?: ($campaign->business_data ?: $defaults['business_data']),
            'cta_label' => SystemSetting::get('apc_cta_label') ?: ($campaign->cta_label ?: $defaults['cta_label']),
            'cta_url' => SystemSetting::get('apc_cta_url') ?: ($campaign->cta_url ?: $defaults['cta_url']),
            'keyword_prompt' => SystemSetting::get('apc_keyword_prompt') ?: ($campaign->keyword_prompt ?: $defaults['keyword_prompt']),
            'entity_prompt' => SystemSetting::get('apc_entity_prompt') ?: ($campaign->entity_prompt ?: $defaults['entity_prompt']),
            'landing_prompt' => SystemSetting::get('apc_landing_prompt') ?: ($campaign->landing_prompt ?: $defaults['landing_prompt']),
            'content_prompt' => SystemSetting::get('apc_content_prompt') ?: ($campaign->content_prompt ?: $defaults['content_prompt']),
            'word_count_min' => (int) (SystemSetting::get('apc_word_count_min') ?: ($campaign->word_count_min ?: 900)),
            'word_count_max' => (int) (SystemSetting::get('apc_word_count_max') ?: ($campaign->word_count_max ?: 1400)),
        ]);

        return $campaign->fresh();
    }

    public function applyDefaults(ContentCampaign $campaign): ContentCampaign
    {
        return $this->syncGlobalToCampaign($campaign);
    }

    public function temporalContext(string $timezone = 'UTC'): string
    {
        $now = CarbonImmutable::now($timezone);

        return "KONTEKS WAKTU AKTUAL:\n"
            .'- Tanggal: '.$now->translatedFormat('l, d F Y')."\n"
            .'- Jam: '.$now->format('H:i').' ('.$timezone.")\n"
            .'- Bulan aktif: '.$now->translatedFormat('F Y')."\n"
            .'- Tahun aktif: '.$now->year."\n"
            .'Aturan: gunakan tanggal/tahun aktif jika menyebut waktu, harga, promo, tren, atau pembaruan. Jangan menyebut tahun lama sebagai kondisi saat ini.';
    }
}
