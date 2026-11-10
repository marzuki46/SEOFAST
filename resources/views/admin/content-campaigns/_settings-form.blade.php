<div class="space-y-5" x-data="{ tab: 'prompts' }">
    <div class="rounded-xl border border-indigo-100 bg-indigo-50 p-4">
        <p class="text-sm font-semibold text-indigo-900">{{ $campaign->name }}</p>
        <p class="mt-1 text-xs text-indigo-700">Pengaturan prompt dan data reusable ini dipakai otomatis untuk seluruh artikel campaign.</p>
    </div>

    <nav aria-label="Pengaturan campaign" class="flex flex-wrap gap-2 border-b border-slate-200 pb-3">
        <button type="button" @click="tab = 'prompts'" :aria-current="tab === 'prompts' ? 'page' : undefined" :class="tab === 'prompts' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100'" class="rounded-lg px-4 py-2 text-sm font-semibold">Prompt AI</button>
        <button type="button" @click="tab = 'data'" :aria-current="tab === 'data' ? 'page' : undefined" :class="tab === 'data' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100'" class="rounded-lg px-4 py-2 text-sm font-semibold">Data & Gaya Bahasa</button>
        <button type="button" @click="tab = 'output'" :aria-current="tab === 'output' ? 'page' : undefined" :class="tab === 'output' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100'" class="rounded-lg px-4 py-2 text-sm font-semibold">Output Artikel</button>
    </nav>

    <form method="POST" action="{{ route('admin.content-campaigns.settings.update', $campaign) }}" class="space-y-5">@csrf @method('PUT')
        <section x-show="tab === 'prompts'" class="space-y-4">
            <div><h3 class="text-lg font-bold text-slate-900">Prompt reusable</h3><p class="text-sm text-slate-500">Jangan memasukkan API key atau data rahasia.</p></div>
            <label class="block text-sm font-semibold">Prompt target keyword / topic research<textarea name="keyword_prompt" required maxlength="6000" rows="5" class="mt-1 w-full rounded-lg border-slate-300">{{ old('keyword_prompt', $campaign->keyword_prompt) }}</textarea></label>
            <label class="block text-sm font-semibold">Prompt entity / LSI keyword<textarea name="entity_prompt" required maxlength="6000" rows="5" class="mt-1 w-full rounded-lg border-slate-300">{{ old('entity_prompt', $campaign->entity_prompt) }}</textarea></label>
            <label class="block text-sm font-semibold">Prompt landing page<textarea name="landing_prompt" required maxlength="6000" rows="4" class="mt-1 w-full rounded-lg border-slate-300">{{ old('landing_prompt', $campaign->landing_prompt) }}</textarea></label>
            <label class="block text-sm font-semibold">Prompt penulisan artikel<textarea name="content_prompt" required maxlength="6000" rows="5" class="mt-1 w-full rounded-lg border-slate-300">{{ old('content_prompt', $campaign->content_prompt) }}</textarea></label>
        </section>

        <section x-show="tab === 'data'" x-cloak class="space-y-4">
            <div><h3 class="text-lg font-bold text-slate-900">Data reusable</h3><p class="text-sm text-slate-500">Konteks bersama untuk riset topik, landing page, entity, dan artikel.</p></div>
            <div class="grid gap-4 md:grid-cols-2"><label class="text-sm font-semibold">Niche<input name="niche" maxlength="255" value="{{ old('niche', $campaign->niche) }}" class="mt-1 w-full rounded-lg border-slate-300"></label><label class="text-sm font-semibold">Brand voice<input name="brand_voice" maxlength="255" value="{{ old('brand_voice', $campaign->brand_voice) }}" class="mt-1 w-full rounded-lg border-slate-300"></label><label class="text-sm font-semibold md:col-span-2">Target audiens<input name="audience" maxlength="500" value="{{ old('audience', $campaign->audience) }}" class="mt-1 w-full rounded-lg border-slate-300"></label><label class="text-sm font-semibold md:col-span-2">Entity seed<textarea name="entity_seed" maxlength="500" rows="2" class="mt-1 w-full rounded-lg border-slate-300">{{ old('entity_seed', $campaign->entity_seed) }}</textarea></label><label class="text-sm font-semibold md:col-span-2">Data bisnis / knowledge base<textarea name="business_data" maxlength="10000" rows="8" class="mt-1 w-full rounded-lg border-slate-300">{{ old('business_data', $campaign->business_data) }}</textarea></label></div>
        </section>

        <section x-show="tab === 'output'" x-cloak class="space-y-4">
            <div><h3 class="text-lg font-bold text-slate-900">Output & CTA</h3><p class="text-sm text-slate-500">Tanggal dan waktu aktual otomatis ditambahkan ke prompt AI sesuai timezone campaign.</p></div>
            <div class="grid gap-4 md:grid-cols-2"><label class="text-sm font-semibold">Minimal kata<input type="number" name="word_count_min" min="300" max="5000" required value="{{ old('word_count_min', $campaign->word_count_min) }}" class="mt-1 w-full rounded-lg border-slate-300"></label><label class="text-sm font-semibold">Maksimal kata<input type="number" name="word_count_max" min="400" max="8000" required value="{{ old('word_count_max', $campaign->word_count_max) }}" class="mt-1 w-full rounded-lg border-slate-300"></label><label class="text-sm font-semibold">Label CTA<input name="cta_label" maxlength="120" value="{{ old('cta_label', $campaign->cta_label) }}" class="mt-1 w-full rounded-lg border-slate-300"></label><label class="text-sm font-semibold">URL CTA<input type="url" name="cta_url" maxlength="2048" value="{{ old('cta_url', $campaign->cta_url) }}" class="mt-1 w-full rounded-lg border-slate-300"></label></div>
        </section>

        <div class="flex justify-end"><button class="rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white">Simpan Pengaturan</button></div>
    </form>
</div>
