#!/usr/bin/env bash
set -e
cd "D:/Program Marzuki/Projek Framework/SEOFAST" || exit 1

echo '=== STAGE ALL ==='
git add -A

echo
echo '=== STAGED FILES ==='
git diff --cached --stat | tail -25

echo
echo '=== COMMIT ==='
git commit -m "feat: SEO overhaul — sitemap caching, robots fix, EN gating & social sameAs

- SitemapController: origin caching (string XML) dengan invalidasi versi via flushCache()
  yang dipanggil otomatis saat publish/update/delete content, page, product & silo
- Hapus public/robots.txt statis agar route dinamis robots() berfungsi (sitemap ter-expose)
- Sitemap posts kini memfilter body_raw non-kosong (selaras BlogController) — blueprint
  kosong tidak boros crawl budget
- Heuristik dupe slug konservatif: hanya suffix mesin (rand 100-999 / -1..-99) dengan
  induk yang ada — slug tahun seperti checklist-2026 tidak ikut terbuang
- Gating konten EN: sitemap EN & hreflang EN hanya untuk post ber-body EN; halaman EN
  tanpa konten EN di-noindex; switcher bahasa ikut di-gate
- robots(): disallow /login /register /payment; noindex_paths default baru
- Organization schema: sameAs dinamis dari setting + form Social Profiles di admin
  SEO Settings (tab Schema Markup)
- Fix pre-existing: homepage 500 (Undefined \$seoOgImage di pages/show.blade.php)" && echo 'COMMIT OK'

echo
echo '=== PUSH ==='
git push origin master && echo 'PUSH OK'
