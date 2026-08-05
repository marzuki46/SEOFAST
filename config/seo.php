<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SEO URL Aliases
    |--------------------------------------------------------------------------
    |
    | Peta slug halaman lama/duplikat ke URL kanonik (301 redirect).
    | Key  = slug halaman (di-route oleh PageController).
    | Value = path tujuan lengkap (tanpa domain, dengan leading slash).
    |
    | Halaman yang terdaftar di sini otomatis:
    |   - di-301 ke target oleh PageController
    |   - dikecualikan dari sitemap-static
    |
    */

    'page_aliases' => [
        'about'       => '/about-us',
        'produk2'     => '/produk',
        'landing-juki' => '/en',
    ],

];
